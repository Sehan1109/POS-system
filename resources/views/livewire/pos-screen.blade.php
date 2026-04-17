<div class="flex flex-col w-full h-screen bg-gray-100 text-gray-900 font-sans overflow-hidden">

    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div
            class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div
            class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <header class="h-[60px] bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
        <div class="font-extrabold text-[20px] text-blue-600 tracking-tight">NEXGEN POS</div>

        <div class="flex-1 max-w-md mx-8">
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                placeholder="Search products or scan barcode..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>

        <div class="flex items-center gap-4 text-sm">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold text-xs">Register Open</span>
            <span class="text-gray-500">{{ now()->format('h:i A') }}</span>
            <strong class="font-bold">{{ auth()->user()->name ?? 'Alex Cashier' }}</strong>
        </div>
    </header>

    {{-- Main Content --}}
    <main x-data="{ isSidebarOpen: false, activeTab: 'catalog' }" class="flex flex-1 overflow-hidden">

        {{-- Sidebar --}}
        {{-- isSidebarOpen true නම් w-48 (පළල වැඩියි), false නම් w-20 (පළල අඩුයි) --}}
        <nav :class="isSidebarOpen ? 'w-52' : 'w-20'"
            class="bg-white border-r border-gray-200 flex flex-col pt-4 gap-2 shrink-0 transition-all duration-300 px-3">

            {{-- Toggle Button (Manual Close/Open) --}}
            <div class="flex items-center mb-4" :class="isSidebarOpen ? 'justify-end' : 'justify-center'">
                <button @click="isSidebarOpen = !isSidebarOpen"
                    class="p-2 text-gray-500 hover:bg-gray-100 rounded-lg transition-colors focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                </button>
            </div>

            {{-- Catalog --}}
            <div @click="activeTab = 'catalog'; isSidebarOpen = true"
                :class="activeTab === 'catalog' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                class="h-14 w-full rounded-xl flex items-center px-4 cursor-pointer transition-colors duration-300 overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                </svg>
                {{-- whitespace-nowrap මගින් text එක කඩාවැටීම වළක්වයි --}}
                <span x-show="isSidebarOpen" x-transition.opacity.duration.300ms
                    class="ml-4 text-sm font-bold whitespace-nowrap" style="display: none;">Catalog</span>
            </div>

            {{-- Orders --}}
            <div @click="activeTab = 'orders'; isSidebarOpen = true"
                :class="activeTab === 'orders' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                class="h-14 w-full rounded-xl flex items-center px-4 cursor-pointer transition-colors duration-300 overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" />
                </svg>
                <span x-show="isSidebarOpen" x-transition.opacity.duration.300ms
                    class="ml-4 text-sm font-bold whitespace-nowrap" style="display: none;">Orders</span>
            </div>

            {{-- Customers --}}
            <div @click="activeTab = 'customers'; isSidebarOpen = true"
                :class="activeTab === 'customers' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                class="h-14 w-full rounded-xl flex items-center px-4 cursor-pointer transition-colors duration-300 overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                </svg>
                <span x-show="isSidebarOpen" x-transition.opacity.duration.300ms
                    class="ml-4 text-sm font-bold whitespace-nowrap" style="display: none;">Customers</span>
            </div>

            {{-- Settings --}}
            <div @click="activeTab = 'settings'; isSidebarOpen = true"
                :class="activeTab === 'settings' ? 'bg-blue-50 text-blue-600' : 'text-gray-500 hover:bg-gray-50'"
                class="h-14 w-full rounded-xl flex items-center px-4 cursor-pointer transition-colors duration-300 overflow-hidden">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-6 h-6 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span x-show="isSidebarOpen" x-transition.opacity.duration.300ms
                    class="ml-4 text-sm font-bold whitespace-nowrap" style="display: none;">Settings</span>
            </div>
        </nav>

        {{-- Catalog Section --}}
        <section class="flex-1 p-6 flex flex-col gap-5 overflow-hidden">
            <div x-show="activeTab === 'catalog'" class="flex flex-col gap-5 overflow-hidden">
                {{-- Categories --}}
                <div class="flex gap-3 overflow-x-auto pb-2 shrink-0">
                    <div wire:click="setCategory(null)"
                        class="px-5 py-2 border rounded-lg text-sm font-medium cursor-pointer transition-colors whitespace-nowrap {{ is_null($activeCategoryId) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-200 text-gray-900 hover:border-blue-600' }}">
                        All Categories
                    </div>
                    @foreach($categories as $cat)
                        <div wire:click="setCategory({{ $cat->id }})"
                            class="px-5 py-2 border rounded-lg text-sm font-medium cursor-pointer transition-colors whitespace-nowrap {{ $activeCategoryId == $cat->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-200 text-gray-900 hover:border-blue-600' }}">
                            {{ $cat->name }}
                        </div>
                    @endforeach
                </div>

                {{-- Product Grid --}}
                <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pb-6 pr-2">
                    @forelse($products as $prod)
                        <div wire:click="addToCart({{ $prod->id }})"
                            class="bg-white rounded-xl border border-gray-200 p-4 flex flex-col gap-2 cursor-pointer hover:border-blue-600 hover:shadow-md transition-all {{ $prod->stock_quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}">
                            <div class="w-full h-[100px] bg-gray-50 rounded-lg flex items-center justify-center text-4xl">
                                📦 {{-- You can replace this with an actual image tag if you add image paths to DB --}}
                            </div>
                            <div class="font-semibold text-sm text-gray-900 mt-1 leading-tight">{{ $prod->name }}</div>
                            <div class="text-blue-600 font-bold text-base">${{ number_format($prod->selling_price, 2) }}
                            </div>
                            <div
                                class="text-xs {{ $prod->stock_quantity <= 5 ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                                Stock: {{ $prod->stock_quantity }} units
                            </div>
                        </div>
                    @empty
                        <div class="col-span-full text-center text-gray-500 py-10">
                            No products found.
                        </div>
                    @endforelse
                </div>
            </div>

            <div x-show="activeTab === 'orders'" class="flex flex-col gap-5 overflow-hidden">
                <div class="flex items-center justify-between gap-4">
                    <div>
                        <h2 class="text-lg font-bold">Recent Orders</h2>
                        <p class="text-sm text-gray-500">Last 12 transactions processed through the POS.</p>
                    </div>
                    <button @click="$wire.loadOrders()"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm hover:bg-blue-700 transition-colors">
                        Refresh
                    </button>
                </div>

                <div class="overflow-auto bg-white border border-gray-200 rounded-xl">
                    <table class="min-w-full text-left text-sm text-gray-700">
                        <thead class="bg-gray-50 text-xs uppercase tracking-wider text-gray-500">
                            <tr>
                                <th class="px-4 py-3">Order #</th>
                                <th class="px-4 py-3">Customer</th>
                                <th class="px-4 py-3">Total</th>
                                <th class="px-4 py-3">Payment</th>
                                <th class="px-4 py-3">Processed By</th>
                                <th class="px-4 py-3">Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($orders as $order)
                                <tr class="border-t border-gray-100 hover:bg-gray-50">
                                    <td class="px-4 py-3 font-semibold">{{ $order->invoice_number ?? 'POS-' . $order->id }}
                                    </td>
                                    <td class="px-4 py-3">{{ $order->customer?->name ?? 'Walk-in' }}</td>
                                    <td class="px-4 py-3 font-bold text-gray-900">
                                        {{ $currency_symbol }}{{ number_format($order->total_amount, 2) }}
                                    </td>
                                    <td class="px-4 py-3">{{ ucfirst($order->payment_method) }}</td>
                                    <td class="px-4 py-3">{{ $order->user?->name ?? 'Cashier' }}</td>
                                    <td class="px-4 py-3 text-gray-500">{{ $order->created_at?->format('M d, Y h:i A') }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-4 py-8 text-center text-gray-500">No orders have been
                                        processed yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

            <div x-show="activeTab === 'customers'" class="flex flex-col gap-5 overflow-hidden">
                <div class="grid gap-5 lg:grid-cols-[1fr_360px]">
                    <div class="bg-white border border-gray-200 rounded-xl p-5 overflow-auto max-h-[620px]">
                        <div class="flex items-center justify-between mb-4">
                            <div>
                                <h2 class="text-lg font-bold">Customers</h2>
                                <p class="text-sm text-gray-500">Select a customer for the current sale or add a new
                                    one.</p>
                            </div>
                            @if($selectedCustomerId)
                                <button wire:click="clearCustomer"
                                    class="text-sm text-red-600 hover:text-red-800">Clear</button>
                            @endif
                        </div>

                        <div class="divide-y divide-gray-200">
                            @foreach($customers as $customer)
                                <div wire:click="selectCustomer({{ $customer->id }})"
                                    class="cursor-pointer px-4 py-3 transition-colors duration-200 rounded-xl {{ $selectedCustomerId === $customer->id ? 'bg-blue-50 border border-blue-200' : 'hover:bg-gray-50' }}">
                                    <div class="flex items-center justify-between gap-2">
                                        <h3 class="font-semibold text-sm text-gray-900">{{ $customer->name }}</h3>
                                        @if($selectedCustomerId === $customer->id)
                                            <span
                                                class="text-xs bg-blue-100 text-blue-700 px-2 py-1 rounded-full">Selected</span>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-500">{{ $customer->phone ?? 'No phone' }}</p>
                                    <p class="text-xs text-gray-500">{{ $customer->email ?? 'No email' }}</p>
                                    <p class="text-xs text-gray-600 mt-2">Credit:
                                        {{ $currency_symbol }}{{ number_format($customer->credit_used, 2) }} /
                                        {{ $currency_symbol }}{{ number_format($customer->credit_limit, 2) }}
                                    </p>
                                </div>
                            @endforeach
                            @if($customers->isEmpty())
                                <div class="px-4 py-8 text-center text-gray-500">No customers found.</div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-white border border-gray-200 rounded-xl p-5 shadow-sm">
                        <h2 class="text-lg font-bold mb-3">Add New Customer</h2>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Name</label>
                                <input wire:model.defer="newCustomerName" type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Phone</label>
                                <input wire:model.defer="newCustomerPhone" type="text"
                                    class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Email</label>
                                <input wire:model.defer="newCustomerEmail" type="email"
                                    class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Credit Limit</label>
                                <input wire:model.defer="newCustomerCreditLimit" type="number" step="0.01"
                                    class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                            </div>
                            <button wire:click="addCustomer"
                                class="w-full rounded-lg bg-blue-600 px-4 py-3 text-white font-semibold hover:bg-blue-700 transition-colors">Add
                                Customer</button>
                        </div>
                    </div>
                </div>
            </div>

            <div x-show="activeTab === 'settings'" class="flex flex-col gap-5 overflow-hidden">
                <div class="bg-white border border-gray-200 rounded-xl p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h2 class="text-lg font-bold">POS Settings</h2>
                            <p class="text-sm text-gray-500">Update shop details, currency, and receipt settings.</p>
                        </div>
                    </div>
                    <div class="grid gap-4 lg:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shop Name</label>
                            <input wire:model.defer="shop_name" type="text"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Currency Symbol</label>
                            <input wire:model.defer="currency_symbol" type="text"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Shop Address</label>
                            <input wire:model.defer="shop_address" type="text"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Shop Phone</label>
                            <input wire:model.defer="shop_phone" type="text"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tax Rate (%)</label>
                            <div class="mt-1 flex items-center gap-2">
                                <input wire:model.defer="taxRate" type="number" step="0.01" min="0"
                                    class="block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500" />
                                <span class="text-gray-500">%</span>
                            </div>
                        </div>
                        <div class="lg:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Receipt Footer</label>
                            <textarea wire:model.defer="receipt_footer" rows="4"
                                class="mt-1 block w-full rounded-lg border-gray-200 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                    <button wire:click="saveSettings"
                        class="mt-6 rounded-lg bg-blue-600 px-5 py-3 text-white font-semibold hover:bg-blue-700 transition-colors">Save
                        Settings</button>
                </div>
            </div>
        </section>

        {{-- Cart Sidebar --}}
        <aside class="w-[340px] bg-white border-l border-gray-200 flex flex-col shrink-0">
            <div class="p-5 border-b border-gray-200 font-bold flex justify-between items-center">
                <span>Current Order</span>
                <span class="text-blue-600">#{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="px-5 py-4 border-b border-gray-200 bg-gray-50">
                <div class="text-sm text-gray-500">Customer</div>
                @if($selectedCustomerId)
                    <div class="mt-2 font-semibold text-gray-900">
                        {{ $customers->firstWhere('id', $selectedCustomerId)?->name ?? 'Selected Customer' }}
                    </div>
                    <div class="text-xs text-gray-500">{{ $customers->firstWhere('id', $selectedCustomerId)?->phone ?? '' }}
                    </div>
                @else
                    <div class="mt-2 font-semibold text-gray-900">Walk-in Customer</div>
                @endif
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-2">
                @forelse($cart as $index => $item)
                    <div
                        class="flex justify-between py-3 border-b border-dashed border-gray-200 last:border-0 items-center">
                        <div class="flex flex-col">
                            <h4 class="font-semibold text-sm text-gray-900 mb-1">{{ $item['name'] }}</h4>
                            <span class="text-xs text-gray-500">x{{ $item['quantity'] }} Units
                                (${{ number_format($item['unit_price'], 2) }} ea)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="font-semibold text-gray-900">${{ number_format($item['sub_total'], 2) }}</div>
                            <button wire:click="removeFromCart({{ $index }})"
                                class="text-red-500 hover:text-red-700 font-bold">×</button>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-400 text-sm py-10">
                        Cart is empty
                    </div>
                @endforelse
            </div>

            <div class="p-6 bg-gray-50 border-t border-gray-200 flex flex-col gap-3 shrink-0">
                <div class="flex justify-between text-sm text-gray-900">
                    <span>Subtotal</span>
                    <span>${{ number_format($subtotal, 2) }}</span>
                </div>
                <div class="flex justify-between text-sm text-gray-900">
                    <span>VAT ({{ number_format($taxRate * 100, 2) }}%)</span>
                    <span>{{ $currency_symbol }}{{ number_format($taxAmount, 2) }}</span>
                </div>
                <div
                    class="flex justify-between font-extrabold text-xl my-2 pt-2 border-t border-gray-200 text-gray-900">
                    <span>TOTAL</span>
                    <span>{{ $currency_symbol }}{{ number_format($total, 2) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-1">
                    <button wire:click="setPaymentMethod('cash')"
                        class="p-2 border rounded-lg text-sm font-semibold cursor-pointer transition-colors flex items-center justify-center gap-2 {{ $paymentMethod === 'cash' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white hover:border-blue-600' }}">
                        💵 Cash
                    </button>
                    <button wire:click="setPaymentMethod('card')"
                        class="p-2 border rounded-lg text-sm font-semibold cursor-pointer transition-colors flex items-center justify-center gap-2 {{ $paymentMethod === 'card' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white hover:border-blue-600' }}">
                        💳 Card
                    </button>
                </div>
                <button wire:click="checkout" wire:loading.attr="disabled"
                    class="w-full bg-blue-600 text-white border-none p-4 rounded-xl font-bold text-base cursor-pointer text-center hover:bg-blue-700 transition-colors mt-2 disabled:opacity-50">
                    <span wire:loading.remove wire:target="checkout">PAY NOW</span>
                    <span wire:loading wire:target="checkout">Processing...</span>
                </button>
            </div>
        </aside>
    </main>
</div>