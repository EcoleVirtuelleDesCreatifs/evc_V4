<?php

namespace App\Http\Controllers;

use App\Models\Evenement;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EvenementPublicController extends Controller
{
    /**
     * Afficher tous les événements (à venir et passés)
     */
    public function allEvenements()
    {
        $now = Carbon::now();
        
        // Événements à venir (incluant aujourd'hui)
        $evenementsAvenir = Evenement::published()
            ->where('event_date', '>=', $now->startOfDay())
            ->orderBy('event_date', 'asc')
            ->get();
        
        // Événements passés
        $evenementsPasses = Evenement::published()
            ->where('event_date', '<', $now->startOfDay())
            ->orderBy('event_date', 'desc')
            ->get();
        
        return view('evenements.all', compact('evenementsAvenir', 'evenementsPasses'));
    }
    
    /**
     * Afficher le détail d'un événement
     */
    public function show($slug)
    {
        $evenement = Evenement::published()
            ->where('slug', $slug)
            ->firstOrFail();
        
        // Incrémenter le compteur de vues
        $evenement->increment('views');
        
        return view('evenements.show', compact('evenement'));
    }
}
