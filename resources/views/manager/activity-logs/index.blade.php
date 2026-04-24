<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                🔍 Activity Logs
            </h2>
            <div class="text-sm text-gray-500">
                Showing: Product activities &amp; all Cashier activities
            </div>
        </div>
    </x-slot>

    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">

            <!-- Filters -->
            <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">User</label>
                    <select name="user_id" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ ucfirst($user->role) }})
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Action</label>
                    <select name="action" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                        <option value="">All Actions</option>
                        @foreach($actions as $value => $label)
                            <option value="{{ $value }}" {{ request('action') === $value ? 'selected' : '' }}>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg shadow">Filter</button>
                <a href="{{ route('manager.activity-logs.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            </form>

            <!-- Table -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Time</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Action</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Model</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">IP</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                            $actionColors = [
                                'created' => 'bg-green-100 text-green-700',
                                'updated' => 'bg-blue-100 text-blue-700',
                                'deleted' => 'bg-red-100 text-red-700',
                                'login'   => 'bg-purple-100 text-purple-700',
                                'logout'  => 'bg-gray-100 text-gray-600',
                            ];
                        @endphp
                        @forelse($activityLogs as $log)
                            <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                                <td class="px-6 py-3 text-xs text-gray-500 whitespace-nowrap">
                                    {{ $log->created_at->format('d M Y H:i:s') }}
                                </td>
                                <td class="px-6 py-3">
                                    <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $log->user?->name ?? 'System' }}</div>
                                    <div class="text-xs text-gray-400">{{ ucfirst($log->user?->role ?? '') }}</div>
                                </td>
                                <td class="px-6 py-3">
                                    <span class="px-2 py-0.5 text-xs rounded-full font-semibold {{ $actionColors[$log->action] ?? 'bg-yellow-100 text-yellow-700' }}">
                                        {{ $actions[$log->action] ?? ucfirst($log->action) }}
                                    </span>
                                </td>
                                <td class="px-6 py-3 text-xs text-gray-500">
                                    {{ $log->model_type ?? '—' }}{{ $log->model_id ? " #{$log->model_id}" : '' }}
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-700 dark:text-gray-300 max-w-xs truncate">
                                    {{ $log->description }}
                                </td>
                                <td class="px-6 py-3 text-xs text-gray-400 font-mono">{{ $log->ip_address ?? '—' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-10 text-center text-gray-400">No activity logs found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div>{{ $activityLogs->links() }}</div>
        </div>
    </div>
</x-app-layout>