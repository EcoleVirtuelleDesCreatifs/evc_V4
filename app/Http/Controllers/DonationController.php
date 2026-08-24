<?php

namespace App\Http\Controllers;

use App\Models\Donation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class DonationController extends Controller
{
    public function index()
    {
        return response()
            ->view('donation.index')
            ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }

    public function submit(Request $request)
    {
        try {
            $validated = $request->validate([
                'full_name' => 'required|string|max:255',
                'email' => 'required|email|max:255',
                'phone' => 'nullable|string|max:30',
                'amount' => 'nullable|numeric|min:1',
                'currency' => 'nullable|string|max:10',
                'payment_method' => 'nullable|string|max:80',
                'message' => 'nullable|string|max:5000',
                'consent' => 'accepted',
            ]);

            $validated['currency'] = $validated['currency'] ?? 'XOF';

            try {
                Donation::create([
                    'full_name' => $validated['full_name'],
                    'email' => $validated['email'],
                    'phone' => $validated['phone'] ?? null,
                    'amount' => $validated['amount'] ?? null,
                    'currency' => $validated['currency'] ?? 'XOF',
                    'payment_method' => $validated['payment_method'] ?? null,
                    'message' => $validated['message'] ?? null,
                    'status' => 'new',
                ]);
            } catch (\Throwable $e) {
                Log::error('Erreur enregistrement don en base: ' . $e->getMessage());
            }

            // Email admin
            try {
                $adminEmail = env('MAIL_ADMIN_ADDRESS') ?: env('MAIL_FROM_ADDRESS', 'admin@evc.ci');

                if ($adminEmail && filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
                    Mail::send('emails.donation_admin_notification', ['data' => $validated], function ($message) use ($adminEmail, $validated) {
                        $message->to($adminEmail)
                            ->subject('Nouveau don - ' . ($validated['full_name'] ?? 'Donateur'));
                    });
                } else {
                    Log::warning('Email admin non configuré. Don reçu mais email non envoyé.');
                }
            } catch (\Throwable $e) {
                Log::error('Erreur envoi email don (admin): ' . $e->getMessage());
            }

            // Email donateur
            try {
                Mail::send('emails.donation_confirmation', ['data' => $validated], function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->subject('Merci pour votre intention de don - EVC');
                });
            } catch (\Throwable $e) {
                Log::error('Erreur envoi email don (donateur): ' . $e->getMessage());
            }

            return redirect()->route('donation.index')->with('success', 'Merci ! Votre demande de don a bien été envoyée. Notre équipe vous contactera rapidement avec les modalités.');
        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Throwable $e) {
            Log::error('Erreur traitement don: ' . $e->getMessage());
            return redirect()->route('donation.index')->with('error', 'Une erreur est survenue lors de l\'envoi. Veuillez réessayer.');
        }
    }
}
