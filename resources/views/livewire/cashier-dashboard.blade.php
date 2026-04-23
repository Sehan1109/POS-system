<div x-data="{ activeTab: $wire.entangle('activeTab'), showFlash: $wire.entangle('showFlash'), timeoutId: null }"
     x-effect="if (showFlash) { clearTimeout(timeoutId); timeoutId = setTimeout(() => $wire.set('showFlash', false), 5000); }">

    {{-- Flash Messages --}}
    @if ($flashMessage)
        <div x-show="showFlash" x-cloak class="fixed top-4 left-1/2 transform -translate-x-1/2 {{ $flashType == 'success' ? 'bg-green-500' : 'bg-red-500' }} text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold transition-opacity duration-300">
            {{ $flashMessage }}
        </div>
    @endif

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            {{-- Tabs --}}
            <div class="flex gap-2 bg-white dark:bg-gray-800 p-2 rounded-xl shadow-sm border border-gray-100 dark:border-gray-700 mb-6 w-fit mx-auto">
                <button wire:click="$set('activeTab', 'overview')" :class="$wire.activeTab === 'overview' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="px-5 py-2.5 rounded-lg text-sm transition-colors">Overview</button>
                <button wire:click="$set('activeTab', 'orders')" :class="$wire.activeTab === 'orders' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="px-5 py-2.5 rounded-lg text-sm transition-colors">Orders</button>
                <button wire:click="$set('activeTab', 'customers')" :class="$wire.activeTab === 'customers' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="px-5 py-2.5 rounded-lg text-sm transition-colors">Customers</button>
                <button wire:click="$set('activeTab', 'settings')" :class="$wire.activeTab === 'settings' ? 'bg-blue-50 text-blue-600 font-bold' : 'text-gray-600 hover:bg-gray-50'" class="px-5 py-2.5 rounded-lg text-sm transition-colors">Settings</button>
            </div>
            
            {{-- Overview Tab --}}
            @if($activeTab === 'overview')
            <div class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Sales</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($total_sales, 2) }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                        <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Active Products</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $total_products }}</p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                        <h3 class="text-sm font-medium text-red-500 mb-1">Low Stock Alerts</h3>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $low_stock }}</p>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-xl p-10 text-center border border-gray-100">
                    <div class="text-7xl mb-6">🛍️</div>
                    <h2 class="text-3xl font-bold mb-3 text-gray-900 dark:text-white">Ready to take an order?</h2>
                    <p class="text-gray-500 dark:text-gray-400 mb-8 max-w-md mx-auto text-lg">Open the POS terminal to quickly process transactions, scan items, and accept payments.</p>
                    <a href="{{ route('pos.index') }}" class="inline-flex justify-center items-center px-8 py-4 border border-transparent text-lg font-bold rounded-xl text-white bg-blue-600 hover:bg-blue-700 shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1">
                        Launch POS Terminal
                    </a>
                </div>
            </div>
            @endif

            {{-- Orders Tab --}}
            @if($activeTab === 'orders')
            <div class="flex flex-col gap-5">
                <div class="flex items-center justify-between gap-4 bg-white p-4 rounded-xl shadow-sm border border-gray-100">
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Recent Orders</h2>
                        <p class="text-sm text-gray-500">View and manage past transactions.</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search orders..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
                        <button wire:click="loadOrders" class="px-4 py-2 rounded-lg bg-gray-100 text-gray-700 font-medium text-sm hover:bg-gray-200 transition-colors">Refresh</button>
                    </div>
                </div>

                <div class="overflow-hidden bg-white border border-gray-200 rounded-xl shadow-sm">
                    <table class="min-w-full text-left text-sm text-gray-700">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500 border-b border-gray-200">
                            <tr>
                                <th class="px-6 py-4 font-semibold">Order #</th>
                                <th class="px-6 py-4 font-semibold">Customer</th>
                                <th class="px-6 py-4 font-semibold">Total</th>
                                <th class="px-6 py-4 font-semibold">Payment</th>
                                <th class="px-6 py-4 font-semibold">Processed By</th>
                                <th class="px-6 py-4 font-semibold">Date</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse($orders as $order)
                                <tr wire:click="viewOrder({{ $order->id }})" class="hover:bg-blue-50 cursor-pointer transition-colors group">
                                    <td class="px-6 py-4 font-semibold text-gray-900 group-hover:text-blue-600">{{ $order->invoice_number ?? 'POS-' . $order->id }}</td>
                                    <td class="px-6 py-4">{{ $order->customer?->name ?? 'Walk-in' }}</td>
                                    <td class="px-6 py-4 font-bold text-gray-900">{{ $currency_symbol }}{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="px-6 py-4 capitalize">{{ $order->payment_method }}</td>
                                    <td class="px-6 py-4">{{ $order->user?->name ?? 'Cashier' }}</td>
                                    <td class="px-6 py-4 text-gray-500">{{ $order->created_at?->format('M d, Y h:i A') }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500">No orders found.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @endif

            {{-- Customers Tab --}}
            @if($activeTab === 'customers')
            <div class="flex flex-col gap-5">
                <div class="grid gap-6 lg:grid-cols-[1fr_400px]">
                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm flex flex-col max-h-[700px]">
                        <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                            <div>
                                <h2 class="text-lg font-bold text-gray-900">Customer Directory</h2>
                                <p class="text-sm text-gray-500">Manage your registered customers.</p>
                            </div>
                            <input type="text" wire:model.live.debounce.300ms="searchQuery" placeholder="Search customers..." class="px-4 py-2 border border-gray-300 rounded-lg text-sm w-64 focus:ring-blue-500 focus:border-blue-500">
                        </div>
                        <div class="flex-1 overflow-y-auto p-2">
                            <div class="divide-y divide-gray-100">
                                @forelse($customers as $customer)
                                    <div class="px-5 py-4 hover:bg-gray-50 transition-colors rounded-lg">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h3 class="font-bold text-gray-900 text-base">{{ $customer->name }}</h3>
                                                <div class="flex gap-4 mt-1 text-sm text-gray-500">
                                                    @if($customer->phone)<span>📞 {{ $customer->phone }}</span>@endif
                                                    @if($customer->email)<span>✉️ {{ $customer->email }}</span>@endif
                                                </div>
                                            </div>
                                            <div class="text-right flex flex-col items-end gap-2">
                                                <div>
                                                    <div class="text-xs font-semibold uppercase tracking-wider text-gray-400 mb-1">Credit Limit</div>
                                                    <div class="text-sm font-bold text-gray-900">{{ $currency_symbol }}{{ number_format($customer->credit_limit, 2) }}</div>
                                                </div>
                                                <button wire:click="confirmDeleteCustomer({{ $customer->id }})" class="p-1.5 text-gray-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete Customer">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                @empty
                                    <div class="px-4 py-12 text-center text-gray-500">No customers found matching your search.</div>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-6 h-fit">
                        <h2 class="text-lg font-bold text-gray-900 mb-2">Register New Customer</h2>
                        <p class="text-sm text-gray-500 mb-6">Add a new customer to your database.</p>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Full Name *</label>
                                <input wire:model.defer="newCustomerName" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                                @error('newCustomerName') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                                <input wire:model.defer="newCustomerPhone" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                                @error('newCustomerPhone') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input wire:model.defer="newCustomerEmail" type="email" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                                @error('newCustomerEmail') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Credit Limit</label>
                                <input wire:model.defer="newCustomerCreditLimit" type="number" step="0.01" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                                @error('newCustomerCreditLimit') <span class="text-red-500 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>
                            <button wire:click="addCustomer" class="w-full mt-2 rounded-lg bg-blue-600 px-4 py-3 text-white font-bold hover:bg-blue-700 transition-colors shadow-sm">
                                Register Customer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- Settings Tab --}}
            @if($activeTab === 'settings')
            <div class="max-w-3xl">
                <div class="bg-white border border-gray-200 rounded-xl shadow-sm p-8">
                    <div class="mb-6 pb-6 border-b border-gray-100">
                        <h2 class="text-xl font-bold text-gray-900">POS Settings</h2>
                        <p class="text-sm text-gray-500 mt-1">Configure your receipt details, shop information, and tax rates.</p>
                    </div>
                    <div class="grid gap-6 sm:grid-cols-2">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Shop Name *</label>
                            <input wire:model.defer="shop_name" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                            @error('shop_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Currency Symbol *</label>
                            <input wire:model.defer="currency_symbol" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                            @error('currency_symbol') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Shop Address</label>
                            <input wire:model.defer="shop_address" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Shop Phone</label>
                            <input wire:model.defer="shop_phone" type="text" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Tax Rate (%) *</label>
                            <input wire:model.defer="taxRate" type="number" step="0.01" min="0" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" />
                            @error('taxRate') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-1">Receipt Footer Message</label>
                            <textarea wire:model.defer="receipt_footer" rows="3" class="block w-full rounded-lg border-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm"></textarea>
                        </div>
                    </div>
                    <div class="mt-8 pt-6 border-t border-gray-100 flex justify-end">
                        <button wire:click="saveSettings" class="rounded-lg bg-blue-600 px-8 py-3 text-white font-bold hover:bg-blue-700 transition-colors shadow-sm">
                            Save Changes
                        </button>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- Order Details Modal --}}
    @if($viewingOrder)
        <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6" x-data="{}" @keydown.escape.window="$wire.closeOrderModal()">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm cursor-pointer" wire:click="closeOrderModal"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-4xl max-h-[90vh] flex flex-col overflow-hidden">
                <div class="flex items-start justify-between px-8 py-6 border-b border-slate-100">
                    <div>
                        <p class="text-[11px] text-blue-500 font-bold uppercase tracking-widest mb-2">Order Details</p>
                        <h1 class="text-3xl font-bold text-gray-900">{{ $viewingOrder->invoice_number ?? '#' . str_pad($viewingOrder->id, 6, '0', STR_PAD_LEFT) }}</h1>
                    </div>
                    <button wire:click="closeOrderModal" class="p-2 hover:bg-slate-100 rounded-xl transition-colors text-slate-400 hover:text-slate-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>
                <div class="flex-1 overflow-y-auto p-8">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-8">
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Customer</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $viewingOrder->customer?->name ?? 'Walk-in Customer' }}</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Payment</p>
                            <p class="text-sm font-semibold text-gray-900 capitalize">{{ $viewingOrder->payment_method }}</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Date</p>
                            <p class="text-sm font-semibold text-gray-900">{{ $viewingOrder->created_at->format('M d, Y h:i A') }}</p>
                        </div>
                        <div class="bg-slate-50 border border-slate-100 rounded-xl p-4">
                            <p class="text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2">Total Amount</p>
                            <p class="text-sm font-semibold text-blue-600">{{ $currency_symbol }}{{ number_format($viewingOrder->total_amount, 2) }}</p>
                        </div>
                    </div>
                    <div class="bg-white border border-slate-100 rounded-xl p-5">
                        <div class="flex items-center justify-between mb-4 pb-4 border-b border-slate-100">
                            <h3 class="text-sm font-bold text-gray-900">Line Items</h3>
                        </div>
                        <table class="w-full text-left text-sm">
                            <thead>
                                <tr class="border-b border-slate-100">
                                    <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest">Product</th>
                                    <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-center">Qty</th>
                                    <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Unit Price</th>
                                    <th class="py-3 text-[10px] font-bold text-slate-400 uppercase tracking-widest text-right">Total</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-100">
                                @forelse($viewingOrder->items as $item)
                                    <tr class="hover:bg-slate-50/50 transition-colors">
                                        <td class="py-3 font-medium text-gray-900">{{ $item->product?->name ?? 'Unknown Item' }}</td>
                                        <td class="py-3 text-center text-gray-600">{{ $item->quantity }}</td>
                                        <td class="py-3 text-right text-gray-600">{{ $currency_symbol }}{{ number_format($item->unit_price, 2) }}</td>
                                        <td class="py-3 text-right font-semibold text-gray-900">{{ $currency_symbol }}{{ number_format($item->sub_total ?? ($item->quantity * $item->unit_price), 2) }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="py-6 text-center text-gray-500 text-sm">No items found.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Delete Customer Modal --}}
    @if($customerToDeleteId)
        <div class="fixed inset-0 z-[150] flex items-center justify-center p-4 sm:p-6" x-data="{}" @keydown.escape.window="$wire.cancelDeleteCustomer()">
            <div class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm cursor-pointer" wire:click="cancelDeleteCustomer"></div>
            <div class="relative bg-white rounded-2xl shadow-2xl border border-slate-200 w-full max-w-lg flex flex-col overflow-hidden">
                <div class="p-6 border-b border-slate-100 flex items-center gap-3 bg-red-50/50">
                    <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="w-6 h-6"><path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-bold text-gray-900">Delete Customer</h3>
                        <p class="text-sm text-gray-600 mt-1">This action will soft-delete the customer.</p>
                    </div>
                </div>
                <div class="p-6">
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Reason for deletion <span class="text-red-500">*</span></label>
                    <textarea wire:model.defer="deleteReason" rows="3" placeholder="Please provide a reason for deleting this customer (min 5 chars)..." class="block w-full rounded-lg border-gray-300 focus:border-red-500 focus:ring-red-500 shadow-sm"></textarea>
                    @error('deleteReason') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                <div class="p-6 pt-0 flex gap-3 justify-end bg-gray-50/50 rounded-b-2xl border-t border-slate-100 mt-2">
                    <button wire:click="cancelDeleteCustomer" class="px-5 py-2.5 bg-white border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors shadow-sm">
                        Cancel
                    </button>
                    <button wire:click="deleteCustomer" class="px-5 py-2.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                        Confirm Deletion
                    </button>
                </div>
            </div>
        </div>
    @endif
</div>
