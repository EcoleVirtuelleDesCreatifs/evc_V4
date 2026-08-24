<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StoreOrder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BoutiqueController extends Controller
{
    /**
     * Liste des produits (page principale de la boutique).
     */
    public function index()
    {
        $products = Product::with('category')->orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => Product::count(),
            'active' => Product::where('is_active', true)->count(),
            'inactive' => Product::where('is_active', false)->count(),
            'orders' => StoreOrder::count(),
        ];

        return view('admin.boutique.products', compact('products', 'stats'));
    }

    /**
     * Liste des commandes.
     */
    public function orders()
    {
        $orders = StoreOrder::orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => StoreOrder::count(),
            'pending' => StoreOrder::where('status', 'pending')->count(),
            'completed' => StoreOrder::where('status', '!=', 'pending')->count(),
        ];

        return view('admin.boutique.index', compact('orders', 'stats'));
    }

    /**
     * Formulaire de création d'un produit.
     */
    public function create()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.boutique.create', compact('categories'));
    }

    /**
     * Enregistrement d'un produit.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'delivery_mode' => 'required|in:deposit,cash_on_delivery',
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        Product::create($validated);

        return redirect()->route('admin.boutique.index')->with('success', 'Produit créé avec succès.');
    }

    /**
     * Formulaire d'édition d'un produit.
     */
    public function edit(Product $product)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        return view('admin.boutique.edit', compact('product', 'categories'));
    }

    /**
     * Mise à jour d'un produit.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'summary' => 'nullable|string|max:500',
            'description' => 'nullable|string',
            'price' => 'required|integer|min:0',
            'delivery_mode' => 'required|in:deposit,cash_on_delivery',
            'category_id' => 'required|exists:product_categories,id',
            'image' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', false);

        if ($request->hasFile('image')) {
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            $validated['image'] = $request->file('image')->store('products', 'public');
        }

        $product->update($validated);

        return redirect()->route('admin.boutique.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Suppression d'un produit.
     */
    public function destroy(Product $product)
    {
        if ($product->image) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.boutique.index')->with('success', 'Produit supprimé avec succès.');
    }
}
