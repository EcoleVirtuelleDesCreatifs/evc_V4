<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CandidatureFormateur;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CandidatureFormateurController extends Controller
{
    public function index()
    {
        $candidatures = CandidatureFormateur::orderBy('created_at', 'desc')->get();

        $stats = [
            'total' => $candidatures->count(),
            'nouveau' => $candidatures->where('statut', 'nouveau')->count(),
            'en_cours' => $candidatures->where('statut', 'en_cours')->count(),
            'accepte' => $candidatures->where('statut', 'accepte')->count(),
            'refuse' => $candidatures->where('statut', 'refuse')->count(),
        ];

        return view('admin.candidatures.formateurs.index', compact('candidatures', 'stats'));
    }

    public function show($id)
    {
        $candidature = CandidatureFormateur::findOrFail($id);
        return view('admin.candidatures.formateurs.show', compact('candidature'));
    }

    public function updateStatut(Request $request, $id)
    {
        $request->validate([
            'statut' => 'required|in:nouveau,en_cours,accepte,refuse',
            'notes_admin' => 'nullable|string|max:5000',
        ]);

        $candidature = CandidatureFormateur::findOrFail($id);
        $candidature->statut = $request->statut;
        $candidature->notes_admin = $request->notes_admin;
        $candidature->date_traitement = now();
        $candidature->save();

        return redirect()->back()->with('success', 'Statut mis à jour avec succès.');
    }

    public function downloadCV($id)
    {
        $candidature = CandidatureFormateur::findOrFail($id);

        if (!Storage::disk('public')->exists($candidature->cv_path)) {
            return redirect()->back()->with('error', 'Le fichier CV n\'existe pas.');
        }

        return Storage::disk('public')->download($candidature->cv_path, 'CV_' . $candidature->nom . '_' . $candidature->prenom . '.pdf');
    }

    public function destroy($id)
    {
        $candidature = CandidatureFormateur::findOrFail($id);

        if (Storage::disk('public')->exists($candidature->cv_path)) {
            Storage::disk('public')->delete($candidature->cv_path);
        }

        $candidature->delete();

        return redirect()->route('admin.candidatures.formateurs.index')
            ->with('success', 'Candidature supprimée avec succès.');
    }
}
