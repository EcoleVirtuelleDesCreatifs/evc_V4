<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DemandePartenariat;
use Illuminate\Http\Request;

class DemandePartenariatController extends Controller
{
    public function index()
    {
        $demandes = DemandePartenariat::orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $demandes->count(),
            'nouveau' => $demandes->where('statut', 'nouveau')->count(),
            'en_cours' => $demandes->where('statut', 'en_cours')->count(),
            'accepte' => $demandes->where('statut', 'accepte')->count(),
            'refuse' => $demandes->where('statut', 'refuse')->count(),
        ];

        return view('admin.demandes.partenariat.index', compact('demandes', 'stats'));
    }

    public function show($id)
    {
        $demande = DemandePartenariat::findOrFail($id);
        return view('admin.demandes.partenariat.show', compact('demande'));
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:nouveau,en_cours,accepte,refuse',
            'notes_admin' => 'nullable|string|max:5000',
        ]);

        $demande = DemandePartenariat::findOrFail($id);
        $demande->statut = $request->statut;
        $demande->notes_admin = $request->notes_admin;
        $demande->date_traitement = now();
        $demande->save();

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $demande = DemandePartenariat::findOrFail($id);
        $demande->delete();

        return redirect()->route('admin.demandes.partenariat.index')
            ->with('success', 'Demande supprimée avec succès.');
    }
}
