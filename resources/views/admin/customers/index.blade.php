<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">👤 Customers</h2>
            <a href="{{ route('admin.customers.create') }}"
               class="inline-flex items-center px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow transition">
                + New Customer
            </a>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Name</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Limit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Credit Used</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($customers as $customer)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $customer->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->phone ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $customer->email ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-700 dark:text-gray-300">${{ number_format($customer->credit_limit, 2) }}</td>
                            <td class="px-6 py-4 text-sm {{ $customer->credit_used > 0 ? 'text-red-600 font-semibold' : 'text-gray-500' }}">${{ number_format($customer->credit_used, 2) }}</td>
                            <td class="px-6 py-4 text-right space-x-2">
                                <a href="{{ route('admin.customers.edit', $customer) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">Edit</a>
                                <form action="{{ route('admin.customers.destroy', $customer) }}" method="POST" class="inline"
                                      onsubmit="return confirm('Delete this customer?')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-900 text-sm font-medium">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="px-6 py-10 text-center text-gray-400">No customers found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $customers->links() }}</div>
        </div>
    </div>
</x-app-layout>
