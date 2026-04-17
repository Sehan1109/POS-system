@php
$isEdit = isset($expense);
$action = $isEdit ? route('admin.expenses.update', $expense) : route('admin.expenses.store');
$title  = $isEdit ? 'Edit Expense' : 'Record Expense';
$categories = ['Rent','Utilities','Salaries','Supplies','Marketing','Maintenance','Transport','Other'];
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
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category *</label>
                        <select name="category" required class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}" {{ old('category', $isEdit ? $expense->category : '') === $cat ? 'selected' : '' }}>{{ $cat }}</option>
                            @endforeach
                        </select>
                        @error('category')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description *</label>
                        <input type="text" name="description" value="{{ old('description', $isEdit ? $expense->description : '') }}" required
                               class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('description')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Amount ($) *</label>
                            <input type="number" name="amount" step="0.01" min="0"
                                   value="{{ old('amount', $isEdit ? $expense->amount : '') }}" required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('amount')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date *</label>
                            <input type="date" name="expense_date"
                                   value="{{ old('expense_date', $isEdit ? $expense->expense_date->format('Y-m-d') : now()->format('Y-m-d')) }}" required
                                   class="w-full rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('expense_date')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 pt-2">
                        <a href="{{ route('admin.expenses.index') }}" class="px-4 py-2 text-sm text-gray-600 hover:text-gray-900">Cancel</a>
                        <button type="submit" class="px-5 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm font-medium rounded-lg shadow">
                            {{ $isEdit ? 'Save Changes' : 'Record Expense' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
