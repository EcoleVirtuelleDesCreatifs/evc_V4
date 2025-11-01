<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatureCollaborateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatureCollaborateurController extends Controller
{
    /**
     * Affiche la liste des candidatures
     */
    public function index()
    {
        $candidatures = CandidatureCollaborateur::orderBy('created_at', 'desc')->get();
        
        $stats = [
            'total' => $candidatures->count(),
            'nouveau' => $candidatures->where('statut', 'nouveau')->count(),
            'en_cours' => $candidatures->where('statut', 'en_cours')->count(),
            'accepte' => $candidatures->where('statut', 'accepte')->count(),
            'refuse' => $candidatures->where('statut', 'refuse')->count(),
        ];
        
        return view('admin.candidatures.collaborateurs.index', compact('candidatures', 'stats'));
    }

    /**
     * Affiche les détails d'une candidature
     */
    public function show($id)
    {
        $candidature = CandidatureCollaborateur::findOrFail($id);
        return view('admin.candidatures.collaborateurs.show', compact('candidature'));
    }

    /**
     * Met à jour le statut d'une candidature
     */
    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:nouveau,en_cours,accepte,refuse',
            'notes_admin' => 'nullable|string|max:5000',
        ]);

        $candidature = CandidatureCollaborateur::findOrFail($id);
        $candidature->statut = $request->statut;
        $candidature->notes_admin = $request->notes_admin;
        $candidature->date_traitement = now();
        $candidature->save();

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }

    /**
     * Télécharge le CV d'un candidat
     */
    public function downloadCV($id)
    {
        $candidature = CandidatureCollaborateur::findOrFail($id);
        
        if (!Storage::disk('public')->exists($candidature->cv_path)) {
            return redirect()->back()->with('error', 'Le fichier CV n\'existe pas.');
        }

        return Storage::disk('public')->download($candidature->cv_path, 'CV_' . $candidature->nom . '_' . $candidature->prenom . '.pdf');
    }

    /**
     * Supprime une candidature
     */
    public function destroy($id)
    {
        $candidature = CandidatureCollaborateur::findOrFail($id);
        
        // Supprimer le CV du storage
        if (Storage::disk('public')->exists($candidature->cv_path)) {
            Storage::disk('public')->delete($candidature->cv_path);
        }
        
        $candidature->delete();
        
        return redirect()->route('admin.candidatures.collaborateurs.index')
            ->with('success', 'Candidature supprimée avec succès.');
    }
}
