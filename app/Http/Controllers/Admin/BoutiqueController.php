<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StoreOrder;

class BoutiqueController extends Controller
{
    /**
     * Display a listing of store orders.
     */
    public function index()
    {
        $orders = StoreOrder::orderBy('created_at', 'desc')->paginate(20);

        $stats = [
            'total' => StoreOrder::count(),
            'pending' => StoreOrder::where('status', 'pending')->count(),
            'completed' => StoreOrder::where('status', '!=', 'pending')->count(),
        ];

        return view('admin.boutique.index', compact('orders', 'stats'));
    }
}
