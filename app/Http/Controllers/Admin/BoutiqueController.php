<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MediaUrl;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StoreOrder;
use App\Models\Visit;
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
            'realtime_visitors' => Visit::where('visited_at', '>', now()->subMinutes(5))->distinct()->count('session_id'),
            'daily_visitors' => Visit::whereDate('visited_at', now()->toDateString())->distinct()->count('session_id'),
            'monthly_visitors' => Visit::whereYear('visited_at', now()->year)->whereMonth('visited_at', now()->month)->distinct()->count('session_id'),
            'abandoned_carts' => StoreOrder::where('status', 'pending')->count(),
            'revenue' => StoreOrder::sum('total'),
        ];

        $mostViewed = Product::orderBy('view_count', 'desc')->limit(5)->get();

        $categories = ProductCategory::withCount('products')->orderBy('name')->get();

        return view('admin.boutique.products', compact('products', 'stats', 'categories', 'mostViewed'));
    }

    /**
     * Liste des commandes.
     */
    public function orders()
    {
        $orders = StoreOrder::orderBy('created_at', 'desc')->paginate(20);
        $productImages = Product::all()->mapWithKeys(fn($p) => [$p->id => MediaUrl::fromPath($p->image)])->all();

        $stats = [
            'total' => StoreOrder::count(),
            'pending' => StoreOrder::where('status', 'pending')->count(),
            'completed' => StoreOrder::where('status', '!=', 'pending')->count(),
            'delivered' => StoreOrder::where('status', 'delivered')->count(),
            'revenue' => StoreOrder::where('status', 'delivered')->sum('total'),
        ];

        return view('admin.boutique.index', compact('orders', 'stats', 'productImages'));
    }

    /**
     * Détail d'une commande.
     */
    public function showOrder(StoreOrder $order)
    {
        $productImages = Product::all()->mapWithKeys(fn($p) => [$p->id => MediaUrl::fromPath($p->image)])->all();
        return view('admin.boutique.orders.show', compact('order', 'productImages'));
    }

    /**
     * Suppression d'une commande.
     */
    public function destroyOrder(StoreOrder $order)
    {
        $order->delete();
        return redirect()->route('admin.boutique.orders')->with('success', 'Commande supprimée.');
    }

    /**
     * Mise à jour du statut d'une commande.
     */
    public function updateOrderStatus(Request $request, StoreOrder $order)
    {
        $allowed = ['payment_pending', 'payment_confirmed', 'preparing', 'ready_for_pickup', 'in_delivery', 'delivered', 'cancelled'];
        $status = $request->input('status');

        if (!in_array($status, $allowed, true)) {
            return redirect()->route('admin.boutique.orders')->with('error', 'Statut invalide.');
        }

        $order->status = $status;
        $order->save();

        return redirect()->route('admin.boutique.orders')->with('success', 'Statut de la commande mis à jour.');
    }

    /**
     * Formulaire de création d'une catégorie.
     */
    public function createCategory()
    {
        return view('admin.boutique.categories.create');
    }

    /**
     * Enregistrement d'une catégorie.
     */
    public function storeCategory(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:product_categories,name',
        ]);

        ProductCategory::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'is_active' => $request->boolean('is_active', true),
        ]);

        return redirect()->route('admin.boutique.index')->with('success', 'Catégorie créée avec succès.');
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
            'old_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'delivery_mode' => 'required|in:deposit,cash_on_delivery',
            'delivery_cost' => 'nullable|integer|min:0',
            'email' => 'nullable|email|max:255',
            'seo_geo' => 'nullable|string|max:5000',
            'category_id' => 'required|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
            'variants' => 'nullable|json',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', true);
        $validated['variants'] = $request->filled('variants') ? json_decode($request->input('variants'), true) : null;

        $imagePaths = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $imagePaths[] = $file->store('products', 'public');
            }
        }

        $validated['images'] = $imagePaths;
        $validated['image'] = $imagePaths[0] ?? null;

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
            'old_price' => 'nullable|integer|min:0',
            'stock' => 'required|integer|min:0',
            'delivery_mode' => 'required|in:deposit,cash_on_delivery',
            'delivery_cost' => 'nullable|integer|min:0',
            'email' => 'nullable|email|max:255',
            'seo_geo' => 'nullable|string|max:5000',
            'category_id' => 'required|exists:product_categories,id',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',
            'is_active' => 'boolean',
            'variants' => 'nullable|json',
        ]);

        $validated['slug'] = Str::slug($validated['title']);
        $validated['is_active'] = $request->boolean('is_active', false);
        $validated['variants'] = $request->filled('variants') ? json_decode($request->input('variants'), true) : null;

        $images = $product->images ?? [];

        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $file) {
                $images[] = $file->store('products', 'public');
            }
        }

        if ($request->filled('remove_images')) {
            foreach ($request->input('remove_images') as $path) {
                if (in_array($path, $images)) {
                    Storage::disk('public')->delete($path);
                    $images = array_values(array_diff($images, [$path]));
                }
            }
        }

        $validated['images'] = $images;
        $validated['image'] = $images[0] ?? null;

        $product->update($validated);

        return redirect()->route('admin.boutique.index')->with('success', 'Produit mis à jour avec succès.');
    }

    /**
     * Suppression d'un produit.
     */
    public function destroy(Product $product)
    {
        foreach ($product->images ?? [] as $img) {
            Storage::disk('public')->delete($img);
        }

        if ($product->image && !in_array($product->image, $product->images ?? [])) {
            Storage::disk('public')->delete($product->image);
        }

        $product->delete();

        return redirect()->route('admin.boutique.index')->with('success', 'Produit supprimé avec succès.');
    }
}
