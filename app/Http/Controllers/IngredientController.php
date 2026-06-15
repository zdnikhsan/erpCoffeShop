<?php

namespace App\Http\Controllers;

use App\Http\Requests\IngredientRequest;
use App\Models\Ingredient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IngredientController extends Controller
{
    /**
     * Display a listing of ingredients.
     */
    public function index(Request $request): View
    {
        $search = $request->query('search');

        $ingredients = Ingredient::query()
            ->when($search, fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('sku', 'like', "%{$search}%"))
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('ingredients.index', compact('ingredients', 'search'));
    }

    /**
     * Show the form for creating a new ingredient.
     */
    public function create(): View
    {
        return view('ingredients.create');
    }

    /**
     * Store a newly created ingredient in storage.
     */
    public function store(IngredientRequest $request): RedirectResponse
    {
        Ingredient::create($request->validated());

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Bahan baku berhasil ditambahkan.');
    }

    /**
     * Display the specified ingredient.
     */
    public function show(Ingredient $ingredient): View
    {
        return view('ingredients.show', compact('ingredient'));
    }

    /**
     * Show the form for editing the specified ingredient.
     */
    public function edit(Ingredient $ingredient): View
    {
        return view('ingredients.edit', compact('ingredient'));
    }

    /**
     * Update the specified ingredient in storage.
     */
    public function update(IngredientRequest $request, Ingredient $ingredient): RedirectResponse
    {
        $ingredient->update($request->validated());

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Bahan baku berhasil diperbarui.');
    }

    /**
     * Remove the specified ingredient from storage.
     */
    public function destroy(Ingredient $ingredient): RedirectResponse
    {
        $ingredient->delete();

        return redirect()
            ->route('ingredients.index')
            ->with('success', 'Bahan baku berhasil dihapus.');
    }
}
