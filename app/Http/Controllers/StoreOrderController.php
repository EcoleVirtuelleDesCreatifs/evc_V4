<?php

namespace App\Http\Controllers;

use App\Models\MediaUrl;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StoreOrder;
use App\Models\Visit;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('created_at', 'desc')->get();

        $productsForJs = $products->map(function (Product $product) {
            return [
                'id' => $product->id,
                'category' => $product->category->slug ?? '',
                'image_url' => MediaUrl::fromPath($product->image),
                'images' => collect($product->images ?? [])->map(fn($img) => MediaUrl::fromPath($img))->filter()->values()->all(),
                'name' => $product->title,
                'desc' => $product->summary ?: $product->description,
                'description' => $product->description,
                'price' => $product->price,
            ];
        });

        Visit::create([
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
            'path' => $request->path(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        return response()
            ->view('evc-store.index', compact('categories', 'products', 'productsForJs'))
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nom' => 'required|string|max:255',
            'prenoms' => 'required|string|max:255',
            'numero' => 'required|string|max:255',
            'lieu' => 'required|string|max:255',
            'autre' => 'nullable|string',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',
            'items.*.name' => 'required|string',
            'items.*.price' => 'required|integer',
            'items.*.qty' => 'required|integer',
            'total' => 'required|integer',
        ]);

        $order = StoreOrder::create($validated);

        return response()->json([
            'success' => true,
            'message' => 'Commande enregistrée avec succès',
            'order' => $order,
        ]);
    }

    public function trackProduct(Request $request, Product $product)
    {
        $product->increment('view_count');

        Visit::create([
            'session_id' => $request->session()->getId(),
            'ip' => $request->ip(),
            'path' => 'product/' . $product->id,
            'user_agent' => $request->userAgent(),
            'product_id' => $product->id,
            'visited_at' => now(),
        ]);

        return response()->json(['success' => true]);
    }
}
