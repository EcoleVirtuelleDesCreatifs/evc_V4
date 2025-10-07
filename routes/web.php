<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVThequeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LibraryCategoryController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\SimpleProjectController;
use App\Http\Controllers\Admin\PreRegistrationAdminController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\AdminStatisticsDetailController;
use App\Http\Controllers\StudentConfirmationController;
use Illuminate\Support\Facades\Route;



// Page d'accueil et pré-inscription
Route::get('/', [HomepageController::class, 'index'])->name('homepage');
Route::get('/preinscription', function () {
    return view('preinscription.index');
})->name('preinscription.start');
Route::post('/pre-registration', [HomepageController::class, 'store'])->name('pre-registration.store');
Route::post('/candidature', [HomepageController::class, 'candidatureStore'])->name('candidature.store');
Route::get('/webtv', [HomepageController::class, 'webtv'])->name('webtv');
Route::get('/presentation', [HomepageController::class, 'presentation'])->name('presentation');
Route::get('/formations', [HomepageController::class, 'formations'])->name('formations');
Route::get('/travaux-etudiants', [HomepageController::class, 'travaux'])->name('travaux');
Route::get('/laureats', [HomepageController::class, 'laureats'])->name('laureats');

// Routes d'authentification

Route::get('/auth/evc/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/auth/evc/login', [AuthController::class, 'login']);
Route::get('/auth/evc/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/auth/evc/register', [AuthController::class, 'register']);

// Route d'inscription sans CSRF (solution de contournement)
Route::post('/auth/evc/register-no-csrf', [AuthController::class, 'registerNoCsrf']);

Route::post('/auth/evc/logout', [AuthController::class, 'logout'])->name('logout');

// Route de chargement après connexion
Route::get('/auth/evc/loading', [AuthController::class, 'showLoadingPage'])->name('auth.loading');

// Routes de récupération de mot de passe
Route::get('/auth/evc/forgot-password', [PasswordResetController::class, 'showResetRequestForm'])->name('password.request');
Route::post('/auth/evc/forgot-password', [PasswordResetController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/auth/evc/reset-password/{token}', [PasswordResetController::class, 'showResetForm'])->name('password.reset');
Route::post('/auth/evc/reset-password', [PasswordResetController::class, 'resetPassword'])->name('password.update');

// Debug: envoyer un e-mail de test (local uniquement)
Route::get('/debug/mail', function () {
    if (!app()->environment('local')) {
        abort(404);
    }
    $to = request('to') ?? config('mail.admin_address') ?? config('mail.from.address');
    if (!$to) {
        return response('MAIL_ADMIN_ADDRESS ou MAIL_FROM_ADDRESS non configuré.', 400);
    }
    \Illuminate\Support\Facades\Mail::raw(
        "Ceci est un e-mail de test SMTP de l’EVC. Si vous le recevez, la configuration est OK.",
        function ($message) use ($to) {
            $message->to($to)->subject('EVC – Test e-mail SMTP');
        }
    );
    return "E-mail de test envoyé à: {$to}";
})->name('debug.mail');

Route::get('/debug/mail/ui', function () {
    if (!app()->environment('local')) {
        abort(404);
    }
    return view('debug.mail');
})->name('debug.mail.ui');

// Routes de confirmation d'inscription étudiant
Route::get('/student/confirm-registration/{token}', [StudentConfirmationController::class, 'showConfirmationForm'])->name('student.confirm-registration');
Route::post('/student/confirm-registration/{token}', [StudentConfirmationController::class, 'confirmRegistration'])->name('student.confirm-registration.process');

// Route de connexion étudiant (alias pour la route login existante)
Route::get('/student/login', [AuthController::class, 'studentLoginRedirect'])->name('student.login');

// Route générique de redirection automatique selon la formation de l'utilisateur
// Route dashboard désactivée - redirection vers espace étudiant personnalisé
Route::get('/dashboard', [DashboardController::class, 'redirectBasedOnFormation'])->name('dashboard');

// Admin: renvoyer le lien de création de compte au candidat
Route::post('/evc/app/admin/preinscriptions/{id}/resend-link', [\App\Http\Controllers\Admin\PreRegistrationAdminController::class, 'resendRegistrationLink'])->name('admin.preinscriptions.resend-link');

// Admin: liste des étudiants par formation
Route::get('/evc/app/admin/etudiants/{formation}', [\App\Http\Controllers\Admin\StudentAdminController::class, 'listByFormation'])
    ->whereIn('formation', ['design-graphique','community-manager','intelligence-artificielle','gestion-informatique'])
    ->name('admin.students.by-formation');

// (Nettoyage) On conserve seulement les routes admin.students.*

Route::get('/evc/app/admin/students/{id}/edit', [\App\Http\Controllers\Admin\StudentAdminController::class, 'edit'])
    ->whereNumber('id')
    ->name('admin.students.edit');

Route::post('/evc/app/admin/students/{id}/toggle-status', [\App\Http\Controllers\Admin\StudentAdminController::class, 'toggleStatus'])
    ->whereNumber('id')
    ->name('admin.students.toggle-status');

// Route pour la page de compte désactivé (accessible UNIQUEMENT avec compte désactivé)
Route::get('/compte-desactive', function() {
    // Vérifier si l'utilisateur est authentifié
    if (!Auth::check()) {
        return redirect()->route('login');
    }
    
    $user = Auth::user();
    
    // Vérifier le statut dans la table students
    if (Schema::hasTable('students')) {
        $student = DB::table('students')
            ->where('email', $user->email)
            ->first();
        
        // Si l'étudiant existe et est ACTIF, rediriger vers le dashboard
        if ($student && $student->status === 'active') {
            // Déterminer la formation pour rediriger vers le bon dashboard
            $formationMap = [
                'Design Graphique' => 'dashboard.design-graphique',
                'Community Management' => 'dashboard.community-manager',
                'Intelligence Artificielle' => 'dashboard.intelligence-artificielle',
                'Gestion Informatique' => 'dashboard.gestion-informatique',
            ];
            
            $dashboardRoute = $formationMap[$student->program] ?? 'dashboard.design-graphique';
            return redirect()->route($dashboardRoute)->with('success', 'Votre compte est actif ! Bienvenue.');
        }
        
        // Si inactif, afficher la page de désactivation
        if ($student && $student->status === 'inactive') {
            return view('account-deactivated', [
                'reason' => session('reason', $student->deactivation_reason ?? ''),
                'deactivatedAt' => session('deactivatedAt', $student->deactivated_at ?? null)
            ]);
        }
    }
    
    // Par défaut, rediriger vers le dashboard
    return redirect()->route('dashboard.design-graphique');
})->middleware('auth')->name('account.deactivated');

// Routes d'espaces étudiants personnalisées selon la formation (PROTÉGÉES PAR AUTH + VÉRIFICATION COMPTE ACTIF)
Route::middleware(['auth', 'student.active'])->group(function () {
    Route::get('/evc/compte/design-graphique/espace-etudiant', [DashboardController::class, 'designGraphique'])->name('dashboard.design-graphique');
    Route::get('/evc/compte/community-manager/espace-etudiant', [DashboardController::class, 'communityManagement'])->name('dashboard.community-manager');
    Route::get('/evc/compte/intelligence-artificielle/espace-etudiant', [DashboardController::class, 'intelligenceArtificielle'])->name('dashboard.intelligence-artificielle');
    Route::get('/evc/compte/gestion-informatique/espace-etudiant', [DashboardController::class, 'gestionInformatique'])->name('dashboard.gestion-informatique');
});

// Groupe de routes pour Design Graphique avec préfixe commun (PROTÉGÉ PAR AUTH + VÉRIFICATION COMPTE ACTIF)
Route::prefix('/evc/compte/design-graphique')->name('design-graphique.')->middleware(['auth', 'student.active'])->group(function () {
    // Profil - Structure: /evc/compte/design-graphique/profil/{action}
    Route::get('/profil/editer/{id?}', [DashboardController::class, 'editProfile'])->name('profil.editer');
    Route::post('/profil/editer/{id?}', [DashboardController::class, 'updateProfile'])->name('profil.update');

    // CVThèque - Structure: /evc/compte/design-graphique/cvtheque/{action}
    Route::get('/cvtheque/index', [CVThequeController::class, 'index'])->name('cvtheque.index');
    Route::get('/cvtheque/historique', [CVThequeController::class, 'historique'])->name('cvtheque.historique');
    Route::post('/cvtheque/update-profile', [CVThequeController::class, 'updateProfile'])->name('cvtheque.update-profile');

    // API Routes pour la gestion des documents - Architecture structurée
    Route::delete('/cvtheque/documents/delete', [CVThequeController::class, 'deleteDocument'])->name('cvtheque.documents.delete');
    Route::get('/cvtheque/documents/export', [CVThequeController::class, 'exportDocuments'])->name('cvtheque.documents.export');
    Route::get('/cvtheque/preview', [CVThequeController::class, 'preview'])->name('cvtheque.preview');
    Route::post('/cvtheque/upload-cv', [CVThequeController::class, 'uploadCV'])->name('cvtheque.upload-cv');
    Route::post('/cvtheque/upload-motivation', [CVThequeController::class, 'uploadMotivation'])->name('cvtheque.upload-motivation');
    Route::post('/cvtheque/upload-realisation', [CVThequeController::class, 'uploadRealisations'])->name('cvtheque.upload-realisation');
    Route::post('/cvtheque/upload-pressbook', [CVThequeController::class, 'uploadPressbook'])->name('cvtheque.upload-pressbook');
    Route::post('/cvtheque/upload-rapport', [CVThequeController::class, 'uploadRapport'])->name('cvtheque.upload-rapport');

    // TP (Travaux Pratiques) - Structure: /evc/compte/design-graphique/tp/{action}
    Route::get('/tp/index', [DashboardController::class, 'listTP'])->name('tp.index');
    Route::get('/tp/tous', [DashboardController::class, 'showAllTP'])->name('tp.tous');
    Route::get('/tp/voir/{id}', [DashboardController::class, 'viewTP'])->name('tp.voir');
    Route::get('/tp/ajouter', [DashboardController::class, 'createTP'])->name('tp.ajouter');
    Route::post('/tp/ajouter', [DashboardController::class, 'storeTP'])->name('tp.store');
    Route::get('/tp/modifier/{id}', [DashboardController::class, 'editTP'])->name('tp.modifier');
    // Routes pour les projets de design graphique
    Route::get('/projets', [DashboardController::class, 'projets'])->name('projets.index');
    Route::post('/projets', [App\Http\Controllers\DesignProjectController::class, 'store'])->name('projets.store');
    Route::get('/projets/stats/json', [App\Http\Controllers\DesignProjectController::class, 'getStats'])->name('projets.stats');
    Route::get('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'show'])->name('projets.show');
    Route::get('/projets/{id}/edit', [App\Http\Controllers\DesignProjectController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projectId}/files/{fileId}', [App\Http\Controllers\DesignProjectController::class, 'removeFile'])->name('projets.removeFile');
    Route::patch('/projets/{id}/status', [App\Http\Controllers\DesignProjectController::class, 'updateStatus'])->name('projets.updateStatus');
    Route::delete('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'destroy'])->name('projets.destroy');

    // Routes pour les listes de projets par catégorie
    Route::get('/projets/solo/liste', [App\Http\Controllers\DesignProjectController::class, 'soloProjects'])->name('projets.solo');
    Route::get('/projets/groupe/liste', [App\Http\Controllers\DesignProjectController::class, 'groupProjects'])->name('projets.groupe');
    Route::get('/projets/tous/liste', [App\Http\Controllers\DesignProjectController::class, 'allProjects'])->name('projets.tous');
    Route::put('/tp/modifier/{id}', [DashboardController::class, 'updateProject'])->name('tp.update');
    Route::post('/tp/modifier/{id}/images', [DashboardController::class, 'updateProjectWithImages'])->name('tp.update.images');
    Route::delete('/tp/supprimer/{id}', [DashboardController::class, 'deleteProject'])->name('tp.supprimer');

    // TP Version Simple (pour debug/test)
    Route::get('/tp/ajouter-simple', [DashboardController::class, 'ajouterSimpleTP'])->name('tp.ajouter-simple');

    // Test ultra-simple pour diagnostic
    Route::get('/tp/test-simple', [DashboardController::class, 'testSimpleTP'])->name('tp.test-simple');

    Route::post('/tp/test-simple', [DashboardController::class, 'storeTestSimpleTP'])->name('tp.test-simple.store');

    // Diagnostic TP - 100% Laravel (pour debug/test)
    Route::get('/diagnostic', [DashboardController::class, 'diagnosticTP'])->name('diagnostic.tp');

    // Programme - Structure: /evc/compte/design-graphique/programme/{action}
    Route::get('/programme/index', [DashboardController::class, 'programmeIndex'])->name('programme.index');

    // Paiements - Structure: /evc/compte/design-graphique/paiements/{action}
    Route::get('/paiements/index', [DashboardController::class, 'paiementsIndex'])->name('paiements.index');

    // Fin de formation - Structure: /evc/compte/design-graphique/fin-formation/{action}
    Route::get('/fin-formation/index', [DashboardController::class, 'finFormationIndex'])->name('fin-formation.index');

    // Paramètres - Structure: /evc/compte/design-graphique/parametres/{action}
    Route::get('/parametres/index', [App\Http\Controllers\ProfileController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [App\Http\Controllers\ProfileController::class, 'update'])->name('parametres.update');

    // Communauté - Structure: /evc/compte/design-graphique/communaute/{action}
    Route::get('/communaute/index', [DashboardController::class, 'communauteIndex'])->name('communaute.index');

    // Formations - Structure: /evc/compte/design-graphique/formations/{action}
    Route::get('/formations/index', [DashboardController::class, 'formationsIndex'])->name('formations.index');
    Route::get('/formations/category/{category}', [DashboardController::class, 'formationsCategory'])->name('formations.category');
    Route::get('/formations/show/{id}', [DashboardController::class, 'formationsShow'])->name('formations.show');
    Route::get('/formations/download/{id}', [DashboardController::class, 'formationsDownload'])->name('formations.download');
    Route::get('/formations/download-all/{id}', [DashboardController::class, 'formationsDownloadAll'])->name('formations.download-all');

    // Projets - Structure: /evc/compte/design-graphique/projets/{action}
    // Routes déplacées vers les lignes 112-117 pour éviter les doublons

    // Events - Structure: /evc/compte/design-graphique/events/{action}
    Route::get('/events/index', [DashboardController::class, 'eventsIndex'])->name('events.index');

    // Actualités - Structure: /evc/compte/design-graphique/actualites/{action}
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');

    // Documents - Structure: /evc/compte/design-graphique/documents/{action}
    Route::get('/documents/index', [DashboardController::class, 'documentsIndex'])->name('documents.index');
});

// Routes Administration - Espace sécurisé pour les administrateurs
Route::prefix('/evc/app/admin')->name('admin.')->middleware('admin.errors')->group(function () {
    // Authentification Admin (accessible seulement si pas connecté)
    Route::middleware('admin.guest')->group(function () {
        Route::get('/login', [AdminAuthController::class, 'showLoginForm'])->name('login');
        Route::post('/login', [AdminAuthController::class, 'login']);
    });
    
    // Routes protégées (nécessitent une authentification admin)
    Route::middleware('admin.auth')->group(function () {
        Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');
        Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
        
        // Pages statistiques spécifiques avec contrôleur dédié (DOIVENT être avant la route générique)
        Route::get('/statistiques/total-students', [AdminStatisticsController::class, 'totalStudents'])->name('statistics.total-students');
        Route::get('/statistiques/total-formations', [AdminStatisticsController::class, 'totalFormations'])->name('statistics.total-formations');
        Route::get('/statistiques/total-projects', [AdminStatisticsController::class, 'totalProjects'])->name('statistics.total-projects');
        
        // Pages de détails des statistiques (route générique en dernier)
        Route::get('/statistiques/{statType}', [AdminStatisticsDetailController::class, 'show'])->name('statistics.detail');
        
        // Routes pour les actions des pages de détails statistiques
        // Documents - Actions de validation
        Route::post('/documents/validate-batch', [AdminDashboardController::class, 'validateDocumentsBatch'])->name('documents.validate-batch');
        Route::post('/documents/reject-batch', [AdminDashboardController::class, 'rejectDocumentsBatch'])->name('documents.reject-batch');
        Route::post('/documents/validate/{id}', [AdminDashboardController::class, 'validateDocument'])->name('documents.validate');
        Route::post('/documents/reject/{id}', [AdminDashboardController::class, 'rejectDocument'])->name('documents.reject');
        Route::get('/documents/preview/{id}', [AdminDashboardController::class, 'previewDocument'])->name('documents.preview');
        Route::get('/documents/download/{id}', [AdminDashboardController::class, 'downloadDocument'])->name('documents.download');
        Route::get('/documents/validation', [AdminDashboardController::class, 'documentsValidation'])->name('documents.validation');
        
        // Étudiants - Actions rapides
        Route::post('/students/add', [AdminDashboardController::class, 'addStudent'])->name('students.add');
        Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/create', [AdminDashboardController::class, 'showAddStudent'])->name('students.create');
        Route::post('/students/bulk-email', [AdminDashboardController::class, 'sendBulkEmail'])->name('students.bulk-email');
        Route::get('/students/export', [AdminDashboardController::class, 'exportStudents'])->name('students.export');
        Route::post('/students/suspend/{id}', [AdminDashboardController::class, 'suspendStudent'])->name('students.suspend');
        Route::post('/students/activate/{id}', [AdminDashboardController::class, 'activateStudent'])->name('students.activate');
        
        // Formations - Gestion
        Route::post('/formations/add', [AdminDashboardController::class, 'addFormation'])->name('formations.add');
        Route::get('/formations/create', [AdminDashboardController::class, 'create'])->name('formations.create');
        // Gestion des Catégories de Formations (Nomenclature Corrigée)
        Route::get('/formations/categories', [AdminDashboardController::class, 'categoriesIndex'])->name('formations.categories.index');
        Route::get('/formations/categories/create', [AdminDashboardController::class, 'createCategory'])->name('formations.categories.create');
        Route::post('/formations/categories', [AdminDashboardController::class, 'storeCategory'])->name('formations.categories.store');
        Route::get('/formations/categories/{id}/edit', [AdminDashboardController::class, 'editCategory'])->name('formations.categories.edit');
        Route::put('/formations/categories/{id}', [AdminDashboardController::class, 'updateCategory'])->name('formations.categories.update');
        Route::delete('/formations/categories/{id}', [AdminDashboardController::class, 'deleteCategory'])->name('formations.categories.delete');
        // API pour récupérer les étudiants par module
        Route::get('/api/students-by-module', [AdminDashboardController::class, 'getStudentsByModule'])->name('api.students-by-module');
        
        Route::post('/formations', [AdminDashboardController::class, 'store'])->name('formations.store');
        Route::get('/formations/{formation}', [AdminDashboardController::class, 'show'])->name('formations.show');
        Route::get('/formations/{formation}/edit', [AdminDashboardController::class, 'edit'])->name('formations.edit');
        Route::put('/formations/{formation}', [AdminDashboardController::class, 'update'])->name('formations.update');
        Route::delete('/formations/{formation}', [AdminDashboardController::class, 'destroy'])->name('formations.destroy');
        Route::patch('/formations/{formation}/toggle-status', [AdminDashboardController::class, 'toggleStatus'])->name('formations.toggleStatus');
        Route::post('/formations/validate', [AdminDashboardController::class, 'validateFormation'])->name('formations.validate');
        Route::delete('/formations/delete/{id}', [AdminDashboardController::class, 'deleteFormation'])->name('formations.delete');
        Route::get('/formations/export', [AdminDashboardController::class, 'exportFormations'])->name('formations.export');
        
        // Projets - Actions de validation
        Route::post('/projects/validate-batch', [AdminDashboardController::class, 'validateProjectsBatch'])->name('projects.validate-batch');
        Route::post('/projects/reject-batch', [AdminDashboardController::class, 'rejectProjectsBatch'])->name('projects.reject-batch');
        Route::post('/projects/validate/{id}', [AdminDashboardController::class, 'validateProject'])->name('projects.validate');
        Route::post('/projects/reject/{id}', [AdminDashboardController::class, 'rejectProject'])->name('projects.reject');
        Route::get('/projects/view/{id}', [AdminDashboardController::class, 'viewProject'])->name('projects.view');

        // Pré-inscriptions - Liste & Export
        Route::get('/preinscriptions', [PreRegistrationAdminController::class, 'index'])->name('preinscriptions.index');
        Route::get('/preinscriptions/export', [PreRegistrationAdminController::class, 'export'])->name('preinscriptions.export');
        Route::get('/preinscriptions/{id}', [PreRegistrationAdminController::class, 'show'])->name('preinscriptions.show');
        Route::post('/preinscriptions/bulk-status', [PreRegistrationAdminController::class, 'bulkStatus'])->name('preinscriptions.bulk-status');
        Route::get('/preinscriptions/{id}/download-photo', [PreRegistrationAdminController::class, 'downloadPhoto'])->name('preinscriptions.download-photo');
        Route::post('/preinscriptions/{id}/validate', [PreRegistrationAdminController::class, 'validateOne'])->name('preinscriptions.validate');
        Route::delete('/preinscriptions/{id}', [PreRegistrationAdminController::class, 'destroy'])->name('preinscriptions.destroy');

        // Test Mailtrap (admin only)
        Route::get('/test-mail', function () {
            $to = config('mail.admin_address') ?? config('mail.from.address');
            Mail::raw("Ceci est un email de test Mailtrap depuis EVC. Si vous le voyez dans Mailtrap, la configuration SMTP fonctionne.", function ($message) use ($to) {
                $message->to($to)->subject('Test Mailtrap - EVC');
            });
            return response()->json(['status' => 'ok', 'to' => $to]);
        })->name('mail.test');
        Route::get('/projects/edit/{id}', [AdminDashboardController::class, 'editProject'])->name('projects.edit');
        Route::put('/projects/update/{id}', [AdminDashboardController::class, 'updateProject'])->name('projects.update');
        Route::get('/projects/{id}/images', [AdminDashboardController::class, 'getProjectImages'])->name('projects.images');
        Route::get('/projects/validate/{id}', [AdminDashboardController::class, 'showValidateProject'])->name('projects.validate.show');
        Route::get('/projects/export', [AdminDashboardController::class, 'exportProjects'])->name('projects.export');
        Route::delete('/projects/delete/{id}', [AdminDashboardController::class, 'deleteProject'])->name('projects.delete');
        
        // Design Projects - Gestion des projets design
        Route::get('/design-projects', [AdminDashboardController::class, 'designProjects'])->name('design-projects.index');
        Route::get('/design-projects/view/{id}', [AdminDashboardController::class, 'viewDesignProject'])->name('design-projects.view');
        Route::get('/design-projects/edit/{id}', [AdminDashboardController::class, 'editDesignProject'])->name('design-projects.edit');
        Route::post('/design-projects/edit/{id}', [AdminDashboardController::class, 'editDesignProject'])->name('design-projects.update'); // Same method handles POST for updates
        Route::post('/design-projects/validate/{id}', [AdminDashboardController::class, 'validateDesignProject'])->name('design-projects.validate');
        Route::delete('/design-projects/delete/{id}', [AdminDashboardController::class, 'deleteDesignProject'])->name('design-projects.delete');
        
        // Route sécurisée pour servir les fichiers des projets design
        Route::get('/design-projects/file/{fileId}', [AdminDashboardController::class, 'serveDesignProjectFile'])->name('design-projects.file');
        
        // TP - Actions de validation
        Route::post('/tp/validate-batch', [AdminDashboardController::class, 'validateTpBatch'])->name('tp.validate-batch');
        Route::post('/tp/reject-batch', [AdminDashboardController::class, 'rejectTpBatch'])->name('tp.reject-batch');
        Route::post('/tp/validate/{id}', [AdminDashboardController::class, 'validateTp'])->name('tp.validate');
        Route::post('/tp/reject/{id}', [AdminDashboardController::class, 'rejectTp'])->name('tp.reject');
        Route::get('/tp/view/{id}', [AdminDashboardController::class, 'viewTp'])->name('tp.view');
        Route::get('/tp/export', [AdminDashboardController::class, 'exportTp'])->name('tp.export');
        
        // Articles - Gestion de contenu
        Route::post('/articles/add', [AdminDashboardController::class, 'addArticle'])->name('articles.add');
        Route::post('/articles/publish/{id}', [AdminDashboardController::class, 'publishArticle'])->name('articles.publish');
        Route::post('/articles/unpublish/{id}', [AdminDashboardController::class, 'unpublishArticle'])->name('articles.unpublish');
        Route::delete('/articles/delete/{id}', [AdminDashboardController::class, 'deleteArticle'])->name('articles.delete');
        Route::get('/articles/export', [AdminDashboardController::class, 'exportArticles'])->name('articles.export');
        
        // Ressources - Gestion bibliothèque
        Route::post('/resources/add', [AdminDashboardController::class, 'addResource'])->name('resources.add');
        Route::post('/resources/update/{id}', [AdminDashboardController::class, 'updateResource'])->name('resources.update');
        Route::delete('/resources/delete/{id}', [AdminDashboardController::class, 'deleteResource'])->name('resources.delete');
        Route::get('/resources/download/{id}', [AdminDashboardController::class, 'downloadResource'])->name('resources.download');
        Route::get('/resources/export', [AdminDashboardController::class, 'exportResources'])->name('resources.export');
        Route::post('/resources/cleanup', [AdminDashboardController::class, 'cleanupStorage'])->name('resources.cleanup');
        
        // Certificats - Délivrance
        Route::post('/certificates/issue-batch', [AdminDashboardController::class, 'issueCertificatesBatch'])->name('certificates.issue-batch');
        Route::post('/certificates/issue/{id}', [AdminDashboardController::class, 'issueCertificate'])->name('certificates.issue');
        Route::post('/certificates/notify/{id}', [AdminDashboardController::class, 'notifyStudent'])->name('certificates.notify');
        Route::post('/certificates/bulk-notify', [AdminDashboardController::class, 'bulkNotifyStudents'])->name('certificates.bulk-notify');
        Route::get('/certificates/export', [AdminDashboardController::class, 'exportCertificates'])->name('certificates.export');
        Route::get('/certificates/report', [AdminDashboardController::class, 'certificatesReport'])->name('certificates.report');
        
        // Admins - Gestion des administrateurs
        Route::post('/admins/add', [AdminDashboardController::class, 'addAdmin'])->name('admins.add');
        Route::post('/admins/update/{id}', [AdminDashboardController::class, 'updateAdmin'])->name('admins.update');
        Route::post('/admins/suspend/{id}', [AdminDashboardController::class, 'suspendAdmin'])->name('admins.suspend');
        Route::post('/admins/activate/{id}', [AdminDashboardController::class, 'activateAdmin'])->name('admins.activate');
        Route::get('/admins/permissions/{id}', [AdminDashboardController::class, 'adminPermissions'])->name('admins.permissions');
        Route::post('/admins/permissions/{id}', [AdminDashboardController::class, 'updatePermissions'])->name('admins.permissions.update');
        Route::get('/admins/logs', [AdminDashboardController::class, 'adminLogs'])->name('admins.logs');
        Route::get('/admins/security-report', [AdminDashboardController::class, 'securityReport'])->name('admins.security-report');
        Route::get('/admins/export', [AdminDashboardController::class, 'exportAdmins'])->name('admins.export');
        
        // Routes pour la gestion des étudiants
        Route::get('/students/add', [AdminDashboardController::class, 'showAddStudent'])->name('students.add');
        Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/by-formation/{formation}', [AdminDashboardController::class, 'studentsByFormation'])->name('students.by-formation');
        // routes unifiées pour profil/édition définies en dehors de ce groupe
        Route::put('/students/update/{id}', [AdminDashboardController::class, 'updateStudent'])->name('students.update');
        Route::get('/students/export-pdf', [AdminDashboardController::class, 'exportStudentsPdf'])->name('students.export-pdf');
        Route::get('/students/export-excel', [AdminDashboardController::class, 'exportStudentsExcel'])->name('students.export-excel');
        Route::get('/students/settings', [AdminDashboardController::class, 'studentsSettings'])->name('students.settings');
        Route::get('/projects/add', [AdminDashboardController::class, 'showAddProject'])->name('projects.add');
        Route::post('/projects/store', [AdminDashboardController::class, 'storeProject'])->name('projects.store');
        Route::get('/tp/add', [AdminDashboardController::class, 'showAddTp'])->name('tp.add');
        Route::post('/tp/store', [AdminDashboardController::class, 'storeTp'])->name('tp.store');
        Route::post('/quick-actions/add-student', [AdminDashboardController::class, 'quickAddStudent'])->name('quick-actions.add-student');
        Route::post('/quick-actions/add-project', [AdminDashboardController::class, 'quickAddProject'])->name('quick-actions.add-project');
        Route::post('/quick-actions/add-tp', [AdminDashboardController::class, 'quickAddTp'])->name('quick-actions.add-tp');
        Route::get('/notifications/mark-read/{id}', [AdminDashboardController::class, 'markNotificationRead'])->name('notifications.mark-read');
        Route::post('/notifications/mark-all-read', [AdminDashboardController::class, 'markAllNotificationsRead'])->name('notifications.mark-all-read');
        
        // Gestion des Étudiants par formation
        Route::get('/etudiants/design-graphique', [AdminDashboardController::class, 'studentsDesignGraphique'])->name('etudiants.design-graphique');
        Route::get('/etudiants/community-management', [AdminDashboardController::class, 'studentsCommunityManagement'])->name('etudiants.community-management');
        Route::get('/etudiants/intelligence-artificielle', [AdminDashboardController::class, 'studentsIA'])->name('etudiants.intelligence-artificielle');
        Route::get('/etudiants/gestion-informatique', [AdminDashboardController::class, 'studentsGestionInfo'])->name('etudiants.gestion-informatique');
        
        // Gestion des Catégories de la Bibliothèque (Définition Manuelle)
        Route::get('/bibliotheque/categories', [LibraryCategoryController::class, 'index'])->name('bibliotheque.categories.index');
        Route::get('/bibliotheque/categories/create', [LibraryCategoryController::class, 'create'])->name('bibliotheque.categories.create');
        Route::post('/bibliotheque/categories', [LibraryCategoryController::class, 'store'])->name('bibliotheque.categories.store');

        // --- Gestion de la Bibliothèque de Médias ---
        Route::get('/bibliotheque', [AdminDashboardController::class, 'bibliotheque'])->name('bibliotheque.index');
        Route::get('/bibliotheque/create', [AdminDashboardController::class, 'createBibliothequeItem'])->name('bibliotheque.create');
        Route::post('/bibliotheque', [AdminDashboardController::class, 'storeBibliothequeItem'])->name('bibliotheque.store');
        Route::get('/bibliotheque/{item}', [AdminDashboardController::class, 'showBibliothequeItem'])->name('bibliotheque.show');
        Route::get('/bibliotheque/{item}/edit', [AdminDashboardController::class, 'editBibliothequeItem'])->name('bibliotheque.edit');
        Route::put('/bibliotheque/{item}', [AdminDashboardController::class, 'updateBibliothequeItem'])->name('bibliotheque.update');
        Route::delete('/bibliotheque/{item}', [AdminDashboardController::class, 'destroyBibliothequeItem'])->name('bibliotheque.destroy');
        Route::post('/bibliotheque/{item}/toggle-status', [AdminDashboardController::class, 'toggleBibliothequeItemStatus'])->name('bibliotheque.toggleStatus');
        
        // Gestion des Programmes
        Route::get('/programmes', [AdminDashboardController::class, 'programmes'])->name('programmes');
        
        // Gestion des formations
        Route::get('/formations', [AdminDashboardController::class, 'index'])->name('formations.index');
        Route::post('/formations/validate', [AdminDashboardController::class, 'validateFormation'])->name('formations.validate');
        
        // Route de test pour diagnostiquer le problème
        Route::get('/formations/create-debug', [DashboardController::class, 'testCreateDebugFormation'])->name('formations.create.debug');
        Route::post('/tp/test-simple', [DashboardController::class, 'storeTestSimpleTP'])->name('tp.test-simple.store');
        
        // Gestion des Documents
        Route::get('/documents/pending', [AdminDashboardController::class, 'documentsPending'])->name('documents.pending');
        Route::get('/documents/all', [AdminDashboardController::class, 'documentsAll'])->name('documents.all');
        
        // Gestion des Travaux
        Route::get('/travaux/pending', [AdminDashboardController::class, 'travauxPending'])->name('travaux.pending');
        Route::get('/travaux/to-send', [AdminDashboardController::class, 'travauxToSend'])->name('travaux.to-send');
        Route::get('/travaux/all', [AdminDashboardController::class, 'travauxAll'])->name('travaux.all');
        
        // Gestion des Projets
        Route::get('/projets/pending', [AdminDashboardController::class, 'projetsPending'])->name('projets.pending');
        Route::get('/projets/to-send', [AdminDashboardController::class, 'projetsToSend'])->name('projets.to-send');
        Route::get('/projets/all', [AdminDashboardController::class, 'projetsAll'])->name('projets.all');
        
        // Gestion des Articles
        Route::get('/articles/evenements', [AdminDashboardController::class, 'articlesEvenements'])->name('articles.evenements');
        Route::get('/articles/actualites', [AdminDashboardController::class, 'articlesActualites'])->name('articles.actualites');
        
        // Gestion des Certificats
        Route::get('/certificats/eligible', [AdminDashboardController::class, 'certificatsEligible'])->name('certificats.eligible');
        Route::get('/certificats/not-eligible', [AdminDashboardController::class, 'certificatsNotEligible'])->name('certificats.not-eligible');
        
        // Gestion des Paiements
        Route::get('/paiements/a-jour', [AdminDashboardController::class, 'paiementsAJour'])->name('paiements.a-jour');
        Route::get('/paiements/a-solder', [AdminDashboardController::class, 'paiementsASolder'])->name('paiements.a-solder');
        Route::get('/paiements/reste-a-payer', [AdminDashboardController::class, 'paiementsResteAPayer'])->name('paiements.reste-a-payer');
        
        // Gestion des CVthèque
        Route::get('/cvtheque', [AdminDashboardController::class, 'cvtheque'])->name('cvtheque');
        Route::get('/cvtheque/profiles', [AdminDashboardController::class, 'cvthequeProfiles'])->name('cvtheque.profiles');
        Route::get('/cvtheque/validation', [AdminDashboardController::class, 'cvthequeValidation'])->name('cvtheque.validation');
        Route::post('/cvtheque/validate/{id}', [AdminDashboardController::class, 'validateCvtheque'])->name('cvtheque.validate');
        Route::post('/cvtheque/reject/{id}', [AdminDashboardController::class, 'rejectCvtheque'])->name('cvtheque.reject');
        Route::get('/cvtheque/export', [AdminDashboardController::class, 'exportCvtheque'])->name('cvtheque.export');
        
        // Gestion des Rapports
        Route::get('/rapports', [AdminDashboardController::class, 'rapports'])->name('rapports');
        Route::get('/rapports/index', [AdminDashboardController::class, 'rapports'])->name('reports.index');
        Route::get('/rapports/analytics', [AdminDashboardController::class, 'analytics'])->name('rapports.analytics');
        Route::get('/rapports/exports', [AdminDashboardController::class, 'exports'])->name('rapports.exports');
        Route::post('/rapports/generate', [AdminDashboardController::class, 'generateReport'])->name('rapports.generate');
        Route::get('/rapports/download/{type}', [AdminDashboardController::class, 'downloadReport'])->name('rapports.download');
        
        // Gestion des Étudiants
        Route::get('/students', [AdminDashboardController::class, 'students'])->name('students.index');
        Route::get('/students/create', [AdminDashboardController::class, 'createStudent'])->name('students.create');
        Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        Route::get('/students/{id}', [AdminDashboardController::class, 'showStudent'])->name('students.show');
        // route unifiée d'édition définie en dehors de ce groupe
        Route::put('/students/{id}', [AdminDashboardController::class, 'updateStudent'])->name('students.update');
        Route::delete('/students/{id}', [AdminDashboardController::class, 'deleteStudent'])->name('students.delete');
        // Route::post('/students/{id}/toggle-status', [AdminDashboardController::class, 'toggleStudentStatus'])->name('students.toggle-status'); // COMMENTÉ - Route dupliquée, voir ligne 107
        Route::get('/students/by-formation/{formation}', [AdminDashboardController::class, 'studentsByFormation'])->name('students.by-formation');
        Route::get('/students/{id}/profile', [\App\Http\Controllers\Admin\StudentAdminController::class, 'profile'])->name('students.profile');
        Route::get('/students/add', [AdminDashboardController::class, 'createStudent'])->name('students.add');
        
        // Routes pour la gestion des projets design (admin)
        Route::get('/projects/{id}', [\App\Http\Controllers\Admin\StudentAdminController::class, 'showProject'])->name('projects.show');
        Route::post('/projects/{id}/validate', [\App\Http\Controllers\Admin\StudentAdminController::class, 'validateProject'])->name('projects.validate');
        Route::get('/projects/{id}/download', [\App\Http\Controllers\Admin\StudentAdminController::class, 'downloadProject'])->name('projects.download');
        Route::delete('/projects/{id}/delete', [\App\Http\Controllers\Admin\StudentAdminController::class, 'deleteProject'])->name('projects.delete');
        
        // Gestion des Admins
        Route::get('/admins', [AdminDashboardController::class, 'admins'])->name('admins.index');
        Route::get('/admins/create', [AdminDashboardController::class, 'createAdmin'])->name('admins.create');
        Route::post('/admins/store', [AdminDashboardController::class, 'storeAdmin'])->name('admins.store');
        Route::get('/admins/{id}/edit', [AdminDashboardController::class, 'editAdmin'])->name('admins.edit');
        Route::put('/admins/{id}', [AdminDashboardController::class, 'updateAdmin'])->name('admins.update');
        Route::delete('/admins/{id}', [AdminDashboardController::class, 'deleteAdmin'])->name('admins.delete');
        Route::post('/admins/{id}/toggle-status', [AdminDashboardController::class, 'toggleAdminStatus'])->name('admins.toggle-status');
        Route::get('/admins/roles', [AdminDashboardController::class, 'adminRoles'])->name('admins.roles');
        Route::post('/admins/roles/assign', [AdminDashboardController::class, 'assignRole'])->name('admins.roles.assign');
        Route::get('/admins/permissions', [AdminDashboardController::class, 'adminPermissions'])->name('admins.permissions');
        Route::post('/admins/permissions/update', [AdminDashboardController::class, 'updatePermissions'])->name('admins.permissions.update');
        
        // API Routes pour la visualisation des projets (Approche développeur senior)
        Route::prefix('api')->group(function () {
            Route::get('/projects/{id}', [ProjectApiController::class, 'show'])->name('api.projects.show');
        });

        // Routes manquantes pour les pages d'erreur et navigation
        Route::get('/etudiants', [AdminDashboardController::class, 'students'])->name('etudiants');
        Route::get('/documents', [AdminDashboardController::class, 'documents'])->name('documents');
        Route::get('/documents/index', [AdminDashboardController::class, 'documents'])->name('documents.index');

        // Paramètres Admin
        Route::get('/parametres', [AdminDashboardController::class, 'parametres'])->name('parametres.index');
        Route::post('/parametres', [AdminDashboardController::class, 'updateParametres'])->name('parametres.update');
        Route::get('/parametres/system', [AdminDashboardController::class, 'systemSettings'])->name('parametres.system');
        Route::get('/parametres/security', [AdminDashboardController::class, 'securitySettings'])->name('parametres.security');
        Route::get('/parametres/notifications', [AdminDashboardController::class, 'notificationSettings'])->name('parametres.notifications');
        Route::get('/parametres/backup', [AdminDashboardController::class, 'backupSettings'])->name('parametres.backup');
        Route::post('/parametres/backup/create', [AdminDashboardController::class, 'createBackup'])->name('parametres.backup.create');
        Route::get('/parametres/logs', [AdminDashboardController::class, 'systemLogs'])->name('parametres.logs');
        
        // Routes héritées (compatibilité)
        Route::get('/etudiants', [AdminDashboardController::class, 'users'])->name('etudiants');
        Route::get('/documents', [AdminDashboardController::class, 'documents'])->name('documents.index');
    });
});

// Routes supprimées - toutes les routes respectent maintenant la nomenclature /evc/compte/design-graphique/{menu}/{action}
// Anciennes routes avec préfixe 'compte-evc/' supprimées pour respecter la nouvelle nomenclature
