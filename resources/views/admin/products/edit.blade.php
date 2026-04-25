<x-app-layout>
    <div class="py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="mb-8 flex items-center justify-between">
                <div>
                    <h2 class="text-3xl font-bold text-slate-900">Edit Product</h2>
                    <p class="mt-1 text-sm text-slate-500">Update the details for <span class="font-semibold text-slate-700">{{ $product->name }}</span>.</p>
                </div>
                <a href="{{ route(auth()->user()->role . '.products.index') }}" class="inline-flex items-center gap-2 text-sm font-medium text-slate-600 hover:text-slate-900 transition-colors">
                    <i class="fas fa-arrow-left text-xs"></i>
                    Back to Catalog
                </a>
            </div>

            <div class="rounded-2xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                <form action="{{ route(auth()->user()->role . '.products.update', $product) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="col-span-2">
                            <label for="name" class="block text-sm font-semibold text-slate-700 mb-2">Product Name *</label>
                            <input type="text" name="name" id="name" value="{{ old('name', $product->name) }}" required
                                   class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 shadow-sm"
                                   placeholder="e.g. Premium Wireless Headphones">
                            @error('name') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="barcode" class="block text-sm font-semibold text-slate-700 mb-2">Barcode / SKU</label>
                            <div class="relative">
                                <i class="fas fa-barcode absolute left-4 top-1/2 -translate-y-1/2 text-slate-400"></i>
                                <input type="text" name="barcode" id="barcode" value="{{ old('barcode', $product->barcode) }}"
                                       class="w-full pl-11 pr-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                       placeholder="Scan or type barcode">
                            </div>
                            @error('barcode') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-semibold text-slate-700 mb-2">Category</label>
                            <select name="category_id" id="category_id"
                                    class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 shadow-sm">
                                <option value="">Select Category</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="border-t border-slate-100 pt-6 col-span-2">
                            <h3 class="text-sm font-bold text-slate-900 uppercase tracking-wider mb-4">Pricing & Inventory</h3>
                        </div>

                        <div>
                            <label for="cost_price" class="block text-sm font-semibold text-slate-700 mb-2">Cost Price *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                <input type="number" step="0.01" name="cost_price" id="cost_price" value="{{ old('cost_price', $product->cost_price) }}" required
                                       class="w-full pl-8 pr-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                       placeholder="0.00">
                            </div>
                            @error('cost_price') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label for="selling_price" class="block text-sm font-semibold text-slate-700 mb-2">Selling Price *</label>
                            <div class="relative">
                                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-sm font-medium">$</span>
                                <input type="number" step="0.01" name="selling_price" id="selling_price" value="{{ old('selling_price', $product->selling_price) }}" required
                                       class="w-full pl-8 pr-4 py-3 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm shadow-sm"
                                       placeholder="0.00">
                            </div>
                            @error('selling_price') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>

                        <div class="col-span-2">
                            <label for="stock_quantity" class="block text-sm font-semibold text-slate-700 mb-2">Current Stock Quantity *</label>
                            <input type="number" name="stock_quantity" id="stock_quantity" value="{{ old('stock_quantity', $product->stock_quantity) }}" required
                                   class="w-full rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm py-3 px-4 shadow-sm"
                                   placeholder="0">
                            <p class="mt-2 text-[10px] text-slate-400 italic">Changing this will record a manual stock adjustment in the logs.</p>
                            @error('stock_quantity') <p class="mt-2 text-xs text-rose-600 font-medium">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-4 border-t border-slate-100 pt-8 mt-4">
                        <a href="{{ route(auth()->user()->role . '.products.index') }}" 
                           class="px-6 py-3 text-sm font-semibold text-slate-600 hover:text-slate-900 transition-colors">
                            Cancel
                        </a>
                        <button type="submit" 
                                class="rounded-xl bg-amber-500 px-8 py-3 text-sm font-bold text-white shadow-lg shadow-amber-100 transition hover:bg-amber-600 hover:scale-105 active:scale-95">
                            Update Product
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>