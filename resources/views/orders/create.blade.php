<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Simular Creación de Pedido') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900 dark:text-gray-100">
                    
                    <p class="mb-4 text-gray-600 dark:text-gray-400">
                        Selecciona la casilla del producto que deseas agregar al pedido y especifica la cantidad. Al guardar, el stock se descontará automáticamente.
                    </p>

                    <form action="{{ route('orders.store') }}" method="POST">
                        @csrf

                        <div class="overflow-x-auto mb-6">
                            <table class="min-w-full leading-normal border border-gray-200 dark:border-gray-700">
                                <thead>
                                    <tr>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">Llevar</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">Producto (SKU)</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">Precio</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">Stock Disponible</th>
                                        <th class="px-5 py-3 border-b-2 border-gray-200 bg-gray-100 dark:bg-gray-700 text-left text-xs font-semibold text-gray-600 dark:text-gray-200 uppercase tracking-wider">Cantidad a comprar</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                    <tr>
                                        <td class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                            <input type="checkbox" name="products[]" value="{{ $product->id }}" class="rounded text-indigo-600 border-gray-300 dark:border-gray-700 focus:ring-indigo-500">
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm">
                                            {{ $product->name }} <span class="text-xs text-gray-500">({{ $product->sku }})</span>
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm font-bold">
                                            ${{ number_format($product->price, 2) }}
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800 text-sm text-green-600 font-bold">
                                            {{ $product->stock }}
                                        </td>
                                        <td class="px-5 py-3 border-b border-gray-200 dark:border-gray-700 bg-white dark:bg-gray-800">
                                            <input type="number" name="quantities[{{ $product->id }}]" min="1" max="{{ $product->stock }}" class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-24 text-sm" placeholder="Cant.">
                                        </td>
                                    </tr>
                                    @endforeach
                                    
                                    @if($products->isEmpty())
                                    <tr>
                                        <td colspan="5" class="px-5 py-5 border-b border-gray-200 text-center text-red-500 font-bold">
                                            No hay productos con stock disponible para crear un pedido.
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>

                        <div class="flex items-center justify-end mt-4">
                            <a href="{{ route('orders.index') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 mr-4">
                                Cancelar
                            </a>
                            <x-primary-button>
                                {{ __('Procesar Pedido') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>