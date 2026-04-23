<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Activity Log Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="mb-6">
                        <a href="{{ route('manager.activity-logs.index') }}" class="text-blue-600 hover:text-blue-900">
                            ← Back to Activity Logs
                        </a>
                    </div>
                    
                    <div class="space-y-4">
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Basic Information</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Date & Time:</span>
                                    <p class="mt-1">{{ $activityLog->created_at->format('F j, Y g:i A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">User:</span>
                                    <p class="mt-1">{{ $activityLog->user->name ?? 'System' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Role:</span>
                                    <p class="mt-1">{{ ucfirst($activityLog->user->role ?? 'N/A') }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">IP Address:</span>
                                    <p class="mt-1">{{ $activityLog->ip_address ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="border-b pb-4">
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Action Details</h3>
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Action Type:</span>
                                    <p class="mt-1">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            @if(str_contains($activityLog->action, 'created')) bg-green-100 text-green-800
                                            @elseif(str_contains($activityLog->action, 'updated')) bg-blue-100 text-blue-800
                                            @elseif(str_contains($activityLog->action, 'deleted')) bg-red-100 text-red-800
                                            @else bg-gray-100 text-gray-800
                                            @endif">
                                            {{ str_replace('_', ' ', ucfirst($activityLog->action)) }}
                                        </span>
                                    </p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Model Type:</span>
                                    <p class="mt-1">{{ $activityLog->model_type ?? '-' }}</p>
                                </div>
                                <div>
                                    <span class="text-sm font-medium text-gray-500">Model ID:</span>
                                    <p class="mt-1">{{ $activityLog->model_id ?? '-' }}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Description</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-gray-700">{{ $activityLog->description }}</p>
                            </div>
                        </div>
                        
                        @if($activityLog->metadata)
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Additional Data</h3>
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <pre class="text-sm text-gray-700">{{ json_encode($activityLog->metadata, JSON_PRETTY_PRINT) }}</pre>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>