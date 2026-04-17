<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">New Purchase Order</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ route('admin.purchase-orders.store') }}" method="POST" x-data="poForm()">
                    @csrf
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Supplier</label>
                            <select name="supplier_id" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">-- Select Supplier --</option>
                                @foreach($suppliers as $s)
                                    <option value="{{ $s->id }}" {{ old('supplier_id') == $s->id ? 'selected' : '' }}>{{ $s->name }}</option>
                                @endforeach
                            </select>
                            @error('supplier_id')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Notes</label>
                            <input type="text" name="notes" value="{{ old('notes') }}" placeholder="Optional notes"
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        </div>
                    </div>

                    <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3 uppercase tracking-wider">Order Items</h3>
                    <div class="space-y-3 mb-4">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex gap-3 items-start">
                                <div class="flex-1">
                                    <select :name="`items[${index}][product_id]`" required
                                            class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                        <option value="">-- Product --</option>
                                        @foreach($products as $p)
                                            <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->barcode }})</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-28">
                                    <input type="number" :name="`items[${index}][quantity]`" x-model.number="item.qty"
                                           placeholder="Qty" min="1" required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                </div>
                                <div class="w-32">
                                    <input type="number" :name="`items[${index}][unit_cost]`" x-model.number="item.cost"
                                           placeholder="Unit Cost" min="0" step="0.01" required
                                           class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm text-sm">
                                </div>
                                <button type="button" @click="removeItem(index)"
                                        class="text-red-500 hover:text-red-700 mt-1 text-sm">✕</button>
                            </div>
                        </template>
                    </div>
                    <button type="button" @click="addItem()"
                            class="text-indigo-600 hover:text-indigo-800 text-sm font-medium mb-6">+ Add Item</button>

                    <div class="flex justify-between items-center border-t pt-4">
                        <p class="text-sm text-gray-600 dark:text-gray-400">
                            Estimated Total: <strong class="text-gray-900 dark:text-white" x-text="'$'+total().toFixed(2)"></strong>
                        </p>
                        <div class="flex gap-2">
                            <a href="{{ route('admin.purchase-orders.index') }}" class="px-4 py-2 text-sm text-gray-600">Cancel</a>
                            <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                                Create Order
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script>
    function poForm() {
        return {
            items: [{ qty: 1, cost: 0 }],
            addItem() { this.items.push({ qty: 1, cost: 0 }); },
            removeItem(i) { if (this.items.length > 1) this.items.splice(i, 1); },
            total() { return this.items.reduce((s, i) => s + (i.qty * i.cost), 0); }
        };
    }
    </script>
</x-app-layout>
