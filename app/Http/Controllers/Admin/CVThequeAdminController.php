<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVThequeProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;

class CVThequeAdminController extends Controller
{
    /**
     * Afficher tous les profils CV de la CVthèque
     */
    public function index(): View
    {
        // Récupérer tous les profils CV avec les informations utilisateur
        $profiles = CVThequeProfile::with('user')
            ->join('users', 'cvtheque_profiles.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'cvtheque_profiles.*',
                'users.email as user_email',
                'students.first_name',
                'students.last_name',
                'students.phone',
                'students.profile_photo',
                'students.program as formation',
                'students.specialization',
                'students.status as student_status'
            )
            ->orderBy('cvtheque_profiles.created_at', 'desc')
            ->get();

        // Calculer les statistiques
        $stats = [
            'total' => $profiles->count(),
            'complete' => $profiles->where('profile_completion_score', '>=', 80)->count(),
            'with_cv' => $profiles->whereNotNull('cv_file_path')->count(),
            'with_motivation' => $profiles->whereNotNull('motivation_letter_path')->count(),
            'with_portfolio' => $profiles->whereNotNull('portfolio_files')->count(),
            'with_pressbook' => $profiles->whereNotNull('pressbook_file_path')->count(),
            'with_report' => $profiles->whereNotNull('report_file_path')->count(),
        ];

        // Statistiques par formation
        $formationStats = [
            'design_graphique' => $profiles->where('formation', 'Design Graphique')->count(),
            'community_management' => $profiles->where('formation', 'Community Management')->count(),
            'gestion_informatique' => $profiles->where('formation', 'Gestion Informatique')->count(),
            'intelligence_artificielle' => $profiles->where('formation', 'Intelligence Artificielle')->count(),
        ];

        return view('admin.cvtheque.profiles', compact('profiles', 'stats', 'formationStats'));
    }

    /**
     * Afficher le détail d'un profil CV
     */
    public function show($id): View
    {
        $profile = CVThequeProfile::with('user')
            ->join('users', 'cvtheque_profiles.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'cvtheque_profiles.*',
                'users.email as user_email',
                'students.first_name',
                'students.last_name',
                'students.phone',
                'students.whatsapp',
                'students.profile_photo',
                'students.program as formation',
                'students.specialization',
                'students.status as student_status',
                'students.country',
                'students.city',
                'students.education_level',
                'students.last_diploma'
            )
            ->where('cvtheque_profiles.id', $id)
            ->firstOrFail();

        // Décoder les fichiers portfolio si présents
        $portfolioFiles = [];
        if ($profile->portfolio_files) {
            $portfolioFiles = is_string($profile->portfolio_files) 
                ? json_decode($profile->portfolio_files, true) 
                : $profile->portfolio_files;
        }

        return view('admin.cvtheque.show', compact('profile', 'portfolioFiles'));
    }

    /**
     * Télécharger un fichier du profil
     */
    public function downloadFile($id, $fileType)
    {
        $profile = CVThequeProfile::findOrFail($id);
        
        $filePath = null;
        $fileName = null;

        switch ($fileType) {
            case 'cv':
                $filePath = $profile->cv_file_path;
                $fileName = $profile->cv_file_name ?? 'cv.pdf';
                break;
            case 'motivation':
                $filePath = $profile->motivation_letter_path;
                $fileName = $profile->motivation_letter_name ?? 'lettre_motivation.pdf';
                break;
            case 'pressbook':
                $filePath = $profile->pressbook_file_path;
                $fileName = $profile->pressbook_file_name ?? 'pressbook.pdf';
                break;
            case 'report':
                $filePath = $profile->report_file_path;
                $fileName = $profile->report_file_name ?? 'rapport.pdf';
                break;
        }

        if ($filePath && Storage::disk('public')->exists($filePath)) {
            return Storage::disk('public')->download($filePath, $fileName);
        }

        return redirect()->back()->with('error', 'Fichier introuvable');
    }

    /**
     * Exporter tous les profils en CSV
     */
    public function export()
    {
        $profiles = CVThequeProfile::with('user')
            ->join('users', 'cvtheque_profiles.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select(
                'cvtheque_profiles.*',
                'users.email as user_email',
                'students.first_name',
                'students.last_name',
                'students.phone',
                'students.program as formation',
                'students.specialization'
            )
            ->get();

        $filename = 'cvtheque_profiles_' . date('Y-m-d_His') . '.csv';
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => "attachment; filename=\"$filename\"",
        ];

        $callback = function() use ($profiles) {
            $file = fopen('php://output', 'w');
            
            // En-têtes CSV
            fputcsv($file, [
                'ID',
                'Prénom',
                'Nom',
                'Email',
                'Téléphone',
                'Formation',
                'Spécialisation',
                'Titre Professionnel',
                'Années d\'expérience',
                'Disponibilité',
                'Score de complétion',
                'CV',
                'Lettre de motivation',
                'Portfolio',
                'Pressbook',
                'Rapport',
                'Date de création'
            ]);

            // Données
            foreach ($profiles as $profile) {
                fputcsv($file, [
                    $profile->id,
                    $profile->first_name,
                    $profile->last_name,
                    $profile->user_email,
                    $profile->phone,
                    $profile->formation,
                    $profile->specialization,
                    $profile->professional_title,
                    $profile->experience_years,
                    $profile->availability,
                    $profile->profile_completion_score . '%',
                    $profile->cv_file_path ? 'Oui' : 'Non',
                    $profile->motivation_letter_path ? 'Oui' : 'Non',
                    $profile->portfolio_files ? 'Oui' : 'Non',
                    $profile->pressbook_file_path ? 'Oui' : 'Non',
                    $profile->report_file_path ? 'Oui' : 'Non',
                    $profile->created_at->format('d/m/Y H:i')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
