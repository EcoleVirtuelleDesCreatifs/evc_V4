<?php

namespace App\Http\Controllers;

use App\Models\MediaUrl;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\StoreOrder;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function index()
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();
        $products = Product::with('category')->where('is_active', true)->orderBy('created_at', 'desc')->get();

        $productsForJs = $products->map(function (Product $product) {
            return [
                'id' => $product->id,
                'category' => $product->category->slug ?? '',
                'image_url' => MediaUrl::fromPath($product->image),
                'name' => $product->title,
                'desc' => $product->summary ?: $product->description,
                'price' => $product->price,
            ];
        });

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
}
