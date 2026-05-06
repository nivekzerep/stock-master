<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderDetail;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('details.product')->orderBy('id', 'desc')->get();
        return view('orders.index', compact('orders'));
    }

    public function create()
    {
        // Solo envía a la vista los productos con disponibilidad para evitar errores de selección
        $products = Product::where('stock', '>', 0)->get();
        return view('orders.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*' => 'exists:products,id',
        ], [
            'products.required' => 'Debe seleccionar al menos un producto marcando su casilla.',
        ]);

        try {
            // Emplea una transacción para garantizar la consistencia entre la orden y el descuento de inventario
            DB::transaction(function () use ($request) {
                // Registro inicial de la orden con un folio único autogenerado
                $order = Order::create([
                    'folio' => 'ORD-' . strtoupper(Str::random(6)), 
                    'order_date' => now(),
                    'total' => 0, 
                ]);

                $totalGeneral = 0;
                $productosValidos = 0;

                // Procesamiento individual y validación de stock por producto
                foreach ($request->products as $productId) {
                    if (!isset($request->quantities[$productId]) || $request->quantities[$productId] <= 0) {
                        throw new \Exception("Debe ingresar una cantidad válida (mayor a 0) para cada producto seleccionado.");
                    }

                    $cantidadSolicitada = $request->quantities[$productId];
                    $producto = Product::findOrFail($productId);

                    if ($cantidadSolicitada > $producto->stock) {
                        throw new \Exception("Stock insuficiente para: " . $producto->name . " (Disponible: {$producto->stock}, Solicitado: {$cantidadSolicitada})");
                    }

                    $subtotal = $producto->price * $cantidadSolicitada;
                    $totalGeneral += $subtotal;
                    $productosValidos++;

                    OrderDetail::create([
                        'order_id' => $order->id,
                        'product_id' => $producto->id,
                        'quantity' => $cantidadSolicitada,
                        'unit_price' => $producto->price,
                    ]);

                    // Actualización del inventario en tiempo real
                    $producto->decrement('stock', $cantidadSolicitada);
                }

                if ($productosValidos === 0) {
                     throw new \Exception("Su pedido no contiene cantidades válidas.");
                }

                // Consolidación del monto final de la orden
                $order->update(['total' => $totalGeneral]);
            });

            return redirect()->route('orders.index')->with('success', 'Pedido guardado y stock descontado.');

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage())->withInput();
        }
    }

    public function show(Order $order)
    {
        return redirect()->route('orders.index');
    }

    public function printOrder(Order $order)
    {
        $html = view('orders.print', compact('order'))->render();
        return Pdf::download('pedido_' . $order->id . '.pdf');
    }

    public function generatePdf(Order $order)
    {
        // Precarga las relaciones para optimizar la consulta antes de enviar al PDF
        $order->load('details.product');
        $pdf = Pdf::loadView('orders.pdf', compact('order'));

        return $pdf->stream('Comprobante_Pedido_' . $order->folio . '.pdf'); 
    }
}
