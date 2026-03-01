<?php

namespace App\Http\Controllers;

use App\Models\Partnership;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PartnershipController extends Controller
{
    public function show(string $slug)
    {
        if (!Schema::hasTable('partnerships')) {
            abort(404);
        }

        $partnership = Partnership::query()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('partnerships.show', [
            'partnership' => $partnership,
        ]);
    }
}
