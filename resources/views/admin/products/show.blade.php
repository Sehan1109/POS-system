<x-app-layout>
    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-8 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-widest text-indigo-600 mb-2">
                        <i class="fas fa-tag"></i>
                        Product Details
                    </div>
                    <h2 class="text-4xl font-black text-slate-900 tracking-tight">{{ $product->name }}</h2>
                    <p class="text-slate-500 mt-1">Barcode: <span class="font-mono font-medium">{{ $product->barcode ?? 'N/A' }}</span></p>
                </div>
                <div class="flex items-center gap-3">
                    <a href="{{ route(auth()->user()->role . '.products.index') }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-5 py-2.5 text-sm font-semibold text-slate-600 shadow-sm transition hover:bg-slate-50">
                        <i class="fas fa-arrow-left"></i>
                        Back
                    </a>
                    <a href="{{ route(auth()->user()->role . '.products.edit', $product) }}" 
                       class="inline-flex items-center gap-2 rounded-xl bg-amber-500 px-5 py-2.5 text-sm font-bold text-white shadow-lg shadow-amber-100 transition hover:bg-amber-600 hover:scale-105 active:scale-95">
                        <i class="fas fa-edit"></i>
                        Edit Product
                    </a>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                <!-- Main Info Card -->
                <div class="lg:col-span-2 space-y-8">
                    <!-- Overview Section -->
                    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <span class="h-8 w-1 bg-indigo-600 rounded-full"></span>
                            Inventory Overview
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div class="rounded-2xl bg-slate-50 p-6 border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Current Stock</p>
                                <p class="text-3xl font-black text-slate-900">{{ $product->stock_quantity }} <span class="text-sm font-medium text-slate-500 uppercase">Units</span></p>
                                <div class="mt-4 w-full bg-slate-200 rounded-full h-2 overflow-hidden">
                                    <div class="h-full bg-indigo-600 rounded-full" style="width: {{ min(100, ($product->stock_quantity / 50) * 100) }}%"></div>
                                </div>
                            </div>
                            <div class="rounded-2xl bg-slate-50 p-6 border border-slate-100">
                                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mb-1">Cost Price</p>
                                <p class="text-3xl font-black text-slate-900">${{ number_format($product->cost_price, 2) }}</p>
                                <p class="mt-2 text-xs text-slate-400">Average acquisition cost</p>
                            </div>
                            <div class="rounded-2xl bg-indigo-600 p-6 shadow-lg shadow-indigo-100">
                                <p class="text-xs font-bold text-indigo-200 uppercase tracking-widest mb-1">Selling Price</p>
                                <p class="text-3xl font-black text-white">${{ number_format($product->selling_price, 2) }}</p>
                                <p class="mt-2 text-xs text-indigo-200">Current market price</p>
                            </div>
                        </div>

                        <div class="mt-8 grid grid-cols-2 md:grid-cols-4 gap-4">
                            <div class="p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Category</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $product->category->name ?? 'None' }}</p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Status</p>
                                <span class="inline-flex mt-1 items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700 uppercase">Active</span>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Profit Margin</p>
                                @php
                                    $margin = $product->selling_price > 0 ? (($product->selling_price - $product->cost_price) / $product->selling_price) * 100 : 0;
                                @endphp
                                <p class="text-sm font-bold text-emerald-600">{{ number_format($margin, 1) }}%</p>
                            </div>
                            <div class="p-4 rounded-2xl border border-slate-100">
                                <p class="text-[10px] font-bold text-slate-400 uppercase">Last Updated</p>
                                <p class="text-sm font-semibold text-slate-700">{{ $product->updated_at->format('M d, Y') }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Recent Sales Table -->
                    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-bold text-slate-900 flex items-center gap-2">
                                <span class="h-8 w-1 bg-emerald-500 rounded-full"></span>
                                Recent Performance
                            </h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full text-left text-sm">
                                <thead>
                                    <tr class="text-slate-400 font-bold uppercase tracking-widest text-[10px]">
                                        <th class="pb-4">Invoice</th>
                                        <th class="pb-4">Date</th>
                                        <th class="pb-4">Customer</th>
                                        <th class="pb-4 text-center">Qty</th>
                                        <th class="pb-4 text-right">Total</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-50">
                                    @forelse($recentSales as $item)
                                        <tr class="hover:bg-slate-50/50 transition-colors">
                                            <td class="py-4 font-bold text-slate-900">{{ $item->sale->invoice_number }}</td>
                                            <td class="py-4 text-slate-500">{{ $item->sale->created_at->format('M d, H:i') }}</td>
                                            <td class="py-4 text-slate-700">{{ $item->sale->customer->name ?? 'Walk-in' }}</td>
                                            <td class="py-4 text-center font-semibold text-slate-900">{{ $item->quantity }}</td>
                                            <td class="py-4 text-right font-bold text-slate-900">${{ number_format($item->total_price, 2) }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="py-8 text-center text-slate-400 italic">No sales recorded for this product yet.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Sidebar Section -->
                <div class="space-y-8">
                    <!-- Stock Adjustment Form -->
                    <div class="rounded-3xl bg-slate-900 p-8 text-white shadow-xl">
                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2">
                            <i class="fas fa-tools text-indigo-400"></i>
                            Quick Adjust
                        </h3>
                        <form action="{{ route(auth()->user()->role . '.products.adjust-stock', $product) }}" method="POST" class="space-y-4">
                            @csrf
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Adjustment Type</label>
                                <div class="grid grid-cols-3 gap-2">
                                    <label class="cursor-pointer">
                                        <input type="radio" name="adjustment_type" value="add" checked class="peer sr-only">
                                        <div class="text-center py-2 rounded-xl border border-slate-700 text-xs font-bold transition peer-checked:bg-indigo-600 peer-checked:border-indigo-600">Add</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="adjustment_type" value="subtract" class="peer sr-only">
                                        <div class="text-center py-2 rounded-xl border border-slate-700 text-xs font-bold transition peer-checked:bg-rose-600 peer-checked:border-rose-600">Sub</div>
                                    </label>
                                    <label class="cursor-pointer">
                                        <input type="radio" name="adjustment_type" value="set" class="peer sr-only">
                                        <div class="text-center py-2 rounded-xl border border-slate-700 text-xs font-bold transition peer-checked:bg-amber-600 peer-checked:border-amber-600">Set</div>
                                    </label>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Quantity</label>
                                <input type="number" name="quantity" required min="1" class="w-full bg-slate-800 border-slate-700 rounded-xl text-white text-sm py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="Enter amount">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-slate-400 uppercase tracking-widest mb-2">Reason (Optional)</label>
                                <textarea name="reason" rows="2" class="w-full bg-slate-800 border-slate-700 rounded-xl text-white text-sm py-3 px-4 focus:ring-indigo-500 focus:border-indigo-500" placeholder="e.g. Restock, Damaged..."></textarea>
                            </div>
                            <button type="submit" class="w-full py-4 rounded-2xl bg-white text-slate-900 text-sm font-black uppercase tracking-widest transition hover:bg-slate-100 hover:scale-[1.02] active:scale-95 shadow-lg shadow-white/5">
                                Update Inventory
                            </button>
                        </form>
                    </div>

                    <!-- Stock Movements / Audit Log -->
                    <div class="rounded-3xl bg-white p-8 shadow-sm ring-1 ring-slate-200">
                        <h3 class="text-lg font-bold text-slate-900 mb-6 flex items-center gap-2">
                            <i class="fas fa-history text-slate-400"></i>
                            Audit Trail
                        </h3>
                        <div class="space-y-6 relative before:absolute before:left-[11px] before:top-2 before:bottom-2 before:w-0.5 before:bg-slate-100">
                            @forelse($stockMovements as $log)
                                <div class="relative pl-8">
                                    <div class="absolute left-0 top-1 h-6 w-6 rounded-full border-4 border-white shadow-sm flex items-center justify-center {{ $log->action === 'stock_increase' ? 'bg-emerald-500' : 'bg-rose-500' }}">
                                        <i class="fas {{ $log->action === 'stock_increase' ? 'fa-plus' : 'fa-minus' }} text-[8px] text-white"></i>
                                    </div>
                                    <p class="text-xs font-bold text-slate-900">{{ $log->action === 'stock_increase' ? 'Stock Added' : 'Stock Removed' }}</p>
                                    <p class="text-[10px] text-slate-500 mt-0.5">{{ $log->description }}</p>
                                    <p class="text-[9px] text-slate-400 mt-1 uppercase font-semibold">{{ $log->created_at->diffForHumans() }}</p>
                                </div>
                            @empty
                                <p class="text-sm text-slate-400 italic pl-2">No recent stock movements.</p>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>