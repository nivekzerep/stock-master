<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Necesario para transacciones
use Illuminate\Support\Str; // Para generar un folio random

class OrderController extends Controller
{
    public function index()
    {
        // Traemos las órdenes con sus detalles para listarlas
        $orders = Order::with('details.product')->orderBy('id', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Solo traemos productos que tengan stock > 0
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        // 1. Solo validamos que hayan seleccionado checkboxes (products)
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ], [
            'products.required' => 'Debe seleccionar al menos un producto marcando su casilla.',
        ]);

        try {
            DB::transaction(function () use ($request) {
                // Creamos la orden principal vacía
                $order = Order::create([
                    'folio' => 'ORD-' . strtoupper(Str::random(6)), 
                    'order_date' => now(),
                    'total' => 0, 
                ]);

                $totalGeneral = 0;
                $productosValidos = 0;

                // Procesamos CADA producto que el usuario *marcó en checkbox*
                foreach ($request->products as $productId) {
                    
                    // ¿Escribió una cantidad para este producto específico en el input?
                    if (!isset($request->quantities[$productId]) || $request->quantities[$productId] <= 0) {
                        throw new \Exception("Debe ingresar una cantidad válida (mayor a 0) para cada producto seleccionado.");
                    }

                    $cantidadSolicitada = $request->quantities[$productId];
                    $producto = Product::findOrFail($productId);

                    // Validamos stock
                    if ($cantidadSolicitada > $producto->stock) {
                        throw new \Exception("Stock insuficiente para: " . $producto->name . " (Disponible: {$producto->stock}, Solicitado: {$cantidadSolicitada})");
                    }

                    // Calculamos y guardamos
                    $subtotal = $producto->price * $cantidadSolicitada;
                    $totalGeneral += $subtotal;
                    $productosValidos++;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $producto->id,
                        'quantity' => $cantidadSolicitada,
                        'unit_price' => $producto->price,
                    ]);

                    // Descontamos stock
                    $producto->decrement('stock', $cantidadSolicitada);
                }

                // Si no procesó ningún producto válido, fallar
                if ($productosValidos === 0) {
                     throw new \Exception("Su pedido no contiene cantidades válidas.");
                }

                // Guardamos el total final en la orden
                $order->update(['total' => $totalGeneral]);
            });

            return redirect()->route('orders.index')->with('success', 'Pedido guardado y stock descontado.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        // En una app real haríamos la vista detalle.
        return redirect()->route('orders.index');
    }
}
