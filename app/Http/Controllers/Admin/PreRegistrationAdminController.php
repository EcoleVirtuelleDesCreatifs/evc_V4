<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class PreRegistrationAdminController extends Controller
{
    public function index(Request $request)
    {
        $query = PreRegistration::query()->latest();

        if ($search = $request->get('q')) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'like', "%{$search}%")
                  ->orWhere('prenom', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('whatsapp', 'like', "%{$search}%");
            });
        }

        if ($formation = $request->get('formation')) {
            $query->where('choix_formation', $formation);
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $pres = $query->paginate(20)->withQueryString();
        return view('admin.preregistrations.index', compact('pres'));
    }

    public function show($id)
    {
        $pre = PreRegistration::findOrFail($id);
        return view('admin.preregistrations.show', compact('pre'));
    }

    public function bulkStatus(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'integer|exists:pre_registrations,id',
            'action' => 'required|in:accepted,rejected,pending'
        ]);
        PreRegistration::whereIn('id', $request->ids)->update(['status' => $request->action]);
        return redirect()->route('admin.preinscriptions.index', $request->only(['q','formation','status']))
            ->with('success', 'Statut mis à jour pour les éléments sélectionnés.');
    }

    public function downloadPhoto($id)
    {
        $pre = PreRegistration::findOrFail($id);
        if (!$pre->photo) {
            return redirect()->back()->with('error', 'Aucune photo disponible.');
        }
        $path = storage_path('app/public/' . $pre->photo);
        if (!file_exists($path)) {
            return redirect()->back()->with('error', 'Fichier photo introuvable.');
        }
        return response()->download($path, 'photo_preinscription_'.$pre->id.'.'.pathinfo($path, PATHINFO_EXTENSION));
    }

    public function validateOne($id)
    {
        $pre = PreRegistration::findOrFail($id);
        $pre->status = 'accepted';
        $pre->save();
        return redirect()->back()->with('success', 'Pré-inscription validée.');
    }

    public function destroy($id)
    {
        $pre = PreRegistration::findOrFail($id);
        $pre->delete();
        return redirect()->route('admin.preinscriptions.index')->with('success', 'Pré-inscription supprimée.');
    }

    public function export(Request $request): StreamedResponse
    {
        $filename = 'preinscriptions_' . now()->format('Y-m-d_H-i-s') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $columns = [
            'id','nom','prenom','age','email','whatsapp','pays','niveau_etude','choix_formation','niveau_dans_formation','has_computer','has_smartphone','disponibilite','motivation','status','created_at'
        ];

        $search = $request->get('q');
        $formation = $request->get('formation');
        $status = $request->get('status');

        $callback = function() use ($columns, $search, $formation, $status) {
            $handle = fopen('php://output', 'w');
            // UTF-8 BOM for Excel compatibility
            fprintf($handle, chr(0xEF).chr(0xBB).chr(0xBF));
            fputcsv($handle, $columns);

            $base = PreRegistration::query();
            if ($search) {
                $base->where(function($q) use ($search) {
                    $q->where('nom', 'like', "%{$search}%")
                      ->orWhere('prenom', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%");
                });
            }
            if ($formation) {
                $base->where('choix_formation', $formation);
            }
            if ($status) {
                $base->where('status', $status);
            }

            $base->orderBy('id', 'desc')->chunk(500, function($chunk) use ($handle, $columns) {
                foreach ($chunk as $pre) {
                    $row = [];
                    foreach ($columns as $col) {
                        $val = $pre->{$col} ?? '';
                        if (is_bool($val)) { $val = $val ? '1' : '0'; }
                        $row[] = $val;
                    }
                    fputcsv($handle, $row);
                }
            });

            fclose($handle);
        };

        return response()->stream($callback, 200, $headers);
    }
}
