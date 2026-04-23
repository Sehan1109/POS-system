<div class="flex flex-col w-full h-screen bg-gray-100 text-gray-900 font-sans overflow-hidden"
    x-data="{ isSidebarOpen: false, activeTab: @entangle('activeTab'), userMenuOpen: false, showFlash: @entangle('showFlash'), timeoutId: null }"
    x-effect="if (showFlash) { clearTimeout(timeoutId); timeoutId = setTimeout(() => $wire.set('showFlash', false), 5000); }">

    {{-- Flash Messages --}}
    @if ($flashMessage)
        <div x-show="showFlash"
            class="flash-message absolute top-4 left-1/2 transform -translate-x-1/2 {{ $flashType == 'success' ? 'bg-green-500' : 'bg-red-500' }} text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold">
            {{ $flashMessage }}
        </div>
    @endif

    {{-- Header --}}
    <header class="h-[60px] bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
        <div class="flex items-center gap-6">
            <div class="font-extrabold text-[20px] text-blue-600 tracking-tight">NEXGEN POS</div>
        </div>

        <div class="flex-1 max-w-md mx-8">
            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                placeholder="Search products or scan barcode..."
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none">
        </div>

        <div class="flex items-center gap-4 text-sm">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold text-xs">Register Open</span>
            <span class="text-gray-500">{{ now()->format('h:i A') }}</span>
            <div class="relative">
                <strong @click="userMenuOpen = !userMenuOpen"
                    class="font-bold cursor-pointer hover:text-blue-600 transition-colors">{{ auth()->user()->name ?? 'Alex Cashier' }}</strong>
                <div x-show="userMenuOpen" @click.away="userMenuOpen = false" x-cloak
                    class="absolute right-0 mt-2 w-32 bg-white border border-gray-200 rounded-lg shadow-lg z-10">
                    <a href="/cashier/dashboard"
                        class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 transition-colors">
                        Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100 transition-colors">
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex flex-1 overflow-hidden">

        {{-- Catalog Section --}}
        <section class="flex-1 p-6 flex flex-col gap-5 overflow-hidden">
            <div class="flex flex-col gap-5 overflow-hidden">
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
                                📦
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
        </section>

        {{-- Cart Sidebar --}}
        <aside class="w-[340px] bg-white border-l border-gray-200 flex flex-col shrink-0">
            <div class="p-5 border-b border-gray-200 flex justify-between items-center">
                <span class="font-bold">Current Order</span>
                <div class="flex items-center gap-3">
                    <button wire:click="cancelOrder"
                        class="text-xs text-red-500 hover:text-red-700 font-bold transition-colors">Cancel</button>
                    <span class="text-blue-600 font-bold">#{{ str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT) }}</span>
                </div>
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
                    <span>VAT ({{ number_format($taxRate, 2) }}%)</span>
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



    {{-- Start New Order Modal --}}
    <div x-show="!$wire.orderStarted" x-cloak
        class="fixed inset-0 z-[200] flex items-center justify-center p-4 sm:p-6 bg-slate-900/80 backdrop-blur-sm">

        <div class="bg-white rounded-3xl shadow-2xl w-full max-w-5xl flex flex-col overflow-hidden max-h-[90vh]"
            @click.stop>

            {{-- Header --}}
            <div
                class="p-6 sm:px-8 sm:py-6 border-b border-slate-200 flex flex-col sm:flex-row justify-between items-center gap-4 bg-slate-50">

                <div class="flex items-center gap-4">

                    <!-- 🔙 Back Button -->
                    <a href="/cashier/dashboard"
                        class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 font-bold rounded-lg transition flex items-center gap-2 shadow-sm">
                        ← Back
                    </a>

                    <div>
                        <h2 class="text-2xl font-black text-gray-900 tracking-tight">Start New Order</h2>
                        <p class="text-sm text-gray-500 font-medium mt-1">Who is this order for?</p>
                    </div>
                </div>

                <!-- Walk-in -->
                <button wire:click="startOrderAsGuest"
                    class="px-6 py-3 bg-white border-2 border-slate-200 hover:border-blue-500 hover:bg-blue-50 hover:text-blue-700 text-gray-800 font-bold rounded-xl transition-all flex items-center gap-2 shadow-sm">
                    <span class="text-xl">🚶</span>
                    Proceed as Walk-in Guest
                </button>
            </div>

            {{-- Body --}}
            <div class="p-6 sm:p-8 overflow-y-auto bg-white flex-1">

                <div class="grid gap-8 lg:grid-cols-2 divide-y lg:divide-y-0 lg:divide-x divide-slate-200">

                    {{-- Existing Customer --}}
                    <div class="lg:pr-8 flex flex-col h-full">

                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-800">
                            <span class="text-blue-500">🔍</span> Select Existing Customer
                        </h3>

                        <div class="relative mb-4 shrink-0">
                            <input type="text" wire:model.live.debounce.300ms="searchQuery"
                                placeholder="Search by name, phone, or email..."
                                class="w-full pl-11 pr-4 py-3 bg-slate-50 border-slate-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none transition-all font-medium">

                            <div class="absolute left-4 top-3.5 text-slate-400">
                                🔎
                            </div>
                        </div>

                        <div
                            class="flex-1 overflow-y-auto border border-slate-100 rounded-xl divide-y divide-slate-100 min-h-[300px]">

                            @forelse($customers as $customer)
                                <div wire:click="selectCustomerAndStart({{ $customer->id }})"
                                    class="p-4 hover:bg-blue-50 cursor-pointer flex justify-between items-center transition-colors group">

                                    <div>
                                        <div class="font-bold text-gray-900">{{ $customer->name }}</div>
                                        <div class="text-xs text-gray-500 flex items-center gap-3 mt-1">
                                            <span>📞 {{ $customer->phone ?? 'N/A' }}</span>
                                            <span>✉️ {{ $customer->email ?? 'N/A' }}</span>
                                        </div>
                                    </div>

                                    <button
                                        class="px-4 py-2 bg-white border border-slate-200 rounded-lg text-blue-600 text-sm font-bold shadow-sm group-hover:bg-blue-600 group-hover:text-white group-hover:border-blue-600 transition-colors">
                                        Select
                                    </button>
                                </div>
                            @empty
                                <div class="p-8 text-center text-gray-500 flex flex-col items-center justify-center h-full">
                                    <span class="text-4xl mb-3 opacity-50">📭</span>
                                    <p class="font-medium">No customers found.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- New Customer --}}
                    <div class="pt-8 lg:pt-0 lg:pl-8">

                        <h3 class="text-lg font-bold mb-4 flex items-center gap-2 text-gray-800">
                            <span class="text-green-500">✨</span> Register New Customer
                        </h3>

                        <div class="space-y-4">

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">
                                    Full Name <span class="text-red-500">*</span>
                                </label>
                                <input wire:model.defer="newCustomerName" type="text"
                                    class="block w-full bg-slate-50 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-all"
                                    placeholder="e.g. John Doe" />
                                @error('newCustomerName')
                                    <span class="text-red-500 text-xs mt-1 font-medium">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="grid grid-cols-2 gap-4">

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Phone</label>
                                    <input wire:model.defer="newCustomerPhone" type="text"
                                        class="block w-full bg-slate-50 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-all"
                                        placeholder="Optional" />
                                </div>

                                <div>
                                    <label class="block text-sm font-semibold text-gray-700 mb-1">Credit Limit</label>
                                    <input wire:model.defer="newCustomerCreditLimit" type="number" step="0.01"
                                        class="block w-full bg-slate-50 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-all"
                                        placeholder="0.00" />
                                </div>
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-1">Email</label>
                                <input wire:model.defer="newCustomerEmail" type="email"
                                    class="block w-full bg-slate-50 border-slate-200 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-all"
                                    placeholder="Optional" />
                            </div>

                            <button wire:click="addCustomerAndStart"
                                class="w-full mt-4 rounded-xl bg-blue-600 px-4 py-4 text-white font-bold text-lg hover:bg-blue-700 shadow-md hover:shadow-lg transition-all flex justify-center items-center gap-2">
                                Register & Start Order →
                            </button>

                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
</div>
</div>