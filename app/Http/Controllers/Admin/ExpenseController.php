<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Expense;
use Illuminate\Http\Request;

class ExpenseController extends Controller
{
    public function index()
    {
        $expenses = Expense::with('user')->latest('expense_date')->paginate(20);
        return view('admin.expenses.index', compact('expenses'));
    }

    public function create()
    {
        return view('admin.expenses.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'category'     => 'required|string|max:100',
            'description'  => 'required|string|max:500',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);
        $validated['user_id'] = auth()->id();
        $expense = Expense::create($validated);
        ActivityLog::record('created', "Logged expense: {$expense->description} ({$expense->amount})", $expense);
        return redirect()->route('admin.expenses.index')->with('success', 'Expense recorded successfully.');
    }

    public function edit(Expense $expense)
    {
        return view('admin.expenses.edit', compact('expense'));
    }

    public function update(Request $request, Expense $expense)
    {
        $validated = $request->validate([
            'category'     => 'required|string|max:100',
            'description'  => 'required|string|max:500',
            'amount'       => 'required|numeric|min:0',
            'expense_date' => 'required|date',
        ]);
        $expense->update($validated);
        ActivityLog::record('updated', "Updated expense: {$expense->description}", $expense);
        return redirect()->route('admin.expenses.index')->with('success', 'Expense updated successfully.');
    }

    public function destroy(Expense $expense)
    {
        ActivityLog::record('deleted', "Deleted expense: {$expense->description}", $expense);
        $expense->delete();
        return redirect()->route('admin.expenses.index')->with('success', 'Expense deleted.');
    }
}
