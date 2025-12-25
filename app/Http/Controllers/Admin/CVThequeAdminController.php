<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CVThequeProfile;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Schema;

class CVThequeAdminController extends Controller
{
    /**
     * Afficher tous les profils CV de la CVthèque
     */
    public function index(): View
    {
        // Récupérer tous les étudiants avec leurs profils CVthèque
        $students = DB::table('students')
            ->leftJoin('users', 'students.user_id', '=', 'users.id')
            ->leftJoin('cvtheque_profiles', 'users.id', '=', 'cvtheque_profiles.user_id')
            ->select(
                'students.id',
                'students.user_id',
                'students.first_name',
                'students.last_name',
                'students.profile_photo',
                'students.phone',
                'students.program',
                'students.specialization',
                'students.status',
                'users.id as user_id_full',
                'users.email',
                'cvtheque_profiles.id as profile_id',
                'cvtheque_profiles.professional_title',
                'cvtheque_profiles.experience_years as years_experience',
                'cvtheque_profiles.cv_file_path as cv_file',
                'cvtheque_profiles.motivation_letter_path as motivation_file',
                'cvtheque_profiles.pressbook_file_path as pressbook_file',
                'cvtheque_profiles.report_file_path as rapport_file',
                'cvtheque_profiles.profile_completion_score',
                'cvtheque_profiles.availability',
                'cvtheque_profiles.created_at as profile_created_at'
            )
            ->where('students.status', 'active')
            ->orderBy('cvtheque_profiles.created_at', 'desc')
            ->get();

        // Grouper par formation
        $studentsByFormation = $students->groupBy('program');

        // Calculer les statistiques
        $totalStudents = $students->count();
        $withProfile = $students->whereNotNull('profile_id')->count();
        $withoutProfile = $totalStudents - $withProfile;

        // Calculer le taux de complétion moyen
        $avgCompletion = $students->whereNotNull('profile_completion_score')->avg('profile_completion_score');
        $visibleProfiles = $students->where('profile_completion_score', '>=', 75)->count();

        $stats = [
            'total_students' => $totalStudents,
            'with_profile' => $withProfile,
            'without_profile' => $withoutProfile,
            'avg_completion' => round($avgCompletion ?? 0),
            'visible_profiles' => $visibleProfiles,
            'with_cv' => $students->whereNotNull('cv_file')->count(),
            'with_motivation' => $students->whereNotNull('motivation_file')->count(),
            'with_pressbook' => $students->whereNotNull('pressbook_file')->count(),
            'with_report' => $students->whereNotNull('rapport_file')->count(),
        ];

        return view('admin.cvtheque.index', compact('students', 'studentsByFormation', 'stats'));
    }

    /**
     * Afficher le détail d'un profil CV
     */
    public function show($id): View
    {
        $select = [
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
        ];

        if (Schema::hasColumn('students', 'education_level')) {
            $select[] = 'students.education_level';
        } else {
            $select[] = DB::raw('NULL as education_level');
        }

        if (Schema::hasColumn('students', 'last_diploma')) {
            $select[] = 'students.last_diploma';
        } else {
            $select[] = DB::raw('NULL as last_diploma');
        }

        $profile = CVThequeProfile::with('user')
            ->join('users', 'cvtheque_profiles.user_id', '=', 'users.id')
            ->leftJoin('students', 'users.id', '=', 'students.user_id')
            ->select($select)
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
