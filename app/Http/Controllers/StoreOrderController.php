<?php

namespace App\Http\Controllers;

use App\Models\MediaUrl;
use App\Models\Product;
use App\Models\ProductCategory;
use App\Models\PromoCode;
use App\Models\StoreOrder;
use App\Models\Visit;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
    public function index(Request $request)
    {
        $categories = ProductCategory::where('is_active', true)->orderBy('name')->get();

        $products = Product::with('category')
            ->where('is_active', true)
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->input('search') . '%');
            })
            ->when($request->filled('category'), function ($query) use ($request) {
                $query->whereHas('category', fn ($q) => $q->where('slug', $request->input('category')));
            })
            ->when($request->filled('min_price'), function ($query) use ($request) {
                $query->where('price', '>=', (int) $request->input('min_price'));
            })
            ->when($request->filled('max_price'), function ($query) use ($request) {
                $query->where('price', '<=', (int) $request->input('max_price'));
            })
            ->when($request->boolean('promo'), function ($query) {
                $query->whereNotNull('old_price')->whereColumn('old_price', '>', 'price');
            })
            ->when($request->input('stock') === 'in_stock', function ($query) {
                $query->where('stock', '>', 0);
            })
            ->when($request->input('stock') === 'available', function ($query) {
                $query->where('stock', '>', 10);
            })
            ->orderBy('created_at', 'desc')
            ->get();

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
                'old_price' => $product->old_price,
                'delivery_cost' => $product->delivery_cost,
                'stock' => $product->stock,
                'stock_status' => $product->stock_status,
                'is_promotion' => $product->is_promotion,
                'variants' => $product->variants ?? [],
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
            'items.*.delivery_cost' => 'nullable|integer',
            'items.*.qty' => 'required|integer',
            'promo_code' => 'nullable|string|max:50',
            'total' => 'required|integer',
        ]);

        $subtotal = collect($validated['items'])->sum(fn($item) => ($item['price'] ?? 0) * ($item['qty'] ?? 1));
        $deliveryCost = collect($validated['items'])->sum(fn($item) => ($item['delivery_cost'] ?? 0) * ($item['qty'] ?? 1));

        $discount = 0;
        $promo = null;
        if (!empty($validated['promo_code'])) {
            $promo = PromoCode::where('code', $validated['promo_code'])->first();
            if ($promo && $promo->isValid()) {
                $discount = $promo->calculateDiscount($subtotal);
                $promo->increment('used_count');
            }
        }

        $finalTotal = $subtotal + $deliveryCost - $discount;
        if ($validated['total'] != $finalTotal) {
            return response()->json([
                'success' => false,
                'message' => 'Le montant total ne correspond pas. Veuillez rafraîchir la page.',
            ], 422);
        }

        $validated['user_id'] = auth()->id();
        $validated['status'] = 'payment_pending';
        $validated['subtotal'] = $subtotal;
        $validated['delivery_cost'] = $deliveryCost;
        $validated['discount'] = $discount;
        $validated['promo_code'] = $promo ? $promo->code : null;
        $validated['final_total'] = $finalTotal;

        $order = StoreOrder::create($validated);
        $order->order_number = 'EVC-' . str_pad($order->id, 6, '0', STR_PAD_LEFT);
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Commande enregistrée avec succès',
            'order' => $order,
        ]);
    }

    public function checkPromo(Request $request)
    {
        $validated = $request->validate([
            'code' => 'required|string|max:50',
            'subtotal' => 'required|integer|min:0',
        ]);

        $promo = PromoCode::where('code', $validated['code'])->first();

        if (!$promo || !$promo->isValid()) {
            return response()->json([
                'success' => false,
                'message' => 'Code promo invalide ou expiré',
                'discount' => 0,
            ]);
        }

        return response()->json([
            'success' => true,
            'discount' => $promo->calculateDiscount($validated['subtotal']),
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

    public function myOrders()
    {
        $orders = StoreOrder::where('user_id', auth()->id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('evc-store.my-orders', compact('orders'));
    }
}
