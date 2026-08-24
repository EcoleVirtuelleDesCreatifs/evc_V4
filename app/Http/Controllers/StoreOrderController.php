<?php

namespace App\Http\Controllers;

use App\Models\StoreOrder;
use Illuminate\Http\Request;

class StoreOrderController extends Controller
{
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
