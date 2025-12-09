<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\WebtvSubscriber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class WebtvAdminController extends Controller
{
    /**
     * Afficher la liste des abonnés WebTV
     */
    public function index()
    {
        // Statistiques globales
        $stats = [
            'total' => WebtvSubscriber::count(),
            'actifs' => WebtvSubscriber::where('is_active', true)->count(),
            'inactifs' => WebtvSubscriber::where('is_active', false)->count(),
            'verifies' => WebtvSubscriber::whereNotNull('verified_at')->count(),
            'non_verifies' => WebtvSubscriber::whereNull('verified_at')->count(),
        ];

        // Récupérer tous les abonnés avec tri
        $subscribers = WebtvSubscriber::orderBy('created_at', 'desc')->get();

        // Abonnés par mois (6 derniers mois)
        $monthlyStats = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $monthlyStats[] = [
                'month' => $date->format('M Y'),
                'count' => WebtvSubscriber::whereYear('created_at', $date->year)
                    ->whereMonth('created_at', $date->month)
                    ->count()
            ];
        }

        return view('admin.webtv.subscribers', compact('stats', 'subscribers', 'monthlyStats'));
    }

    /**
     * Afficher les détails d'un abonné
     */
    public function show($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);
        return view('admin.webtv.show', compact('subscriber'));
    }

    /**
     * Désactiver un abonné
     */
    public function deactivate($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);
        $subscriber->update(['is_active' => false]);

        return redirect()->back()->with('success', 'L\'abonné a été désactivé avec succès.');
    }

    /**
     * Activer un abonné
     */
    public function activate($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);
        $subscriber->update(['is_active' => true]);

        return redirect()->back()->with('success', 'L\'abonné a été activé avec succès.');
    }

    /**
     * Supprimer un abonné
     */
    public function destroy($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);
        $subscriber->delete();

        return redirect()->route('admin.webtv.subscribers')->with('success', 'L\'abonné a été supprimé avec succès.');
    }

    /**
     * Marquer un abonné comme vérifié manuellement
     */
    public function verify($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);

        if (!$subscriber->isVerified()) {
            $subscriber->markAsVerified();
            return redirect()->back()->with('success', 'L\'abonné a été vérifié avec succès.');
        }

        return redirect()->back()->with('info', 'Cet abonné est déjà vérifié.');
    }

    /**
     * Envoyer un email de test à un abonné
     */
    public function sendTestEmail($id)
    {
        $subscriber = WebtvSubscriber::findOrFail($id);

        try {
            Mail::send('emails.webtv_test_notification', [
                'subscriber' => $subscriber
            ], function ($message) use ($subscriber) {
                $message->to($subscriber->email)
                    ->subject('Test de notification WebTV - EVC');
            });

            return redirect()->back()->with('success', 'Email de test envoyé avec succès.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Erreur lors de l\'envoi de l\'email : ' . $e->getMessage());
        }
    }

    /**
     * Exporter la liste des abonnés en CSV
     */
    public function export()
    {
        $subscribers = WebtvSubscriber::orderBy('created_at', 'desc')->get();

        $filename = 'abonnes_webtv_' . now()->format('Y-m-d_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
            'Pragma' => 'no-cache',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Expires' => '0'
        ];

        $callback = function() use ($subscribers) {
            $file = fopen('php://output', 'w');

            // BOM UTF-8 pour Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // En-têtes
            fputcsv($file, ['ID', 'Nom', 'Email', 'Statut', 'Vérifié', 'Date d\'inscription', 'Dernière notification'], ';');

            // Données
            foreach ($subscribers as $subscriber) {
                fputcsv($file, [
                    $subscriber->id,
                    $subscriber->name ?? 'N/A',
                    $subscriber->email,
                    $subscriber->is_active ? 'Actif' : 'Inactif',
                    $subscriber->verified_at ? 'Oui' : 'Non',
                    $subscriber->created_at->format('d/m/Y H:i'),
                    $subscriber->last_notified_at ? $subscriber->last_notified_at->format('d/m/Y H:i') : 'Jamais'
                ], ';');
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Envoyer une notification à tous les abonnés actifs et vérifiés
     */
    public function notifyAll(Request $request)
    {
        $request->validate([
            'subject' => 'required|string|max:255',
            'message' => 'required|string',
        ]);

        $subscribers = WebtvSubscriber::active()->verified()->get();
        $sentCount = 0;
        $failedCount = 0;

        foreach ($subscribers as $subscriber) {
            try {
                Mail::send('emails.webtv_broadcast', [
                    'subscriber' => $subscriber,
                    'customMessage' => $request->message
                ], function ($message) use ($subscriber, $request) {
                    $message->to($subscriber->email)
                        ->subject($request->subject);
                });

                $subscriber->update(['last_notified_at' => now()]);
                $sentCount++;
            } catch (\Exception $e) {
                $failedCount++;
                \Log::error('Erreur envoi email WebTV à ' . $subscriber->email . ': ' . $e->getMessage());
            }
        }

        $message = "$sentCount notification(s) envoyée(s) avec succès.";
        if ($failedCount > 0) {
            $message .= " $failedCount email(s) en échec.";
        }

        return redirect()->back()->with('success', $message);
    }
}
