<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Inicio de la consulta base (Query Builder) e incluimos a su categoría
        $query = Product::with('category');

        // 2. Filtro: Búsqueda por Nombre o SKU
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('sku', 'like', "%{$search}%");
            });
        }

        // 3. Filtro: Por Categoría Select
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Ordenamiento (Precio o Stock, ASC o DESC)
        if ($request->filled('sort_by') && $request->filled('direction')) {
            // Solo permitimos columnas seguras para evitar inyección SQL
            $allowedSorts = ['price', 'stock'];
            if (in_array($request->sort_by, $allowedSorts)) {
                $direction = $request->direction === 'desc' ? 'desc' : 'asc';
                $query->orderBy($request->sort_by, $direction);
            }
        } else {
            $query->orderBy('id', 'desc'); // Orden por defecto
        }

        // 5. Ejecutamos la consulta y la mandamos a la vista
        $products = $query->get();
        $categories = Category::all(); // Necesarias para el dropdown de filtros

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        // Validación estricta solicitada en la prueba:
        // - SKU único (unique:products,sku)
        // - Stock no negativo (min:0)
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        Product::create($request->all());

        return redirect()->route('products.index')->with('success', 'Producto creado con éxito.');
    }

    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        // Misma validación, pero el SKU ignora el ID actual (para que deje guardar sin chocar consigo mismo)
        $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'sku' => 'required|string|unique:products,sku,' . $product->id,
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
        ]);

        $product->update($request->all());

        return redirect()->route('products.index')->with('success', 'Producto actualizado.');
    }

    public function destroy(Product $product)
    {
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Producto eliminado.');
    }
}
