<div class="flex flex-col w-full h-screen bg-gray-100 text-gray-900 font-sans overflow-hidden">
    
    {{-- Flash Messages --}}
    @if (session()->has('success'))
        <div class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-green-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold">
            {{ session('success') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="absolute top-4 left-1/2 transform -translate-x-1/2 bg-red-500 text-white px-6 py-3 rounded-lg shadow-lg z-50 font-bold">
            {{ session('error') }}
        </div>
    @endif

    {{-- Header --}}
    <header class="h-[60px] bg-white border-b border-gray-200 flex items-center justify-between px-6 shrink-0">
        <div class="font-extrabold text-[20px] text-blue-600 tracking-tight">NEXGEN POS</div>
        
        <div class="flex-1 max-w-md mx-8">
            <input 
                type="text" 
                wire:model.live.debounce.300ms="searchQuery" 
                placeholder="Search products or scan barcode..." 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 outline-none"
            >
        </div>

        <div class="flex items-center gap-4 text-sm">
            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full font-semibold text-xs">Register Open</span>
            <span class="text-gray-500">{{ now()->format('h:i A') }}</span>
            <strong class="font-bold">{{ auth()->user()->name ?? 'Alex Cashier' }}</strong>
        </div>
    </header>

    {{-- Main Content --}}
    <main class="flex flex-1 overflow-hidden">
        {{-- Sidebar --}}
        <nav class="w-[80px] bg-white border-r border-gray-200 flex flex-col items-center pt-5 gap-6 shrink-0">
            <div class="w-12 h-12 rounded-xl flex items-center justify-center cursor-pointer bg-blue-50 text-blue-600 text-xl">📦</div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center cursor-pointer text-gray-500 hover:bg-gray-50 text-xl transition-colors">📑</div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center cursor-pointer text-gray-500 hover:bg-gray-50 text-xl transition-colors">👥</div>
            <div class="w-12 h-12 rounded-xl flex items-center justify-center cursor-pointer text-gray-500 hover:bg-gray-50 text-xl transition-colors">⚙️</div>
        </nav>

        {{-- Catalog Section --}}
        <section class="flex-1 p-6 flex flex-col gap-5 overflow-hidden">
            {{-- Categories --}}
            <div class="flex gap-3 overflow-x-auto pb-2 shrink-0">
                <div
                    wire:click="setCategory(null)"
                    class="px-5 py-2 border rounded-lg text-sm font-medium cursor-pointer transition-colors whitespace-nowrap {{ is_null($activeCategoryId) ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-200 text-gray-900 hover:border-blue-600' }}"
                >
                    All Categories
                </div>
                @foreach($categories as $cat)
                    <div
                        wire:click="setCategory({{ $cat->id }})"
                        class="px-5 py-2 border rounded-lg text-sm font-medium cursor-pointer transition-colors whitespace-nowrap {{ $activeCategoryId == $cat->id ? 'bg-blue-600 text-white border-blue-600' : 'bg-white border-gray-200 text-gray-900 hover:border-blue-600' }}"
                    >
                        {{ $cat->name }}
                    </div>
                @endforeach
            </div>

            {{-- Product Grid --}}
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 overflow-y-auto pb-6 pr-2">
                @forelse($products as $prod)
                    <div
                        wire:click="addToCart({{ $prod->id }})"
                        class="bg-white rounded-xl border border-gray-200 p-4 flex flex-col gap-2 cursor-pointer hover:border-blue-600 hover:shadow-md transition-all {{ $prod->stock_quantity <= 0 ? 'opacity-50 cursor-not-allowed' : '' }}"
                    >
                        <div class="w-full h-[100px] bg-gray-50 rounded-lg flex items-center justify-center text-4xl">
                            📦 {{-- You can replace this with an actual image tag if you add image paths to DB --}}
                        </div>
                        <div class="font-semibold text-sm text-gray-900 mt-1 leading-tight">{{ $prod->name }}</div>
                        <div class="text-blue-600 font-bold text-base">${{ number_format($prod->selling_price, 2) }}</div>
                        <div class="text-xs {{ $prod->stock_quantity <= 5 ? 'text-red-500 font-bold' : 'text-gray-500' }}">
                            Stock: {{ $prod->stock_quantity }} units
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center text-gray-500 py-10">
                        No products found.
                    </div>
                @endforelse
            </div>
        </section>

        {{-- Cart Sidebar --}}
        <aside class="w-[340px] bg-white border-l border-gray-200 flex flex-col shrink-0">
            <div class="p-5 border-b border-gray-200 font-bold flex justify-between items-center">
                <span>Current Order</span>
                <span class="text-blue-600">#{{ str_pad(rand(1,9999), 4, '0', STR_PAD_LEFT) }}</span>
            </div>

            <div class="flex-1 overflow-y-auto px-5 py-2">
                @forelse($cart as $index => $item)
                    <div class="flex justify-between py-3 border-b border-dashed border-gray-200 last:border-0 items-center">
                        <div class="flex flex-col">
                            <h4 class="font-semibold text-sm text-gray-900 mb-1">{{ $item['name'] }}</h4>
                            <span class="text-xs text-gray-500">x{{ $item['quantity'] }} Units (${{ number_format($item['unit_price'], 2) }} ea)</span>
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="font-semibold text-gray-900">${{ number_format($item['sub_total'], 2) }}</div>
                            <button wire:click="removeFromCart({{ $index }})" class="text-red-500 hover:text-red-700 font-bold">×</button>
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
                    <span>VAT (10%)</span>
                    <span>${{ number_format($taxAmount, 2) }}</span>
                </div>
                <div class="flex justify-between font-extrabold text-xl my-2 pt-2 border-t border-gray-200 text-gray-900">
                    <span>TOTAL</span>
                    <span>${{ number_format($total, 2) }}</span>
                </div>
                <div class="grid grid-cols-2 gap-2 mt-1">
                    <button 
                        wire:click="setPaymentMethod('cash')"
                        class="p-2 border rounded-lg text-sm font-semibold cursor-pointer transition-colors flex items-center justify-center gap-2 {{ $paymentMethod === 'cash' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white hover:border-blue-600' }}"
                    >
                        💵 Cash
                    </button>
                    <button 
                        wire:click="setPaymentMethod('card')"
                        class="p-2 border rounded-lg text-sm font-semibold cursor-pointer transition-colors flex items-center justify-center gap-2 {{ $paymentMethod === 'card' ? 'border-blue-600 bg-blue-50 text-blue-700' : 'border-gray-200 bg-white hover:border-blue-600' }}"
                    >
                        💳 Card
                    </button>
                </div>
                <button 
                    wire:click="checkout"
                    wire:loading.attr="disabled"
                    class="w-full bg-blue-600 text-white border-none p-4 rounded-xl font-bold text-base cursor-pointer text-center hover:bg-blue-700 transition-colors mt-2 disabled:opacity-50"
                >
                    <span wire:loading.remove wire:target="checkout">PAY NOW</span>
                    <span wire:loading wire:target="checkout">Processing...</span>
                </button>
            </div>
        </aside>
    </main>
</div>