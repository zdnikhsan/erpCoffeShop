<?php

namespace App\Http\Controllers;

use App\Http\Requests\ExpenseRequest;
use App\Models\Expense;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ExpenseController extends Controller
{
    /**
     * Display a listing of expenses.
     */
    public function index(Request $request): View
    {
        $search   = $request->query('search');
        $category = $request->query('category');

        $expenses = Expense::query()
            ->when($search, fn ($q) => $q->where('expense_number', 'like', "%{$search}%")
                ->orWhere('note', 'like', "%{$search}%"))
            ->when($category, fn ($q) => $q->where('category', $category))
            ->latest('date')
            ->paginate(10)
            ->withQueryString();

        $categories = Expense::CATEGORIES;

        return view('expenses.index', compact('expenses', 'search', 'category', 'categories'));
    }

    /**
     * Show the form for creating a new expense.
     */
    public function create(): View
    {
        $categories = Expense::CATEGORIES;
        return view('expenses.create', compact('categories'));
    }

    /**
     * Store a newly created expense in storage.
     */
    public function store(ExpenseRequest $request): RedirectResponse
    {
        Expense::create($request->validated());

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dicatat.');
    }

    /**
     * Display the specified expense.
     */
    public function show(Expense $expense): View
    {
        return view('expenses.show', compact('expense'));
    }

    /**
     * Show the form for editing the specified expense.
     */
    public function edit(Expense $expense): View
    {
        $categories = Expense::CATEGORIES;
        return view('expenses.edit', compact('expense', 'categories'));
    }

    /**
     * Update the specified expense in storage.
     */
    public function update(ExpenseRequest $request, Expense $expense): RedirectResponse
    {
        $expense->update($request->validated());

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil diperbarui.');
    }

    /**
     * Remove the specified expense from storage.
     */
    public function destroy(Expense $expense): RedirectResponse
    {
        $expense->delete();

        return redirect()
            ->route('expenses.index')
            ->with('success', 'Pengeluaran berhasil dihapus.');
    }
}
