<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\View\View;

class DonationAdminController extends Controller
{
    public function index(): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $donations = Donation::query()
            ->orderByDesc('created_at')
            ->paginate(20);

        $totalCount = Donation::count();
        $totalAmount = Donation::whereNotNull('amount')->sum('amount');
        $pendingCount = Donation::where('status', 'new')->count();
        $monthCount = Donation::where('created_at', '>=', now()->startOfMonth())->count();

        $stats = [
            'total_count' => $totalCount,
            'total_amount' => $totalAmount,
            'pending_count' => $pendingCount,
            'month_count' => $monthCount,
        ];

        return view('admin.donations.index', compact('donations', 'stats'));
    }

    public function show($id): View
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $donation = Donation::query()->where('id', $id)->firstOrFail();

        return view('admin.donations.show', compact('donation'));
    }
}
