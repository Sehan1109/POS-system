<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Cashier Activity Logs') }}
            </h2>
            <div class="text-sm text-gray-500">
                Showing only cashier-related activities (Login/Logout/Sales/User Management)
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    
                    <!-- Filter Form -->
                    <form method="GET" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Action Type</label>
                            <select name="action" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Actions</option>
                                @foreach($actions as $value => $label)
                                    <option value="{{ $value }}" {{ request('action') == $value ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Cashier</label>
                            <select name="user_id" class="w-full rounded-md border-gray-300 shadow-sm">
                                <option value="">All Cashiers</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ ucfirst($user->role) }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date From</label>
                            <input type="date" name="date_from" value="{{ request('date_from') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Date To</label>
                            <input type="date" name="date_to" value="{{ request('date_to') }}" 
                                   class="w-full rounded-md border-gray-300 shadow-sm">
                        </div>
                        
                        <div class="md:col-span-4 flex justify-end">
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                Filter Logs
                            </button>
                            <a href="{{ route('manager.activity-logs.index') }}" class="ml-2 px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600">
                                Reset
                            </a>
                        </div>
                    </form>
                    
                    <!-- Activity Logs Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cashier</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Description</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($activityLogs as $log)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            {{ $log->created_at->format('Y-m-d H:i:s') }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $log->user->name ?? 'System' }}</div>
                                            <div class="text-xs text-gray-500">{{ ucfirst($log->user->role ?? 'N/A') }}</div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                @if($log->action == 'login') bg-indigo-100 text-indigo-800
                                                @elseif($log->action == 'logout') bg-gray-100 text-gray-800
                                                @elseif($log->action == 'sale_created') bg-green-100 text-green-800
                                                @elseif($log->action == 'user_created') bg-blue-100 text-blue-800
                                                @elseif($log->action == 'user_deleted') bg-red-100 text-red-800
                                                @else bg-yellow-100 text-yellow-800
                                                @endif">
                                                {{ $actions[$log->action] ?? str_replace('_', ' ', ucfirst($log->action)) }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500 max-w-md">
                                            {{ Str::limit($log->description, 100) }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="{{ route('manager.activity-logs.show', $log) }}" class="text-blue-600 hover:text-blue-900">View Details</a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                                            <div class="py-8">
                                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                </svg>
                                                <p class="mt-2 text-gray-500">No cashier activity logs found</p>
                                                <p class="text-sm text-gray-400">Try adjusting your filters or resetting the form</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="mt-4">
                        {{ $activityLogs->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>