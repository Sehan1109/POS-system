@extends('layouts.app')

@section('content')
<div class="sm:flex sm:items-center sm:justify-between mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-900">Products</h1>
        <p class="mt-1 text-sm text-gray-600">A list of all products in your inventory.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <a href="{{ route('products.create') }}" class="inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 shadow-sm">
            + Add Product
        </a>
    </div>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border-l-4 border-green-400 p-4">
        <div class="flex">
            <div class="ml-3">
                <p class="text-sm text-green-700">{{ session('success') }}</p>
            </div>
        </div>
    </div>
@endif

<div class="bg-white shadow-sm border border-gray-200 rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Name / Barcode</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Price</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Stock</th>
                <th scope="col" class="relative px-6 py-3"><span class="sr-only">Edit</span></th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach ($products as $product)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-gray-900">{{ $product->name }}</div>
                    <div class="text-sm text-gray-500">{{ $product->barcode }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                        {{ $product->category->name }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${{ number_format($product->selling_price, 2) }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="text-sm {{ $product->stock_quantity <= 5 ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                        {{ $product->stock_quantity }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <a href="{{ route('products.edit', $product) }}" class="text-blue-600 hover:text-blue-900 mr-3">Edit</a>
                    <!-- Delete form would go here -->
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
    @if($products->hasPages())
        <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6">
            {{ $products->links() }}
        </div>
    @endif
</div>
@endsection