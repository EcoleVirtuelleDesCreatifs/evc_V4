<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\JsonResponse;

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

    public function sendReminder(Request $request, $id): JsonResponse
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403);
        }

        $donation = Donation::query()->where('id', $id)->firstOrFail();

        if (!$donation->email || !filter_var($donation->email, FILTER_VALIDATE_EMAIL)) {
            return response()->json([
                'success' => false,
                'message' => 'Email du donateur invalide.'
            ], 400);
        }

        $emailTo = $donation->email;

        try {
            Mail::send('emails.donation_reminder', ['donation' => $donation], function ($message) use ($emailTo, $donation) {
                $message->to($emailTo)
                    ->subject('Rappel - Finaliser votre don (EVC)');
            });

            Log::info('Email relance don envoyé', [
                'donation_id' => $donation->id,
                'email' => $emailTo,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Relance envoyée à ' . $emailTo,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erreur envoi relance don: ' . $e->getMessage(), [
                'donation_id' => $donation->id,
            ]);

            return response()->json([
                'success' => false,
                'message' => "Erreur lors de l'envoi de l'email",
            ], 500);
        }
    }
}
