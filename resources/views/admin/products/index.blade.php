<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header Section -->
            <section class="mb-8 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-indigo-900 px-6 py-8 text-white shadow-xl">
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-6">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-indigo-200">Inventory Management</p>
                        <h2 class="mt-2 text-3xl font-bold">Product Catalog</h2>
                        <p class="mt-2 text-slate-300 max-w-xl">
                            Track your inventory levels, manage product details, and monitor stock health in real-time.
                        </p>
                    </div>
                    <div>
                        <a href="{{ route(auth()->user()->role . '.products.create') }}" 
                           class="inline-flex items-center gap-2 rounded-xl bg-white px-5 py-3 text-sm font-semibold text-slate-900 shadow-sm transition hover:bg-slate-100 hover:scale-105 active:scale-95 no-underline">
                            <i class="fas fa-plus text-indigo-600"></i>
                            <span class="text-slate-900">Add New Product</span>
                        </a>
                    </div>
                </div>
            </section>

            @if(session('success'))
                <div class="mb-6 rounded-xl bg-emerald-50 border border-emerald-200 p-4 flex items-center gap-3 text-emerald-700">
                    <i class="fas fa-check-circle"></i>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-6 rounded-xl bg-rose-50 border border-rose-200 p-4 flex items-center gap-3 text-rose-700">
                    <i class="fas fa-exclamation-circle"></i>
                    <p class="text-sm font-medium">{{ session('error') }}</p>
                </div>
            @endif

            <!-- Stats Bar -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-5 mb-8">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 flex items-center gap-4">
                    <div class="rounded-xl bg-indigo-50 p-3 text-indigo-600">
                        <i class="fas fa-box text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Total Products</p>
                        <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Product::count() }}</p>
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 flex items-center gap-4">
                    <div class="rounded-xl bg-amber-50 p-3 text-amber-600">
                        <i class="fas fa-exclamation-triangle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Low Stock</p>
                        <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count() }}</p>
                    </div>
                </div>
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 flex items-center gap-4">
                    <div class="rounded-xl bg-rose-50 p-3 text-rose-600">
                        <i class="fas fa-times-circle text-xl"></i>
                    </div>
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Out of Stock</p>
                        <p class="text-2xl font-bold text-slate-900">{{ \App\Models\Product::where('stock_quantity', '<=', 0)->count() }}</p>
                    </div>
                </div>
            </div>

            <!-- Main Content Card -->
            <div class="rounded-2xl bg-white shadow-sm ring-1 ring-slate-200 overflow-hidden">
                <!-- Filters & Search -->
                <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                    <form method="GET" action="{{ route(auth()->user()->role . '.products.index') }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div class="relative">
                            <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-slate-400"></i>
                            <input type="text" name="search" value="{{ request('search') }}" 
                                   placeholder="Search products..." 
                                   class="w-full pl-10 pr-4 py-2 rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                        </div>
                        <select name="category_id" class="rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">All Categories</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        <select name="stock_status" class="rounded-xl border-slate-200 focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                            <option value="">Stock Status</option>
                            <option value="in" {{ request('stock_status') == 'in' ? 'selected' : '' }}>In Stock</option>
                            <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Low Stock</option>
                            <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Out of Stock</option>
                        </select>
                        <button type="submit" class="rounded-xl bg-slate-900 px-4 py-2 text-sm font-semibold text-white transition hover:bg-slate-800">
                            Apply Filters
                        </button>
                    </form>
                </div>

                <!-- Table -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-slate-50 text-xs font-semibold uppercase tracking-wider text-slate-500">
                                <th class="px-6 py-4">Product Info</th>
                                <th class="px-6 py-4">Category</th>
                                <th class="px-6 py-4">Pricing</th>
                                <th class="px-6 py-4 text-center">Stock Level</th>
                                <th class="px-6 py-4 text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-100">
                            @forelse($products as $product)
                            <tr class="group hover:bg-slate-50/80 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="h-10 w-10 rounded-lg bg-slate-100 flex items-center justify-center text-slate-400 group-hover:bg-white transition-colors border border-slate-200">
                                            <i class="fas fa-barcode"></i>
                                        </div>
                                        <div>
                                            <p class="font-semibold text-slate-900">{{ $product->name }}</p>
                                            <p class="text-xs text-slate-500 font-mono">{{ $product->barcode ?? 'NO BARCODE' }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-sm text-slate-600">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-slate-100 text-slate-800">
                                        {{ $product->category->name ?? 'Uncategorized' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4">
                                    <p class="text-sm font-semibold text-slate-900">${{ number_format($product->selling_price, 2) }}</p>
                                    <p class="text-[10px] text-slate-400 uppercase tracking-wide">Cost: ${{ number_format($product->cost_price, 2) }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="flex flex-col items-center gap-1">
                                        @php
                                            $stock = $product->stock_quantity;
                                            $badgeClass = 'bg-emerald-100 text-emerald-700';
                                            $barClass = 'bg-emerald-500';
                                            if($stock <= 0) {
                                                $badgeClass = 'bg-rose-100 text-rose-700';
                                                $barClass = 'bg-rose-500';
                                            } elseif($stock <= 10) {
                                                $badgeClass = 'bg-amber-100 text-amber-700';
                                                $barClass = 'bg-amber-500';
                                            }
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold {{ $badgeClass }}">
                                            {{ $stock }} Units
                                        </span>
                                        <div class="w-20 bg-slate-100 rounded-full h-1.5 mt-1 overflow-hidden">
                                            <div class="h-full rounded-full {{ $barClass }}" 
                                                 style="width: {{ min(100, ($stock / 50) * 100) }}%"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="flex justify-end gap-2">
                                        <a href="{{ route(auth()->user()->role . '.products.show', $product) }}" 
                                           class="p-2 rounded-lg text-slate-400 hover:text-indigo-600 hover:bg-indigo-50 transition-colors" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <a href="{{ route(auth()->user()->role . '.products.edit', $product) }}" 
                                           class="p-2 rounded-lg text-slate-400 hover:text-amber-600 hover:bg-amber-50 transition-colors" title="Edit Product">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form action="{{ route(auth()->user()->role . '.products.destroy', $product) }}" method="POST" class="inline" onsubmit="return confirm('Delete this product? This action cannot be undone.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 transition-colors" title="Delete Product">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <div class="h-16 w-16 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 mb-4">
                                            <i class="fas fa-box-open text-2xl"></i>
                                        </div>
                                        <p class="text-slate-500 font-medium">No products found</p>
                                        <p class="text-slate-400 text-sm mt-1">Try adjusting your filters or search terms.</p>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                @if($products->hasPages())
                <div class="p-6 bg-slate-50 border-t border-slate-100">
                    {{ $products->appends(request()->query())->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>