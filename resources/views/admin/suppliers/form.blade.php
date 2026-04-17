@php
$isEdit = isset($supplier);
$action = $isEdit ? route('admin.suppliers.update', $supplier) : route('admin.suppliers.store');
$title  = $isEdit ? "Edit Supplier: {$supplier->name}" : 'New Supplier';
@endphp
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ $title }}</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <form action="{{ $action }}" method="POST" class="space-y-4">
                    @csrf
                    @if($isEdit) @method('PUT') @endif
                    @foreach(['name'=>'Supplier Name','contact_person'=>'Contact Person','phone'=>'Phone','email'=>'Email'] as $field => $label)
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">{{ $label }}</label>
                        <input type="{{ $field === 'email' ? 'email' : 'text' }}" name="{{ $field }}"
                               value="{{ old($field, $isEdit ? $supplier->$field : '') }}"
                               {{ $field === 'name' ? 'required' : '' }}
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error($field)<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    @endforeach
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Address</label>
                        <textarea name="address" rows="2" class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('address', $isEdit ? $supplier->address : '') }}</textarea>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('admin.suppliers.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                            {{ $isEdit ? 'Save Changes' : 'Create Supplier' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
