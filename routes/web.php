<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CVThequeController;
use App\Http\Controllers\Auth\PasswordResetController;
use App\Http\Controllers\Admin\AdminAuthController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\LibraryCategoryController;
use App\Http\Controllers\Admin\AdminStatisticsController;
use App\Http\Controllers\Admin\PreRegistrationAdminController;
use App\Http\Controllers\Admin\CVThequeAdminController;
use App\Http\Controllers\Admin\DonationAdminController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\HomepageController;
use App\Http\Controllers\Api\ProjectApiController;
use App\Http\Controllers\AdminStatisticsDetailController;
use App\Http\Controllers\StudentConfirmationController;
use App\Http\Controllers\DonationController;
use App\Http\Controllers\StudentIdVerificationController;
use App\Http\Controllers\ActivityReportPublicController;
use App\Http\Controllers\PartnershipController;
use App\Http\Controllers\Admin\PartnershipsAdminController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;



// Page d'accueil et pré-inscription
Route::get('/', [HomepageController::class, 'index'])->name('homepage');

Route::get('/domicile', [HomepageController::class, 'index'])->name('domicile');

$servePublicStorage = function (string $path) {
    $path = ltrim($path, '/');
    if (str_contains($path, '..')) {
        abort(404);
    }

    if (!Storage::disk('public')->exists($path)) {
        abort(404);
    }

    $fullPath = storage_path('app/public/' . $path);
    return response()->file($fullPath);
};

Route::get('/storage/app/public/{path}', $servePublicStorage)->where('path', '.*');
Route::get('/evc/storage/app/public/{path}', $servePublicStorage)->where('path', '.*');

Route::get('/preinscription', function () {
    return response()
        ->view('preinscription.index')
        ->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')
        ->header('Pragma', 'no-cache')
        ->header('Expires', '0');
})->name('preinscription.start');
Route::post('/pre-registration', [HomepageController::class, 'store'])->name('pre-registration.store');
Route::post('/candidature', [HomepageController::class, 'candidatureStore'])->name('candidature.store');

Route::get('/faire-un-don', [DonationController::class, 'index'])->name('donation.index');
Route::post('/faire-un-don', [DonationController::class, 'submit'])->name('donation.submit');

Route::get('/webtv', [HomepageController::class, 'webtv'])->name('webtv');
Route::get('/webtv/thematique/{category}', [HomepageController::class, 'webtvThematique'])->name('webtv.thematique');

// Route de test/diagnostic WebTV (à supprimer en production)
Route::get('/webtv/test-embed', function () {
    return view('test-webtv-embed');
})->name('webtv.test.embed');

// Routes WebTV Subscription
Route::post('/webtv/subscribe', [App\Http\Controllers\WebtvSubscriptionController::class, 'subscribe'])->name('webtv.subscribe');
Route::get('/webtv/verify/{token}', [App\Http\Controllers\WebtvSubscriptionController::class, 'verify'])->name('webtv.verify');
Route::post('/webtv/unsubscribe', [App\Http\Controllers\WebtvSubscriptionController::class, 'unsubscribe'])->name('webtv.unsubscribe');

Route::get('/presentation', [HomepageController::class, 'presentation'])->name('presentation');
Route::get('/formations', [HomepageController::class, 'formations'])->name('formations');
Route::get('/plaquettes-formations', [HomepageController::class, 'plaquettesFormations'])->name('plaquettes.formations');
Route::get('/evc/plaquettes-formations', [HomepageController::class, 'plaquettesFormations'])->name('plaquettes.formations.evc');
Route::get('/plaquettes-formations/item/{plaquette}', [HomepageController::class, 'plaquetteFormById'])->whereNumber('plaquette')->name('plaquettes.formations.form.id');
Route::post('/plaquettes-formations/item/{plaquette}', [HomepageController::class, 'plaquetteDownloadById'])->whereNumber('plaquette')->name('plaquettes.formations.download.id');
Route::get('/plaquettes-formations/request/{plaquetteRequest}/download', [HomepageController::class, 'plaquetteFileByRequest'])
    ->whereNumber('plaquetteRequest')
    ->middleware('signed')
    ->name('plaquettes.requests.file');
Route::get('/evc/plaquettes-formations/item/{plaquette}', [HomepageController::class, 'plaquetteFormById'])->whereNumber('plaquette')->name('plaquettes.formations.form.id.evc');
Route::post('/evc/plaquettes-formations/item/{plaquette}', [HomepageController::class, 'plaquetteDownloadById'])->whereNumber('plaquette')->name('plaquettes.formations.download.id.evc');
Route::get('/evc/plaquettes-formations/request/{plaquetteRequest}/download', [HomepageController::class, 'plaquetteFileByRequest'])
    ->whereNumber('plaquetteRequest')
    ->middleware('signed')
    ->name('plaquettes.requests.file.evc');
Route::get('/plaquettes-formations/{filename}', [HomepageController::class, 'plaquetteForm'])->where('filename', '.*')->name('plaquettes.formations.form');
Route::post('/plaquettes-formations/{filename}', [HomepageController::class, 'plaquetteDownload'])->where('filename', '.*')->name('plaquettes.formations.download');
Route::get('/evc/plaquettes-formations/{filename}', [HomepageController::class, 'plaquetteForm'])->where('filename', '.*')->name('plaquettes.formations.form.evc');
Route::post('/evc/plaquettes-formations/{filename}', [HomepageController::class, 'plaquetteDownload'])->where('filename', '.*')->name('plaquettes.formations.download.evc');
Route::get('/travaux-etudiants', [HomepageController::class, 'travaux'])->name('travaux');
Route::get('/laureats', [HomepageController::class, 'laureats'])->name('laureats');
Route::get('/jury', [HomepageController::class, 'jury'])->name('jury');
Route::get('/rapports-activite', [ActivityReportPublicController::class, 'index'])->name('activity-reports.index');
Route::get('/rapports-activite/download/{activityReport}', [ActivityReportPublicController::class, 'download'])->name('activity-reports.download');
Route::get('/evenements', [App\Http\Controllers\EvenementPublicController::class, 'allEvenements'])->name('evenements.all');
Route::get('/evenement/{slug}', [HomepageController::class, 'showEvenement'])->name('evenement.show');
Route::get('/actualites', [HomepageController::class, 'actualites'])->name('actualites');
Route::get('/actualite/{slug}', function ($slug) {
    return redirect()->route('actualite.show', ['slug' => $slug], 301);
})->name('actualite.redirect');
Route::get('/actualites/{slug}', [HomepageController::class, 'showActualite'])->name('actualite.show');

Route::get('/partenariat/{slug}', [PartnershipController::class, 'show'])->name('partnerships.show');

// Pages légales
Route::get('/mentions-legales', function () {
    return view('legal.mentions-legales');
})->name('mentions-legales');

Route::get('/politique-confidentialite', function () {
    return view('legal.politique-confidentialite');
})->name('politique-confidentialite');

Route::get('/reglement-interieur', function () {
    return view('legal.reglement-interieur');
})->name('reglement-interieur');

// Routes "Rejoignez-nous"
Route::get('/rejoignez-nous', function () {
    return view('rejoignez-nous');
})->name('rejoignez-nous');

Route::get('/rejoignez-nous/collaborateur', function () {
    return view('rejoignez-nous.collaborateur');
})->name('rejoignez-nous.collaborateur');

Route::get('/rejoignez-nous/partenaire', function () {
    return view('rejoignez-nous.partenaire');
})->name('rejoignez-nous.partenaire');

Route::get('/rejoignez-nous/formateur', function () {
    return view('rejoignez-nous.formateur');
})->name('rejoignez-nous.formateur');

Route::get('/parcours-formateur', function () {
    return view('parcours-formateur');
})->name('parcours-formateur');

// Routes de soumission des formulaires "Rejoignez-nous"
Route::post('/rejoignez-nous/collaborateur/submit', [HomepageController::class, 'collaborateurSubmit'])->name('rejoignez-nous.collaborateur.submit');
Route::post('/rejoignez-nous/partenaire/submit', [HomepageController::class, 'partenaireSubmit'])->name('rejoignez-nous.partenaire.submit');
Route::post('/rejoignez-nous/formateur/submit', [HomepageController::class, 'formateurSubmit'])->name('rejoignez-nous.formateur.submit');

// Routes de paiement (accessibles publiquement)
Route::get('/evc/payment/{paymentReference}', [\App\Http\Controllers\PaymentController::class, 'showCheckout'])->name('payment.checkout');
Route::post('/evc/payment/process', [\App\Http\Controllers\PaymentController::class, 'processPayment'])->name('payment.process');
Route::get('/evc/payment/return', [\App\Http\Controllers\PaymentController::class, 'paymentReturn'])->name('payment.return');
Route::get('/evc/payment/cancel', [\App\Http\Controllers\PaymentController::class, 'paymentCancel'])->name('payment.cancel');
Route::post('/evc/payment/webhook', [\App\Http\Controllers\PaymentController::class, 'webhook'])->name('payment.webhook');
Route::post('/evc/payment/test/success', [\App\Http\Controllers\PaymentController::class, 'testPaymentSuccess'])->name('payment.test.success');

// Routes Chariow (paiement alternatif)
Route::prefix('evc/payment/chariow')->group(function () {
    Route::get('/return', [\App\Http\Controllers\PaymentController::class, 'chariowReturn'])->name('payment.chariow.return');
    Route::get('/cancel', [\App\Http\Controllers\PaymentController::class, 'chariowCancel'])->name('payment.chariow.cancel');
    Route::post('/webhook', [\App\Http\Controllers\PaymentController::class, 'chariowWebhook'])->name('payment.chariow.webhook');
});

// Routes d'authentification

Route::get('/auth/evc/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/auth/evc/login', [AuthController::class, 'login']);
Route::get('/auth/evc/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/auth/evc/register', [AuthController::class, 'register']);

// Vérification publique d'ID étudiant
Route::get('/auth/evc/verify-id', [StudentIdVerificationController::class, 'show'])->name('auth.verify-id');
Route::post('/auth/evc/verify-id', [StudentIdVerificationController::class, 'check'])->name('auth.verify-id.check');
Route::get('/auth/evc/verify-id/certificate/preview', [StudentIdVerificationController::class, 'certificatePreview'])->name('auth.verify-id.certificate.preview');

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

// Admin: liste des étudiants (index avec paramètre de requête formation)
Route::get('/evc/app/admin/students', [\App\Http\Controllers\Admin\StudentAdminController::class, 'index'])
    ->name('admin.students.index');

// Admin: liste des étudiants par formation
Route::get('/evc/app/admin/etudiants/{formation}', [\App\Http\Controllers\Admin\StudentAdminController::class, 'listByFormation'])
    ->whereIn('formation', ['design-graphique', 'community-manager', 'community-management', 'design-graphique-community-manager', 'intelligence-artificielle', 'gestion-informatique'])
    ->name('admin.students.by-formation');

// (Nettoyage) On conserve seulement les routes admin.students.*

Route::get('/evc/app/admin/students/{id}/edit', [\App\Http\Controllers\Admin\StudentAdminController::class, 'edit'])
    ->whereNumber('id')
    ->name('admin.students.edit');

Route::put('/evc/app/admin/students/{id}', [\App\Http\Controllers\Admin\StudentAdminController::class, 'update'])
    ->whereNumber('id')
    ->name('admin.students.update');

Route::post('/evc/app/admin/students/{studentId}/extend-expiration', [\App\Http\Controllers\Admin\StudentAdminController::class, 'extendExpiration'])
    ->whereNumber('studentId')
    ->name('admin.students.extend-expiration');

Route::post('/evc/app/admin/students/{id}/toggle-status', [\App\Http\Controllers\Admin\StudentAdminController::class, 'toggleStatus'])
    ->whereNumber('id')
    ->name('admin.students.toggle-status');

// Route pour la page de compte désactivé (accessible UNIQUEMENT avec compte désactivé)
Route::get('/compte-desactive', function () {
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
                'Community Management' => 'dashboard.community-management',
                'Design Graphique & Community Management' => 'dashboard.design-graphique-cm',
                'design_graphique_community_management' => 'dashboard.design-graphique-cm',
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
    Route::get('/evc/compte/design-graphique/espace-etudiant/stats', [DashboardController::class, 'designGraphiqueStats'])->name('dashboard.design-graphique.stats');
    Route::get('/evc/compte/design-graphique-cm/espace-etudiant', [DashboardController::class, 'designCm'])->name('dashboard.design-graphique-cm');
    Route::get('/evc/compte/design-graphique-cm/espace-etudiant/stats', [DashboardController::class, 'designCmStats'])->name('dashboard.design-graphique-cm.stats');
    Route::get('/evc/compte/notifications', [DashboardController::class, 'notificationsFeed'])->name('dashboard.notifications.feed');
    Route::post('/evc/compte/notifications/mark-read', [DashboardController::class, 'notificationsMarkRead'])->name('dashboard.notifications.mark-read');
    Route::get('/evc/compte/notifications/toutes', [DashboardController::class, 'notificationsIndex'])->name('dashboard.notifications.index');
    Route::get('/evc/compte/community-manager/espace-etudiant', [DashboardController::class, 'communityManagement'])->name('dashboard.community-manager');
    Route::get('/evc/compte/community-management/espace-etudiant', [DashboardController::class, 'communityManagement'])->name('dashboard.community-management');
    Route::get('/evc/compte/community-management/espace-etudiant/stats', [DashboardController::class, 'communityManagementStats'])->name('dashboard.community-management.stats');
    Route::get('/evc/compte/intelligence-artificielle/espace-etudiant', [DashboardController::class, 'intelligenceArtificielle'])->name('dashboard.intelligence-artificielle');
    Route::get('/evc/compte/gestion-informatique/espace-etudiant', [DashboardController::class, 'gestionInformatique'])->name('dashboard.gestion-informatique');
});

// Groupe de routes pour Design Graphique avec préfixe commun (PROTÉGÉ PAR AUTH + VÉRIFICATION COMPTE ACTIF + ACCÈS FORMATION)
Route::prefix('/evc/compte/design-graphique')->name('design-graphique.')->middleware(['auth', 'student.active', 'formation.access'])->group(function () {
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
    Route::get('/cvtheque/profile-display', [CVThequeController::class, 'profileDisplay'])->name('cvtheque.profile-display');
    Route::get('/cvtheque/mon-profil', [CVThequeController::class, 'monProfil'])->name('cvtheque.mon-profil');
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
    Route::get('/tp/{id}/soumettre', [DashboardController::class, 'showSubmitPage'])->name('tp.soumettre');
    Route::post('/tp/{id}/submit', [DashboardController::class, 'submitTP'])->name('tp.submit');
    // Routes pour les projets de design graphique
    Route::get('/projets', [DashboardController::class, 'projets'])->name('projets.index');
    Route::post('/projets', [App\Http\Controllers\DesignProjectController::class, 'store'])->name('projets.store');
    Route::get('/projets/stats/json', [App\Http\Controllers\DesignProjectController::class, 'getStats'])->name('projets.stats');
    Route::get('/projets/historique', [App\Http\Controllers\DesignProjectController::class, 'historique'])->name('projets.historique');
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
    Route::delete('/tp/{tpId}/fichier/{fileId}', [DashboardController::class, 'deleteTPFile'])->name('tp.fichier.supprimer');
    Route::delete('/tp/supprimer/{id}', [DashboardController::class, 'deleteProject'])->name('tp.supprimer');

    // TP Version Simple (pour debug/test)
    Route::get('/tp/ajouter-simple', [DashboardController::class, 'ajouterSimpleTP'])->name('tp.ajouter-simple');

    // Test ultra-simple pour diagnostic
    Route::get('/tp/test-simple', [DashboardController::class, 'testSimpleTP'])->name('tp.test-simple');

    Route::post('/tp/test-simple', [DashboardController::class, 'storeTestSimpleTP'])->name('tp.test-simple.store');

    // Diagnostic TP - 100% Laravel (pour debug/test)
    Route::get('/diagnostic', [DashboardController::class, 'diagnosticTP'])->name('diagnostic.tp');

    // To Do List - Structure: /evc/compte/design-graphique/todo/{action}
    Route::get('/todo/index', [DashboardController::class, 'todoIndex'])->name('todo.index');

    // Traiter un projet assigné (publication)
    Route::get('/todo/traiter/{projectId}', [DashboardController::class, 'traiterAssignedProject'])->name('todo.traiter');
    Route::post('/todo/traiter/{projectId}', [DashboardController::class, 'storeTreatedAssignedProject'])->name('todo.traiter.store');

    // Programme - Structure: /evc/compte/design-graphique/programme/{action}
    Route::get('/programme/index', [DashboardController::class, 'programmeIndex'])->name('programme.index');
    Route::get('/programme/{id}', [DashboardController::class, 'programmeShow'])->whereNumber('id')->name('programme.show');
    Route::get('/programme/formation/{slug}', [DashboardController::class, 'programmeFormation'])->name('programme.formation');

    // Paiements - Structure: /evc/compte/design-graphique/paiements/{action}
    Route::get('/paiements/index', [DashboardController::class, 'paiementsIndex'])->name('paiements.index');
    Route::get('/paiements/invoice/{paymentId}', [DashboardController::class, 'downloadInvoice'])->name('paiements.invoice');

    // Fin de formation - Structure: /evc/compte/design-graphique/fin-formation/{action}
    Route::get('/fin-formation/index', [DashboardController::class, 'finFormationIndex'])->name('fin-formation.index');
    Route::post('/fin-formation/upload-report', [DashboardController::class, 'uploadReport'])->name('fin-formation.upload-report');
    Route::get('/fin-formation/download-report/{id}', [DashboardController::class, 'downloadReport'])->name('fin-formation.download-report');

    // Certificat - Téléchargement et prévisualisation
    Route::get('/certificate/preview', [DashboardController::class, 'previewCertificateStudent'])->name('certificate.preview');
    Route::get('/certificate/download', [DashboardController::class, 'downloadCertificate'])->name('certificate.download');

    // Paramètres - Structure: /evc/compte/design-graphique/parametres/{action}
    Route::get('/parametres/index', [App\Http\Controllers\ProfileController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [App\Http\Controllers\ProfileController::class, 'update'])->name('parametres.update');
    Route::post('/parametres/update-login', [App\Http\Controllers\ProfileController::class, 'updateLoginInfo'])->name('parametres.update-login');
    Route::post('/parametres/upload-photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('parametres.upload-photo');

    // Communauté - Structure: /evc/compte/design-graphique/communaute/{action}
    Route::get('/communaute/index', [DashboardController::class, 'communauteIndex'])->name('communaute.index');

    // Bibliothèque - Structure: /evc/compte/design-graphique/bibliotheque/{action}
    Route::get('/bibliotheque/index', [DashboardController::class, 'bibliothequeIndex'])->name('bibliotheque.index');
    Route::get('/bibliotheque/download/{id}', [DashboardController::class, 'downloadDocument'])->name('bibliotheque.download');

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
    Route::get('/events/{id}', [DashboardController::class, 'eventsShow'])->name('events.show');

    // Actualités - Structure: /evc/compte/design-graphique/actualites/{action}
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');
    Route::get('/actualites/{id}', [DashboardController::class, 'actualitesShow'])->name('actualites.show');

    // Documents - Structure: /evc/compte/design-graphique/documents/{action}
    Route::get('/documents/index', [DashboardController::class, 'documentsIndex'])->name('documents.index');
    Route::get('/documents/download/{id}', [DashboardController::class, 'downloadDocument'])->name('documents.download');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notificationsFeed'])->name('notifications.feed');
    Route::post('/notifications/mark-read', [DashboardController::class, 'notificationsMarkRead'])->name('notifications.mark-read');
    Route::get('/notifications/toutes', [DashboardController::class, 'notificationsIndex'])->name('notifications.index');
});

// Groupe de routes pour Design Graphique & Community Manager (Formation combinée)
Route::prefix('/evc/compte/design-graphique-cm')->name('design-graphique-cm.')->middleware(['auth', 'student.active', 'formation.access'])->group(function () {
    // Profil
    Route::get('/profil/editer/{id?}', [DashboardController::class, 'editProfile'])->name('profil.editer');
    Route::post('/profil/editer/{id?}', [DashboardController::class, 'updateProfile'])->name('profil.update');

    // CVThèque
    Route::get('/cvtheque/index', [CVThequeController::class, 'index'])->name('cvtheque.index');
    Route::get('/cvtheque/historique', [CVThequeController::class, 'historique'])->name('cvtheque.historique');
    Route::post('/cvtheque/update-profile', [CVThequeController::class, 'updateProfile'])->name('cvtheque.update-profile');
    Route::delete('/cvtheque/documents/delete', [CVThequeController::class, 'deleteDocument'])->name('cvtheque.documents.delete');
    Route::get('/cvtheque/documents/export', [CVThequeController::class, 'exportDocuments'])->name('cvtheque.documents.export');
    Route::get('/cvtheque/preview', [CVThequeController::class, 'preview'])->name('cvtheque.preview');
    Route::get('/cvtheque/profile-display', [CVThequeController::class, 'profileDisplay'])->name('cvtheque.profile-display');
    Route::get('/cvtheque/mon-profil', [CVThequeController::class, 'monProfil'])->name('cvtheque.mon-profil');
    Route::post('/cvtheque/upload-cv', [CVThequeController::class, 'uploadCV'])->name('cvtheque.upload-cv');
    Route::post('/cvtheque/upload-motivation', [CVThequeController::class, 'uploadMotivation'])->name('cvtheque.upload-motivation');
    Route::post('/cvtheque/upload-realisation', [CVThequeController::class, 'uploadRealisations'])->name('cvtheque.upload-realisation');
    Route::post('/cvtheque/upload-pressbook', [CVThequeController::class, 'uploadPressbook'])->name('cvtheque.upload-pressbook');
    Route::post('/cvtheque/upload-rapport', [CVThequeController::class, 'uploadRapport'])->name('cvtheque.upload-rapport');

    // TP (Travaux Pratiques)
    Route::get('/tp/index', [DashboardController::class, 'listTP'])->name('tp.index');
    Route::get('/tp/tous', [DashboardController::class, 'showAllTP'])->name('tp.tous');
    Route::get('/tp/voir/{id}', [DashboardController::class, 'viewTP'])->name('tp.voir');
    Route::get('/tp/ajouter', [DashboardController::class, 'createTP'])->name('tp.ajouter');
    Route::post('/tp/ajouter', [DashboardController::class, 'storeTP'])->name('tp.store');
    Route::get('/tp/modifier/{id}', [DashboardController::class, 'editTP'])->name('tp.modifier');
    Route::get('/tp/{id}/soumettre', [DashboardController::class, 'showSubmitPage'])->name('tp.soumettre');
    Route::post('/tp/{id}/submit', [DashboardController::class, 'submitTP'])->name('tp.submit');
    Route::put('/tp/modifier/{id}', [DashboardController::class, 'updateProject'])->name('tp.update');
    Route::post('/tp/modifier/{id}/images', [DashboardController::class, 'updateProjectWithImages'])->name('tp.update.images');
    Route::delete('/tp/{tpId}/fichier/{fileId}', [DashboardController::class, 'deleteTPFile'])->name('tp.fichier.supprimer');
    Route::delete('/tp/supprimer/{id}', [DashboardController::class, 'deleteProject'])->name('tp.supprimer');

    // Projets
    Route::get('/projets', [DashboardController::class, 'projets'])->name('projets.index');
    Route::post('/projets', [App\Http\Controllers\DesignProjectController::class, 'store'])->name('projets.store');
    Route::get('/projets/stats/json', [App\Http\Controllers\DesignProjectController::class, 'getStats'])->name('projets.stats');
    Route::get('/projets/historique', [App\Http\Controllers\DesignProjectController::class, 'historique'])->name('projets.historique');
    Route::get('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'show'])->name('projets.show');
    Route::get('/projets/{id}/edit', [App\Http\Controllers\DesignProjectController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projectId}/files/{fileId}', [App\Http\Controllers\DesignProjectController::class, 'removeFile'])->name('projets.removeFile');
    Route::patch('/projets/{id}/status', [App\Http\Controllers\DesignProjectController::class, 'updateStatus'])->name('projets.updateStatus');
    Route::delete('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'destroy'])->name('projets.destroy');
    Route::get('/projets/solo/liste', [App\Http\Controllers\DesignProjectController::class, 'soloProjects'])->name('projets.solo');
    Route::get('/projets/groupe/liste', [App\Http\Controllers\DesignProjectController::class, 'groupProjects'])->name('projets.groupe');
    Route::get('/projets/tous/liste', [App\Http\Controllers\DesignProjectController::class, 'allProjects'])->name('projets.tous');

    // To Do List
    Route::get('/todo/index', [DashboardController::class, 'todoIndex'])->name('todo.index');
    Route::get('/todo/traiter/{projectId}', [DashboardController::class, 'traiterAssignedProject'])->name('todo.traiter');
    Route::post('/todo/traiter/{projectId}', [DashboardController::class, 'storeTreatedAssignedProject'])->name('todo.traiter.store');

    // Programme
    Route::get('/programme/index', [DashboardController::class, 'programmeIndex'])->name('programme.index');
    Route::get('/programme/{id}', [DashboardController::class, 'programmeShow'])->whereNumber('id')->name('programme.show');
    Route::get('/programme/formation/{slug}', [DashboardController::class, 'programmeFormation'])->name('programme.formation');

    // Paiements
    Route::get('/paiements/index', [DashboardController::class, 'paiementsIndex'])->name('paiements.index');
    Route::get('/paiements/invoice/{paymentId}', [DashboardController::class, 'downloadInvoice'])->name('paiements.invoice');

    // Fin de formation
    Route::get('/fin-formation/index', [DashboardController::class, 'finFormationIndex'])->name('fin-formation.index');

    // Certificat
    Route::get('/certificate/preview', [DashboardController::class, 'previewCertificateStudent'])->name('certificate.preview');
    Route::get('/certificate/download', [DashboardController::class, 'downloadCertificate'])->name('certificate.download');

    // Paramètres
    Route::get('/parametres/index', [App\Http\Controllers\ProfileController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [App\Http\Controllers\ProfileController::class, 'update'])->name('parametres.update');
    Route::post('/parametres/update-login', [App\Http\Controllers\ProfileController::class, 'updateLoginInfo'])->name('parametres.update-login');
    Route::post('/parametres/upload-photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('parametres.upload-photo');

    // Communauté
    Route::get('/communaute/index', [DashboardController::class, 'communauteIndex'])->name('communaute.index');

    // Bibliothèque
    Route::get('/bibliotheque/index', [DashboardController::class, 'bibliothequeIndex'])->name('bibliotheque.index');
    Route::get('/bibliotheque/download/{id}', [DashboardController::class, 'downloadDocument'])->name('bibliotheque.download');

    // Formations
    Route::get('/formations/index', [DashboardController::class, 'formationsIndex'])->name('formations.index');
    Route::get('/formations/category/{category}', [DashboardController::class, 'formationsCategory'])->name('formations.category');
    Route::get('/formations/show/{id}', [DashboardController::class, 'formationsShow'])->name('formations.show');
    Route::get('/formations/download/{id}', [DashboardController::class, 'formationsDownload'])->name('formations.download');
    Route::get('/formations/download-all/{id}', [DashboardController::class, 'formationsDownloadAll'])->name('formations.download-all');

    // Events
    Route::get('/events/index', [DashboardController::class, 'eventsIndex'])->name('events.index');
    Route::get('/events/{id}', [DashboardController::class, 'eventsShow'])->name('events.show');

    // Actualités
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');
    Route::get('/actualites/{id}', [DashboardController::class, 'actualitesShow'])->name('actualites.show');

    // Documents
    Route::get('/documents/index', [DashboardController::class, 'documentsIndex'])->name('documents.index');
    Route::get('/documents/download/{id}', [DashboardController::class, 'downloadDocument'])->name('documents.download');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notificationsFeed'])->name('notifications.feed');
    Route::post('/notifications/mark-read', [DashboardController::class, 'notificationsMarkRead'])->name('notifications.mark-read');
    Route::get('/notifications/toutes', [DashboardController::class, 'notificationsIndex'])->name('notifications.index');
});

// Groupe de routes pour Design Graphique & Community Management (design-graphique-cm)
Route::prefix('/evc/compte/design-graphique-cm')->name('design-graphique-cm.')->middleware(['auth', 'student.active', 'formation.access'])->group(function () {
    // Profil
    Route::get('/profil/editer/{id?}', [DashboardController::class, 'editProfile'])->name('profil.editer');
    Route::post('/profil/editer/{id?}', [DashboardController::class, 'updateProfile'])->name('profil.update');

    // CVThèque
    Route::get('/cvtheque/index', [CVThequeController::class, 'index'])->name('cvtheque.index');
    Route::get('/cvtheque/historique', [CVThequeController::class, 'historique'])->name('cvtheque.historique');
    Route::post('/cvtheque/update-profile', [CVThequeController::class, 'updateProfile'])->name('cvtheque.update-profile');
    Route::delete('/cvtheque/documents/delete', [CVThequeController::class, 'deleteDocument'])->name('cvtheque.documents.delete');
    Route::get('/cvtheque/documents/export', [CVThequeController::class, 'exportDocuments'])->name('cvtheque.documents.export');
    Route::get('/cvtheque/preview', [CVThequeController::class, 'preview'])->name('cvtheque.preview');
    Route::get('/cvtheque/profile-display', [CVThequeController::class, 'profileDisplay'])->name('cvtheque.profile-display');
    Route::get('/cvtheque/mon-profil', [CVThequeController::class, 'monProfil'])->name('cvtheque.mon-profil');
    Route::post('/cvtheque/upload-cv', [CVThequeController::class, 'uploadCV'])->name('cvtheque.upload-cv');
    Route::post('/cvtheque/upload-motivation', [CVThequeController::class, 'uploadMotivation'])->name('cvtheque.upload-motivation');
    Route::post('/cvtheque/upload-realisation', [CVThequeController::class, 'uploadRealisations'])->name('cvtheque.upload-realisation');
    Route::post('/cvtheque/upload-pressbook', [CVThequeController::class, 'uploadPressbook'])->name('cvtheque.upload-pressbook');
    Route::post('/cvtheque/upload-rapport', [CVThequeController::class, 'uploadRapport'])->name('cvtheque.upload-rapport');

    // TP
    Route::get('/tp/index', [DashboardController::class, 'listTP'])->name('tp.index');
    Route::get('/tp/tous', [DashboardController::class, 'showAllTP'])->name('tp.tous');
    Route::get('/tp/voir/{id}', [DashboardController::class, 'viewTP'])->name('tp.voir');
    Route::get('/tp/ajouter', [DashboardController::class, 'createTP'])->name('tp.ajouter');
    Route::post('/tp/ajouter', [DashboardController::class, 'storeTP'])->name('tp.store');
    Route::get('/tp/modifier/{id}', [DashboardController::class, 'editTP'])->name('tp.modifier');
    Route::get('/tp/{id}/soumettre', [DashboardController::class, 'showSubmitPage'])->name('tp.soumettre');
    Route::post('/tp/{id}/submit', [DashboardController::class, 'submitTP'])->name('tp.submit');
    Route::put('/tp/modifier/{id}', [DashboardController::class, 'updateProject'])->name('tp.update');
    Route::post('/tp/modifier/{id}/images', [DashboardController::class, 'updateProjectWithImages'])->name('tp.update.images');
    Route::delete('/tp/{tpId}/fichier/{fileId}', [DashboardController::class, 'deleteTPFile'])->name('tp.fichier.supprimer');
    Route::delete('/tp/supprimer/{id}', [DashboardController::class, 'deleteProject'])->name('tp.supprimer');

    // Projets
    Route::get('/projets', [DashboardController::class, 'projets'])->name('projets.index');
    Route::post('/projets', [App\Http\Controllers\DesignProjectController::class, 'store'])->name('projets.store');
    Route::get('/projets/stats/json', [App\Http\Controllers\DesignProjectController::class, 'getStats'])->name('projets.stats');
    Route::get('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'show'])->name('projets.show');
    Route::get('/projets/{id}/edit', [App\Http\Controllers\DesignProjectController::class, 'edit'])->name('projets.edit');
    Route::put('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'update'])->name('projets.update');
    Route::delete('/projets/{projectId}/files/{fileId}', [App\Http\Controllers\DesignProjectController::class, 'removeFile'])->name('projets.removeFile');
    Route::patch('/projets/{id}/status', [App\Http\Controllers\DesignProjectController::class, 'updateStatus'])->name('projets.updateStatus');
    Route::delete('/projets/{id}', [App\Http\Controllers\DesignProjectController::class, 'destroy'])->name('projets.destroy');
    Route::get('/projets/solo/liste', [App\Http\Controllers\DesignProjectController::class, 'soloProjects'])->name('projets.solo');
    Route::get('/projets/groupe/liste', [App\Http\Controllers\DesignProjectController::class, 'groupProjects'])->name('projets.groupe');
    Route::get('/projets/tous/liste', [App\Http\Controllers\DesignProjectController::class, 'allProjects'])->name('projets.tous');

    // Programme
    Route::get('/programme/index', [DashboardController::class, 'programmeIndex'])->name('programme.index');
    Route::get('/programme/{id}', [DashboardController::class, 'programmeShow'])->whereNumber('id')->name('programme.show');
    Route::get('/programme/formation/{slug}', [DashboardController::class, 'programmeFormation'])->name('programme.formation');

    // Paiements
    Route::get('/paiements/index', [DashboardController::class, 'paiementsIndex'])->name('paiements.index');
    Route::get('/paiements/invoice/{paymentId}', [DashboardController::class, 'downloadInvoice'])->name('paiements.invoice');

    // Fin de formation
    Route::get('/fin-formation/index', [DashboardController::class, 'finFormationIndex'])->name('fin-formation.index');
    Route::post('/fin-formation/upload-report', [DashboardController::class, 'uploadReport'])->name('fin-formation.upload-report');
    Route::get('/fin-formation/download-report/{id}', [DashboardController::class, 'downloadReport'])->name('fin-formation.download-report');

    // Certificat
    Route::get('/certificate/preview', [DashboardController::class, 'previewCertificateStudent'])->name('certificate.preview');
    Route::get('/certificate/download', [DashboardController::class, 'downloadCertificate'])->name('certificate.download');

    // Paramètres
    Route::get('/parametres/index', [App\Http\Controllers\ProfileController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [App\Http\Controllers\ProfileController::class, 'update'])->name('parametres.update');
    Route::post('/parametres/update-login', [App\Http\Controllers\ProfileController::class, 'updateLoginInfo'])->name('parametres.update-login');
    Route::post('/parametres/upload-photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('parametres.upload-photo');

    // Actualités
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');
    Route::get('/actualites/{id}', [DashboardController::class, 'actualitesShow'])->name('actualites.show');

    // Documents
    Route::get('/documents/index', [DashboardController::class, 'documentsIndex'])->name('documents.index');
    Route::get('/documents/download/{id}', [DashboardController::class, 'downloadDocument'])->name('documents.download');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notificationsFeed'])->name('notifications.feed');
    Route::post('/notifications/mark-read', [DashboardController::class, 'notificationsMarkRead'])->name('notifications.mark-read');
    Route::get('/notifications/toutes', [DashboardController::class, 'notificationsIndex'])->name('notifications.index');
});

// Groupe de routes pour Community Management avec préfixe commun (PROTÉGÉ PAR AUTH + VÉRIFICATION COMPTE ACTIF + ACCÈS FORMATION)
Route::prefix('/evc/compte/community-management')->name('community-management.')->middleware(['auth', 'student.active', 'formation.access'])->group(function () {
    // Profil - Structure: /evc/compte/community-management/profil/{action}
    Route::get('/profil/editer/{id?}', [DashboardController::class, 'editProfile'])->name('profil.editer');
    Route::post('/profil/editer/{id?}', [DashboardController::class, 'updateProfile'])->name('profil.update');

    // CVThèque - Structure: /evc/compte/community-management/cvtheque/{action}
    Route::get('/cvtheque/index', [CVThequeController::class, 'index'])->name('cvtheque.index');
    Route::get('/cvtheque/historique', [CVThequeController::class, 'historique'])->name('cvtheque.historique');
    Route::post('/cvtheque/update-profile', [CVThequeController::class, 'updateProfile'])->name('cvtheque.update-profile');

    // API Routes pour la gestion des documents - Architecture structurée
    Route::delete('/cvtheque/documents/delete', [CVThequeController::class, 'deleteDocument'])->name('cvtheque.documents.delete');
    Route::get('/cvtheque/documents/export', [CVThequeController::class, 'exportDocuments'])->name('cvtheque.documents.export');
    Route::get('/cvtheque/preview', [CVThequeController::class, 'preview'])->name('cvtheque.preview');
    Route::get('/cvtheque/profile-display', [CVThequeController::class, 'profileDisplay'])->name('cvtheque.profile-display');
    Route::get('/cvtheque/mon-profil', [CVThequeController::class, 'monProfil'])->name('cvtheque.mon-profil');
    Route::post('/cvtheque/upload-cv', [CVThequeController::class, 'uploadCV'])->name('cvtheque.upload-cv');
    Route::post('/cvtheque/upload-motivation', [CVThequeController::class, 'uploadMotivation'])->name('cvtheque.upload-motivation');
    Route::post('/cvtheque/upload-realisation', [CVThequeController::class, 'uploadRealisations'])->name('cvtheque.upload-realisation');
    Route::post('/cvtheque/upload-pressbook', [CVThequeController::class, 'uploadPressbook'])->name('cvtheque.upload-pressbook');
    Route::post('/cvtheque/upload-rapport', [CVThequeController::class, 'uploadRapport'])->name('cvtheque.upload-rapport');

    // TP (Travaux Pratiques) - Structure: /evc/compte/community-management/tp/{action}
    Route::get('/tp/index', [DashboardController::class, 'listTP'])->name('tp.index');
    Route::get('/tp/tous', [DashboardController::class, 'showAllTP'])->name('tp.tous');
    Route::get('/tp/voir/{id}', [DashboardController::class, 'viewTP'])->name('tp.voir');
    Route::get('/tp/ajouter', [DashboardController::class, 'createTP'])->name('tp.ajouter');
    Route::post('/tp/ajouter', [DashboardController::class, 'storeTP'])->name('tp.store');
    Route::get('/tp/modifier/{id}', [DashboardController::class, 'editTP'])->name('tp.modifier');
    Route::get('/tp/{id}/soumettre', [DashboardController::class, 'showSubmitPage'])->name('tp.soumettre');
    Route::post('/tp/{id}/submit', [DashboardController::class, 'submitTP'])->name('tp.submit');

    // Routes pour les projets de community management
    Route::get('/projets', [DashboardController::class, 'projets'])->name('projets.index');
    Route::post('/projets', [App\Http\Controllers\DesignProjectController::class, 'store'])->name('projets.store');
    Route::get('/projets/stats/json', [App\Http\Controllers\DesignProjectController::class, 'getStats'])->name('projets.stats');
    Route::get('/projets/historique', [App\Http\Controllers\DesignProjectController::class, 'historique'])->name('projets.historique');
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
    Route::delete('/tp/{tpId}/fichier/{fileId}', [DashboardController::class, 'deleteTPFile'])->name('tp.fichier.supprimer');
    Route::delete('/tp/supprimer/{id}', [DashboardController::class, 'deleteProject'])->name('tp.supprimer');

    // To Do List - Structure: /evc/compte/community-management/todo/{action}
    Route::get('/todo/index', [DashboardController::class, 'todoIndex'])->name('todo.index');

    // Traiter un projet assigné (publication)
    Route::get('/todo/traiter/{projectId}', [DashboardController::class, 'traiterAssignedProject'])->name('todo.traiter');
    Route::post('/todo/traiter/{projectId}', [DashboardController::class, 'storeTreatedAssignedProject'])->name('todo.traiter.store');

    // Programme - Structure: /evc/compte/community-management/programme/{action}
    Route::get('/programme/index', [DashboardController::class, 'programmeIndex'])->name('programme.index');
    Route::get('/programme/{id}', [DashboardController::class, 'programmeShow'])->whereNumber('id')->name('programme.show');
    Route::get('/programme/formation/{slug}', [DashboardController::class, 'programmeFormation'])->name('programme.formation');

    // Bibliothèque - Structure: /evc/compte/community-management/bibliotheque/{action}
    Route::get('/bibliotheque/index', [DashboardController::class, 'bibliothequeIndex'])->name('bibliotheque.index');
    Route::get('/bibliotheque/download/{id}', [DashboardController::class, 'downloadDocument'])->name('bibliotheque.download');

    // Paiements - Structure: /evc/compte/community-management/paiements/{action}
    Route::get('/paiements/index', [DashboardController::class, 'paiementsIndex'])->name('paiements.index');
    Route::get('/paiements/invoice/{paymentId}', [DashboardController::class, 'downloadInvoice'])->name('paiements.invoice');

    // Fin de formation - Structure: /evc/compte/community-management/fin-formation/{action}
    Route::get('/fin-formation/index', [DashboardController::class, 'finFormationIndex'])->name('fin-formation.index');
    Route::post('/fin-formation/upload-report', [DashboardController::class, 'uploadReport'])->name('fin-formation.upload-report');
    Route::get('/fin-formation/download-report/{id}', [DashboardController::class, 'downloadReport'])->name('fin-formation.download-report');

    // Certificat - Téléchargement et prévisualisation
    Route::get('/certificate/preview', [DashboardController::class, 'previewCertificateStudent'])->name('certificate.preview');
    Route::get('/certificate/download', [DashboardController::class, 'downloadCertificate'])->name('certificate.download');

    // Paramètres - Structure: /evc/compte/community-management/parametres/{action}
    Route::get('/parametres/index', [App\Http\Controllers\ProfileController::class, 'index'])->name('parametres.index');
    Route::post('/parametres', [App\Http\Controllers\ProfileController::class, 'update'])->name('parametres.update');
    Route::post('/parametres/update-login', [App\Http\Controllers\ProfileController::class, 'updateLoginInfo'])->name('parametres.update-login');
    Route::post('/parametres/upload-photo', [App\Http\Controllers\ProfileController::class, 'uploadPhoto'])->name('parametres.upload-photo');

    // Actualités - Structure: /evc/compte/community-management/actualites/{action}
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');
    Route::get('/actualites/{id}', [DashboardController::class, 'showActualite'])->name('actualites.show');

    // Communauté - Structure: /evc/compte/community-management/communaute/{action}
    Route::get('/communaute/index', [DashboardController::class, 'communauteIndex'])->name('communaute.index');

    // Formations - Structure: /evc/compte/community-management/formations/{action}
    Route::get('/formations/index', [DashboardController::class, 'formationsIndex'])->name('formations.index');
    Route::get('/formations/category/{category}', [DashboardController::class, 'formationsCategory'])->name('formations.category');
    Route::get('/formations/show/{id}', [DashboardController::class, 'formationsShow'])->name('formations.show');
    Route::get('/formations/download/{id}', [DashboardController::class, 'formationsDownload'])->name('formations.download');
    Route::get('/formations/download-all/{id}', [DashboardController::class, 'formationsDownloadAll'])->name('formations.download-all');

    // Events - Structure: /evc/compte/community-management/events/{action}
    Route::get('/events/index', [DashboardController::class, 'eventsIndex'])->name('events.index');
    Route::get('/events/{id}', [DashboardController::class, 'eventsShow'])->where('id', '[0-9]+')->name('events.show');

    // Actualités - Structure: /evc/compte/community-management/actualites/{action}
    Route::get('/actualites/index', [DashboardController::class, 'actualitesIndex'])->name('actualites.index');
    Route::get('/actualites/{id}', [DashboardController::class, 'actualitesShow'])->name('actualites.show');

    // Documents - Structure: /evc/compte/community-management/documents/{action}
    Route::get('/documents/index', [DashboardController::class, 'documentsIndex'])->name('documents.index');
    Route::get('/documents/download/{id}', [DashboardController::class, 'downloadDocument'])->name('documents.download');

    // Notifications
    Route::get('/notifications', [DashboardController::class, 'notificationsFeed'])->name('notifications.feed');
    Route::post('/notifications/mark-read', [DashboardController::class, 'notificationsMarkRead'])->name('notifications.mark-read');
    Route::get('/notifications/toutes', [DashboardController::class, 'notificationsIndex'])->name('notifications.index');
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

        Route::get('/partnerships', [PartnershipsAdminController::class, 'index'])->name('partnerships.index');
        Route::get('/partnerships/create', [PartnershipsAdminController::class, 'create'])->name('partnerships.create');
        Route::post('/partnerships', [PartnershipsAdminController::class, 'store'])->name('partnerships.store');
        Route::get('/partnerships/{partnership}/edit', [PartnershipsAdminController::class, 'edit'])->name('partnerships.edit');
        Route::put('/partnerships/{partnership}', [PartnershipsAdminController::class, 'update'])->name('partnerships.update');
        Route::post('/partnerships/{partnership}/delete-document', [PartnershipsAdminController::class, 'deleteDocument'])->name('partnerships.document.delete');

        Route::get('/plaquettes', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'index'])->name('plaquettes.index');
        Route::get('/plaquettes/create', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'create'])->name('plaquettes.create');
        Route::post('/plaquettes', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'store'])->name('plaquettes.store');
        Route::get('/plaquettes/{plaquette}/edit', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'edit'])->name('plaquettes.edit');
        Route::put('/plaquettes/{plaquette}', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'update'])->name('plaquettes.update');
        Route::delete('/plaquettes/{plaquette}', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'destroy'])->name('plaquettes.delete');
        Route::post('/plaquettes/{plaquette}/toggle-publish', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'togglePublish'])->name('plaquettes.toggle-publish');
        Route::post('/plaquettes/{plaquette}/toggle-active', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'toggleActive'])->name('plaquettes.toggle-active');
        Route::get('/plaquettes/{plaquette}/download', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'download'])->name('plaquettes.download');

        Route::get('/plaquettes/requests', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'requestsIndex'])->name('plaquettes.requests.index');
        Route::get('/plaquettes/requests/{plaquetteRequest}', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'requestShow'])->name('plaquettes.requests.show');
        Route::post('/plaquettes/requests/{plaquetteRequest}/approve', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'approveRequest'])->name('plaquettes.requests.approve');
        Route::post('/plaquettes/requests/{plaquetteRequest}/reject', [\App\Http\Controllers\Admin\PlaquettesAdminController::class, 'rejectRequest'])->name('plaquettes.requests.reject');

        Route::get('/assistant/tasks', [App\Http\Controllers\Admin\AssistantTasksController::class, 'index'])->name('assistant.tasks.index');
        Route::post('/assistant/tasks', [App\Http\Controllers\Admin\AssistantTasksController::class, 'store'])->name('assistant.tasks.store');
        Route::get('/payroll/task-history', [App\Http\Controllers\Admin\AdminTaskHistoryController::class, 'index'])->name('payroll.task-history.index');
        Route::post('/salaries/assistant/{adminId}/compliance', [App\Http\Controllers\Admin\AdminSalaryController::class, 'toggleAssistantMonthCompliance'])->name('salaries.assistant.compliance');

        Route::get('/payroll', [App\Http\Controllers\Admin\AdminPayrollController::class, 'index'])->name('payroll.index');
        Route::get('/payroll/admin/{adminId}', [App\Http\Controllers\Admin\AdminPayrollController::class, 'showAdmin'])->name('payroll.admin.show');
        Route::get('/payroll/admin/{adminId}/profiles', [App\Http\Controllers\Admin\AdminPayrollController::class, 'editAdminProfiles'])->name('payroll.admin.profiles.edit');
        Route::post('/payroll/admin/{adminId}/profiles', [App\Http\Controllers\Admin\AdminPayrollController::class, 'updateAdminProfiles'])->name('payroll.admin.profiles');
        Route::post('/payroll/admin/{adminId}/visibility', [App\Http\Controllers\Admin\AdminPayrollController::class, 'updateSalaryVisibility'])->name('payroll.admin.visibility');
        Route::get('/payroll/me', [App\Http\Controllers\Admin\AdminPayrollController::class, 'me'])->name('payroll.me');

        Route::get('/payroll/settings', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'index'])->name('payroll.settings.index');
        Route::get('/payroll/settings/profile/create', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'createProfile'])->name('payroll.settings.profile.create');
        Route::post('/payroll/settings/profile', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'storeProfile'])->name('payroll.settings.profile.store');
        Route::get('/payroll/settings/profile/{profileId}', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'editProfile'])->name('payroll.settings.profile.edit');
        Route::post('/payroll/settings/profile/{profileId}', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'updateProfile'])->name('payroll.settings.profile.update');
        Route::post('/payroll/settings/profile/{profileId}/delete', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'destroyProfile'])->name('payroll.settings.profile.delete');
        Route::get('/payroll/settings/profile/{profileId}/tasks', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'profileTasks'])->name('payroll.settings.profile.tasks');
        Route::post('/payroll/settings/profile/{profileId}/tasks/catalog', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'storeTaskCatalog'])->name('payroll.settings.profile.tasks.catalog.store');
        Route::post('/payroll/settings/profile/{profileId}/tasks', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'storeTaskType'])->name('payroll.settings.profile.tasks.store');
        Route::post('/payroll/settings/task/{taskTypeId}', [App\Http\Controllers\Admin\AdminPayrollSettingsController::class, 'updateTaskType'])->name('payroll.settings.task.update');

        Route::get('/dons', [DonationAdminController::class, 'index'])->name('donations.index');
        Route::get('/dons/{id}', [DonationAdminController::class, 'show'])->whereNumber('id')->name('donations.show');
        Route::post('/dons/{id}/send-reminder', [DonationAdminController::class, 'sendReminder'])->whereNumber('id')->name('donations.send-reminder');

        // Dernières connexions étudiants (liste paginée)
        Route::get('/connexions', [AdminDashboardController::class, 'connexions'])->name('connexions.index');

        Route::get('/studio-creative', [AdminDashboardController::class, 'studioCreative'])->name('studio-creative');

        // Routes pour les boutons "Actions Rapides" du dashboard
        Route::get('/travaux/pending', [AdminDashboardController::class, 'travauxPending'])->name('travaux.quick.pending');
        Route::get('/travaux/all', [AdminDashboardController::class, 'travauxAll'])->name('travaux.quick.all');
        Route::get('/rapports', [AdminDashboardController::class, 'rapports'])->name('rapports.quick');
        Route::get('/bibliotheque', [AdminDashboardController::class, 'bibliotheque'])->name('bibliotheque.quick.index');
        Route::get('/parametres', [AdminDashboardController::class, 'parametres'])->name('parametres.quick.index');

        // Gestion des paiements
        Route::get('/payments', [\App\Http\Controllers\Admin\PaymentAdminController::class, 'index'])->name('payments.index');
        Route::get('/payments/{id}', [\App\Http\Controllers\Admin\PaymentAdminController::class, 'show'])->name('payments.show');

        // Page toutes les statistiques
        Route::get('/statistiques/all', [AdminStatisticsDetailController::class, 'allStatistics'])->name('statistics.all');

        // Pages statistiques spécifiques avec contrôleur dédié (DOIVENT être avant la route générique)
        Route::get('/statistiques/total-students', [AdminStatisticsController::class, 'totalStudents'])->name('statistics.total-students');
        Route::get('/statistiques/total-formations', [AdminStatisticsController::class, 'totalFormations'])->name('statistics.total-formations');
        Route::get('/statistiques/total-projects', [AdminStatisticsController::class, 'totalProjects'])->name('statistics.total-projects');
        Route::get('/statistiques/total-admins', [AdminStatisticsDetailController::class, 'totalAdmins'])->name('statistics.total-admins');

        // Routes de gestion des administrateurs (CRUD)
        Route::get('/admins/create', [App\Http\Controllers\Admin\AdminManagementController::class, 'create'])->name('admins.create');
        Route::post('/admins', [App\Http\Controllers\Admin\AdminManagementController::class, 'store'])->name('admins.store');
        Route::get('/admins/{id}/edit', [App\Http\Controllers\Admin\AdminManagementController::class, 'edit'])->name('admins.edit');
        Route::put('/admins/{id}', [App\Http\Controllers\Admin\AdminManagementController::class, 'update'])->name('admins.update');
        Route::delete('/admins/{id}', [App\Http\Controllers\Admin\AdminManagementController::class, 'destroy'])->name('admins.destroy');

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
        // Routes étudiants commentées - méthodes inexistantes dans AdminDashboardController
        // Utiliser StudentAdminController à la place (voir lignes 97-111)
        // Route::post('/students/add', [AdminDashboardController::class, 'addStudent'])->name('students.add');
        // Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        // Route::get('/students/create', [AdminDashboardController::class, 'showAddStudent'])->name('students.create');
        // Route::post('/students/bulk-email', [AdminDashboardController::class, 'sendBulkEmail'])->name('students.bulk-email');
        // Route::get('/students/export', [AdminDashboardController::class, 'exportStudents'])->name('students.export');
        // Route::post('/students/suspend/{id}', [AdminDashboardController::class, 'suspendStudent'])->name('students.suspend');
        // Route::post('/students/activate/{id}', [AdminDashboardController::class, 'activateStudent'])->name('students.activate');

        // Communiqués
        Route::resource('communiques', App\Http\Controllers\Admin\CommuniqueController::class)->except(['show']);
        Route::patch('/communiques/{communique}/toggle-status', [App\Http\Controllers\Admin\CommuniqueController::class, 'toggleStatus'])->name('communiques.toggle-status');

        // Gestion de la Comptabilité
        Route::get('/comptabilite', [App\Http\Controllers\Admin\AccountingController::class, 'index'])->name('accounting.index');
        Route::get('/comptabilite/depenses', [App\Http\Controllers\Admin\AccountingController::class, 'expenses'])->name('accounting.expenses');
        Route::get('/comptabilite/depenses/create', [App\Http\Controllers\Admin\AccountingController::class, 'createExpense'])->name('accounting.expenses.create');
        Route::get('/comptabilite/ventes', [App\Http\Controllers\Admin\AccountingController::class, 'sales'])->name('accounting.sales');
        Route::get('/comptabilite/ventes/create', [App\Http\Controllers\Admin\AccountingController::class, 'createSale'])->name('accounting.sales.create');
        Route::get('/comptabilite/rapport', [App\Http\Controllers\Admin\AccountingController::class, 'report'])->name('accounting.report');
        Route::get('/comptabilite/export', [App\Http\Controllers\Admin\AccountingController::class, 'export'])->name('accounting.export');
        Route::get('/comptabilite/grand-livre', [App\Http\Controllers\Admin\AccountingController::class, 'generalLedger'])->name('accounting.general-ledger');
        Route::get('/comptabilite/budgets', [App\Http\Controllers\Admin\AccountingController::class, 'budgets'])->name('accounting.budgets');
        Route::get('/comptabilite/budgets/create', [App\Http\Controllers\Admin\AccountingController::class, 'createBudget'])->name('accounting.budgets.create');
        Route::post('/comptabilite/budgets', [App\Http\Controllers\Admin\AccountingController::class, 'storeBudget'])->name('accounting.budgets.store');
        Route::post('/comptabilite/transaction', [App\Http\Controllers\Admin\AccountingController::class, 'store'])->name('accounting.store');
        Route::delete('/comptabilite/transaction/{id}', [App\Http\Controllers\Admin\AccountingController::class, 'destroy'])->name('accounting.destroy');

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
        Route::get('/preinscriptions/pending', [PreRegistrationAdminController::class, 'pending'])->name('preinscriptions.pending');
        Route::get('/preinscriptions/accepted', [PreRegistrationAdminController::class, 'accepted'])->name('preinscriptions.accepted');
        Route::get('/preinscriptions/rejected', [PreRegistrationAdminController::class, 'rejected'])->name('preinscriptions.rejected');
        Route::get('/preinscriptions/export', [PreRegistrationAdminController::class, 'export'])->name('preinscriptions.export');
        Route::get('/preinscriptions/{id}/devis', [PreRegistrationAdminController::class, 'devis'])->name('preinscriptions.devis');
        Route::get('/preinscriptions/{id}', [PreRegistrationAdminController::class, 'show'])->name('preinscriptions.show');
        Route::get('/preinscriptions/{id}/edit', [PreRegistrationAdminController::class, 'edit'])->name('preinscriptions.edit');
        Route::put('/preinscriptions/{id}', [PreRegistrationAdminController::class, 'update'])->name('preinscriptions.update');
        Route::get('/preinscriptions/{id}/payment', [PreRegistrationAdminController::class, 'payment'])->name('preinscriptions.payment');
        Route::post('/preinscriptions/bulk-status', [PreRegistrationAdminController::class, 'bulkStatus'])->name('preinscriptions.bulk-status');
        Route::get('/preinscriptions/{id}/download-photo', [PreRegistrationAdminController::class, 'downloadPhoto'])->name('preinscriptions.download-photo');
        Route::post('/preinscriptions/{id}/validate', [PreRegistrationAdminController::class, 'validateOne'])->name('preinscriptions.validate');
        Route::post('/preinscriptions/{id}/accept', [PreRegistrationAdminController::class, 'acceptCandidate'])->name('preinscriptions.accept');
        Route::post('/preinscriptions/{id}/reject', [PreRegistrationAdminController::class, 'rejectCandidate'])->name('preinscriptions.reject');
        Route::post('/preinscriptions/{id}/manual-payment', [PreRegistrationAdminController::class, 'manualPayment'])->name('preinscriptions.manual-payment');
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
        Route::post('/projects/{id}/add-student', [AdminDashboardController::class, 'addStudentToProject'])->name('projects.add-student');
        Route::delete('/projects/assigned/{id}', [AdminDashboardController::class, 'deleteProject'])->name('projects.assigned.delete');
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
        Route::post('/design-projects/reject/{id}', [AdminDashboardController::class, 'rejectDesignProject'])->name('design-projects.reject');
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
        Route::get('/tp/edit/{id}', [AdminDashboardController::class, 'editTp'])->name('tp.edit');
        Route::put('/tp/update/{id}', [AdminDashboardController::class, 'updateTp'])->name('tp.update');
        Route::delete('/tp/delete/{id}', [AdminDashboardController::class, 'deleteTp'])->name('tp.delete');
        Route::patch('/travaux/{id}/update-status', [AdminDashboardController::class, 'updateTpStatus'])->name('travaux.update-status');

        // API - Étudiants connectés en temps réel
        Route::get('/api/online-students', [AdminDashboardController::class, 'getOnlineStudents'])->name('api.online-students');

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

        // Admins - Anciennes routes désactivées (remplacées par AdminManagementController aux lignes 279-283)
        // Toutes les routes de gestion des admins sont maintenant centralisées dans AdminManagementController

        // Routes pour la gestion des étudiants - COMMENTÉES (méthodes inexistantes)
        // Route::get('/students/add', [AdminDashboardController::class, 'showAddStudent'])->name('students.add');
        // Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        // Route::get('/students/by-formation/{formation}', [AdminDashboardController::class, 'studentsByFormation'])->name('students.by-formation');
        // routes unifiées pour profil/édition définies en dehors de ce groupe
        // Route::put('/students/update/{id}', [AdminDashboardController::class, 'updateStudent'])->name('students.update');
        // Route::get('/students/export-pdf', [AdminDashboardController::class, 'exportStudentsPdf'])->name('students.export-pdf');
        // Route::get('/students/export-excel', [AdminDashboardController::class, 'exportStudentsExcel'])->name('students.export-excel');
        // Route::get('/students/settings', [AdminDashboardController::class, 'studentsSettings'])->name('students.settings');
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
        Route::get('/etudiants/design-graphique-community-manager', [AdminDashboardController::class, 'studentsDesignGraphiqueCommunityManager'])->name('etudiants.design-graphique-community-manager');
        Route::get('/etudiants/intelligence-artificielle', [AdminDashboardController::class, 'studentsIA'])->name('etudiants.intelligence-artificielle');
        Route::get('/etudiants/gestion-informatique', [AdminDashboardController::class, 'studentsGestionInfo'])->name('etudiants.gestion-informatique');

        // Gestion des Catégories de la Bibliothèque (Définition Manuelle)
        Route::get('/bibliotheque/categories', [LibraryCategoryController::class, 'index'])->name('bibliotheque.categories.index');
        Route::get('/bibliotheque/categories/create', [LibraryCategoryController::class, 'create'])->name('bibliotheque.categories.create');
        Route::post('/bibliotheque/categories', [LibraryCategoryController::class, 'store'])->name('bibliotheque.categories.store');
        Route::get('/bibliotheque/categories/{id}/edit', [LibraryCategoryController::class, 'edit'])->name('bibliotheque.categories.edit');
        Route::put('/bibliotheque/categories/{id}', [LibraryCategoryController::class, 'update'])->name('bibliotheque.categories.update');
        Route::delete('/bibliotheque/categories/{id}', [LibraryCategoryController::class, 'destroy'])->name('bibliotheque.categories.destroy');

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
        // Route pour afficher tous les travaux pratiques
        Route::get('/travaux/all', [AdminDashboardController::class, 'travauxAll'])->name('travaux.all');
        // Route pour envoyer des TP aux étudiants
        Route::get('/travaux/to-send', [AdminDashboardController::class, 'travauxToSend'])->name('travaux.to-send');
        Route::post('/travaux/send', [AdminDashboardController::class, 'sendTravaux'])->name('travaux.send');
        // Route pour afficher les TP envoyés par l'admin
        Route::get('/travaux/assigned', [AdminDashboardController::class, 'travauxAssigned'])->name('travaux.assigned');
        // Route pour afficher les détails d'un TP assigné
        Route::get('/travaux/assignment/{title}', [AdminDashboardController::class, 'assignmentDetail'])->name('travaux.assignment.detail');
        // Route pour modifier un TP assigné
        Route::get('/travaux/assignment/{title}/edit', [AdminDashboardController::class, 'editAssignment'])->name('travaux.assignment.edit');
        Route::put('/travaux/assignment/{title}', [AdminDashboardController::class, 'updateAssignment'])->name('travaux.assignment.update');
        // Route pour supprimer un TP assigné
        Route::delete('/travaux/assignment/{title}', [AdminDashboardController::class, 'deleteAssignment'])->name('travaux.assignment.delete');
        // Route pour supprimer un rapport/TP
        Route::delete('/travaux/{id}/delete', [AdminDashboardController::class, 'deleteTp'])->name('travaux.delete');
        // Routes pour voir et éditer un TP
        Route::get('/tp/view/{id}', [AdminDashboardController::class, 'viewTp'])->name('tp.view');
        Route::get('/tp/edit/{id}', [AdminDashboardController::class, 'editTp'])->name('tp.edit');
        Route::put('/tp/update/{id}', [AdminDashboardController::class, 'updateTp'])->name('tp.update');
        Route::delete('/tp/delete/{id}', [AdminDashboardController::class, 'deleteTp'])->name('tp.delete');

        // Gestion des Activités
        Route::get('/activites', [AdminDashboardController::class, 'activites'])->name('activites.index');

        // Route de debug pour les étudiants
        Route::get('/debug/students', [AdminDashboardController::class, 'debugStudents'])->name('debug.students');
        // Test Webhook CinetPay (admin only - mode développement)
        Route::get('/test/webhook', function () {
            return view('admin.test_webhook');
        })->name('test.webhook');
        Route::post('/test/simulate-webhook', [\App\Http\Controllers\PaymentController::class, 'simulateWebhook'])->name('test.simulate-webhook');

        // Gestion 2ème tranche (après 2 mois de formation)
        Route::get('/second-installment-manager', function () {
            return view('admin.second_installment_manager');
        })->name('second-installment.manager');
        Route::post('/send-second-installment-email', [\App\Http\Controllers\PaymentController::class, 'sendSecondInstallmentEmailManual'])->name('second-installment.send');

        // Gestion des Programmes
        Route::get('/programmes', [AdminDashboardController::class, 'programmes'])->name('programmes');
        Route::get('/programmes/create', [AdminDashboardController::class, 'createProgramme'])->name('programmes.create');
        Route::post('/programmes', [AdminDashboardController::class, 'storeProgramme'])->name('programmes.store');
        Route::get('/programmes/{id}/edit', [AdminDashboardController::class, 'editProgramme'])->whereNumber('id')->name('programmes.edit');
        Route::put('/programmes/{id}', [AdminDashboardController::class, 'updateProgramme'])->whereNumber('id')->name('programmes.update');
        Route::delete('/programmes/{id}', [AdminDashboardController::class, 'destroyProgramme'])->name('programmes.destroy');

        // Gestion des Projets
        Route::get('/projets/pending', [AdminDashboardController::class, 'projetsPending'])->name('projets.pending');
        Route::get('/projets/pending/{id}', [AdminDashboardController::class, 'showTpDetails'])->name('projets.pending.show');
        Route::post('/projets/pending/{id}/validate', [AdminDashboardController::class, 'validateTp'])->name('projets.pending.validate');
        Route::post('/projets/pending/{id}/reject', [AdminDashboardController::class, 'rejectTp'])->name('projets.pending.reject');
        Route::get('/projets/to-send', [AdminDashboardController::class, 'projetsToSend'])->name('projets.to-send');
        Route::post('/projets/send', [AdminDashboardController::class, 'sendProjects'])->name('projets.send');
        Route::get('/projets/all', [AdminDashboardController::class, 'projetsAll'])->name('projets.all');

        // Gestion des Badges
        Route::get('/badges/students/active', [\App\Http\Controllers\Admin\BadgeAdminController::class, 'active'])->name('badges.students.active');
        Route::get('/badges/students/inactive', [\App\Http\Controllers\Admin\BadgeAdminController::class, 'inactive'])->name('badges.students.inactive');
        Route::get('/badges/students/top-performers', [\App\Http\Controllers\Admin\BadgeAdminController::class, 'topPerformers'])->name('badges.students.top-performers');
        Route::get('/badges/students/list', [\App\Http\Controllers\Admin\BadgeAdminController::class, 'studentsList'])->name('badges.students.list');
        Route::get('/badges/students/{id}/generate', [\App\Http\Controllers\Admin\BadgeAdminController::class, 'generate'])->whereNumber('id')->name('badges.generate');

        // Gestion des Articles - Événements
        Route::get('/articles/evenements', [App\Http\Controllers\Admin\EvenementController::class, 'index'])->name('articles.evenements');
        Route::get('/articles/evenements/create', [App\Http\Controllers\Admin\EvenementController::class, 'create'])->name('articles.evenements.create');
        Route::post('/articles/evenements', [App\Http\Controllers\Admin\EvenementController::class, 'store'])->name('articles.evenements.store');
        Route::get('/articles/evenements/{evenement}', [App\Http\Controllers\Admin\EvenementController::class, 'show'])->name('articles.evenements.show');
        Route::get('/articles/evenements/{evenement}/edit', [App\Http\Controllers\Admin\EvenementController::class, 'edit'])->name('articles.evenements.edit');
        Route::put('/articles/evenements/{evenement}', [App\Http\Controllers\Admin\EvenementController::class, 'update'])->name('articles.evenements.update');
        Route::delete('/articles/evenements/{evenement}', [App\Http\Controllers\Admin\EvenementController::class, 'destroy'])->name('articles.evenements.destroy');
        Route::patch('/articles/evenements/{evenement}/toggle-status', [App\Http\Controllers\Admin\EvenementController::class, 'toggleStatus'])->name('articles.evenements.toggle-status');
        Route::patch('/articles/evenements/{evenement}/toggle-featured', [App\Http\Controllers\Admin\EvenementController::class, 'toggleFeatured'])->name('articles.evenements.toggle-featured');

        // Gestion des Articles - Actualités
        Route::get('/articles/actualites', [App\Http\Controllers\Admin\ActualiteController::class, 'index'])->name('articles.actualites');
        Route::get('/articles/actualites/create', [App\Http\Controllers\Admin\ActualiteController::class, 'create'])->name('articles.actualites.create');
        Route::post('/articles/actualites', [App\Http\Controllers\Admin\ActualiteController::class, 'store'])->name('articles.actualites.store');
        Route::get('/articles/actualites/{actualite}', [App\Http\Controllers\Admin\ActualiteController::class, 'show'])->name('articles.actualites.show');
        Route::get('/articles/actualites/{actualite}/edit', [App\Http\Controllers\Admin\ActualiteController::class, 'edit'])->name('articles.actualites.edit');
        Route::put('/articles/actualites/{actualite}', [App\Http\Controllers\Admin\ActualiteController::class, 'update'])->name('articles.actualites.update');
        Route::delete('/articles/actualites/{actualite}', [App\Http\Controllers\Admin\ActualiteController::class, 'destroy'])->name('articles.actualites.destroy');
        Route::patch('/articles/actualites/{actualite}/toggle-status', [App\Http\Controllers\Admin\ActualiteController::class, 'toggleStatus'])->name('articles.actualites.toggle-status');
        Route::patch('/articles/actualites/{actualite}/toggle-featured', [App\Http\Controllers\Admin\ActualiteController::class, 'toggleFeatured'])->name('articles.actualites.toggle-featured');

        // API IA pour génération SEO (dans le groupe admin)
        Route::post('/api/generate-seo', [App\Http\Controllers\Admin\AiSeoController::class, 'generateSeo'])->name('api.generate-seo');

        // Gestion des Certificats
        Route::get('/certificats/eligible', [AdminDashboardController::class, 'certificatsEligible'])->name('certificats.eligible');
        Route::get('/certificats/not-eligible', [AdminDashboardController::class, 'certificatsNotEligible'])->name('certificats.not-eligible');
        Route::get('/certificats/generate/{id}', [AdminDashboardController::class, 'generateCertificate'])->name('certificats.generate');
        Route::get('/certificats/preview/{id}', [AdminDashboardController::class, 'previewCertificate'])->name('certificats.preview');

        // Gestion des Paiements
        Route::get('/paiements/a-jour', [AdminDashboardController::class, 'paiementsAJour'])->name('paiements.a-jour');
        Route::get('/paiements/a-solder', [AdminDashboardController::class, 'paiementsASolder'])->name('paiements.a-solder');
        Route::get('/paiements/recu/{preRegistrationId}', [AdminDashboardController::class, 'downloadPaymentReceipt'])->name('paiements.receipt');
        Route::get('/paiements/a-solder/{preRegistrationId}/edit-restant', [AdminDashboardController::class, 'editPaiementRestant'])->name('paiements.a-solder.edit-restant');
        Route::post('/paiements/a-solder/{preRegistrationId}/update-restant', [AdminDashboardController::class, 'updatePaiementRestant'])->name('paiements.a-solder.update-restant');
        Route::get('/paiements/reste-a-payer', [AdminDashboardController::class, 'paiementsResteAPayer'])->name('paiements.reste-a-payer');
        Route::post('/paiements/send-reminder/{id}', [AdminDashboardController::class, 'sendPaymentReminder'])->name('paiements.send-reminder');

        // Gestion des CVthèque
        Route::get('/cvtheque', [CVThequeAdminController::class, 'index'])->name('cvtheque.profiles');
        Route::get('/cvtheque/profil/{id}', [CVThequeAdminController::class, 'show'])->name('cvtheque.show');
        Route::get('/cvtheque/download/{id}/{type}', [CVThequeAdminController::class, 'downloadFile'])->name('cvtheque.download');
        Route::get('/cvtheque/export', [CVThequeAdminController::class, 'export'])->name('cvtheque.export');

        // Gestion des Candidatures Collaborateurs
        Route::get('/candidatures/collaborateurs', [App\Http\Controllers\Admin\CandidatureCollaborateurController::class, 'index'])->name('candidatures.collaborateurs.index');
        Route::get('/candidatures/collaborateurs/{id}', [App\Http\Controllers\Admin\CandidatureCollaborateurController::class, 'show'])->name('candidatures.collaborateurs.show');
        Route::post('/candidatures/collaborateurs/{id}/statut', [App\Http\Controllers\Admin\CandidatureCollaborateurController::class, 'updateStatut'])->name('candidatures.collaborateurs.update-statut');
        Route::get('/candidatures/collaborateurs/{id}/download-cv', [App\Http\Controllers\Admin\CandidatureCollaborateurController::class, 'downloadCV'])->name('candidatures.collaborateurs.download-cv');
        Route::delete('/candidatures/collaborateurs/{id}', [App\Http\Controllers\Admin\CandidatureCollaborateurController::class, 'destroy'])->name('candidatures.collaborateurs.destroy');

        // Gestion des Candidatures Formateurs
        Route::get('/candidatures/formateurs', [App\Http\Controllers\Admin\CandidatureFormateurController::class, 'index'])->name('candidatures.formateurs.index');
        Route::get('/candidatures/formateurs/{id}', [App\Http\Controllers\Admin\CandidatureFormateurController::class, 'show'])->name('candidatures.formateurs.show');
        Route::post('/candidatures/formateurs/{id}/statut', [App\Http\Controllers\Admin\CandidatureFormateurController::class, 'updateStatut'])->name('candidatures.formateurs.update-statut');
        Route::get('/candidatures/formateurs/{id}/download-cv', [App\Http\Controllers\Admin\CandidatureFormateurController::class, 'downloadCV'])->name('candidatures.formateurs.download-cv');
        Route::delete('/candidatures/formateurs/{id}', [App\Http\Controllers\Admin\CandidatureFormateurController::class, 'destroy'])->name('candidatures.formateurs.destroy');

        // Gestion des Demandes Partenaires
        Route::get('/demandes/partenariat', [App\Http\Controllers\Admin\DemandePartenariatController::class, 'index'])->name('demandes.partenariat.index');
        Route::get('/demandes/partenariat/{id}', [App\Http\Controllers\Admin\DemandePartenariatController::class, 'show'])->name('demandes.partenariat.show');
        Route::post('/demandes/partenariat/{id}/statut', [App\Http\Controllers\Admin\DemandePartenariatController::class, 'updateStatut'])->name('demandes.partenariat.update-statut');
        Route::delete('/demandes/partenariat/{id}', [App\Http\Controllers\Admin\DemandePartenariatController::class, 'destroy'])->name('demandes.partenariat.destroy');

        // Gestion des Rapports
        Route::get('/rapports', [AdminDashboardController::class, 'rapports'])->name('rapports');
        Route::get('/rapports/index', [AdminDashboardController::class, 'rapports'])->name('reports.index');
        Route::get('/rapports/financier', [AdminDashboardController::class, 'rapportFinancier'])->name('rapports.financier');
        Route::get('/rapports/formations', [AdminDashboardController::class, 'rapportFormations'])->name('rapports.formations');
        Route::get('/rapports/analytics', [AdminDashboardController::class, 'analytics'])->name('rapports.analytics');
        Route::get('/rapports/exports', [AdminDashboardController::class, 'exports'])->name('rapports.exports');
        Route::post('/rapports/generate', [AdminDashboardController::class, 'generateReport'])->name('rapports.generate');
        Route::get('/rapports/download/{type}', [AdminDashboardController::class, 'downloadReport'])->name('rapports.download');

        // Rapports d'activité (public)
        Route::get('/activity-reports', [App\Http\Controllers\Admin\ActivityReportController::class, 'index'])->name('activity-reports.index');
        Route::get('/activity-reports/create', [App\Http\Controllers\Admin\ActivityReportController::class, 'create'])->name('activity-reports.create');
        Route::post('/activity-reports', [App\Http\Controllers\Admin\ActivityReportController::class, 'store'])->name('activity-reports.store');
        Route::get('/activity-reports/{activityReport}/edit', [App\Http\Controllers\Admin\ActivityReportController::class, 'edit'])->name('activity-reports.edit');
        Route::put('/activity-reports/{activityReport}', [App\Http\Controllers\Admin\ActivityReportController::class, 'update'])->name('activity-reports.update');
        Route::delete('/activity-reports/{activityReport}', [App\Http\Controllers\Admin\ActivityReportController::class, 'destroy'])->name('activity-reports.destroy');
        Route::patch('/activity-reports/{activityReport}/toggle', [App\Http\Controllers\Admin\ActivityReportController::class, 'togglePublish'])->name('activity-reports.toggle');
        Route::get('/activity-reports/{activityReport}/download', [App\Http\Controllers\Admin\ActivityReportController::class, 'download'])->name('activity-reports.download');

        // Gestion des Étudiants
        // Route::get('/students', [AdminDashboardController::class, 'students'])->name('students.index'); // COMMENTÉ - Route dupliquée, voir ligne 97 (utilise StudentAdminController::index)
        // Route::get('/students/create', [AdminDashboardController::class, 'createStudent'])->name('students.create');
        // Route::post('/students/store', [AdminDashboardController::class, 'storeStudent'])->name('students.store');
        // Route::get('/students/{id}', [AdminDashboardController::class, 'showStudent'])->name('students.show');
        // route unifiée d'édition définie en dehors de ce groupe
        // Route::put('/students/{id}', [AdminDashboardController::class, 'updateStudent'])->name('students.update');
        // Route::delete('/students/{id}', [AdminDashboardController::class, 'deleteStudent'])->name('students.delete');
        // Route::post('/students/{id}/toggle-status', [AdminDashboardController::class, 'toggleStudentStatus'])->name('students.toggle-status'); // COMMENTÉ - Route dupliquée, voir ligne 107
        // Route::get('/students/by-formation/{formation}', [AdminDashboardController::class, 'studentsByFormation'])->name('students.by-formation'); // COMMENTÉ - Méthode inexistante
        Route::get('/students/{id}/profile', [\App\Http\Controllers\Admin\StudentAdminController::class, 'profile'])->name('students.profile');
        Route::delete('/students/{id}/delete', [\App\Http\Controllers\Admin\StudentAdminController::class, 'destroy'])->name('students.delete');
        // Route::get('/students/add', [AdminDashboardController::class, 'createStudent'])->name('students.add');

        // Routes pour la gestion des projets design (admin)
        // NOTE: renommées pour éviter les collisions de noms avec admin.projects.* (AdminDashboardController)
        Route::get('/projects/{id}', [\App\Http\Controllers\Admin\StudentAdminController::class, 'showProject'])->name('student-projects.show');
        Route::post('/projects/{id}/validate', [\App\Http\Controllers\Admin\StudentAdminController::class, 'validateProject'])->name('student-projects.validate');
        Route::get('/projects/{id}/download', [\App\Http\Controllers\Admin\StudentAdminController::class, 'downloadProject'])->name('student-projects.download');
        Route::delete('/projects/{id}/delete', [\App\Http\Controllers\Admin\StudentAdminController::class, 'deleteProject'])->name('student-projects.delete');

        // Gestion des Admins - Routes désactivées (remplacées par AdminManagementController)
        // Les routes de gestion des admins sont maintenant gérées par AdminManagementController (voir lignes 279-283)
        Route::get('/admins', [AdminStatisticsDetailController::class, 'totalAdmins'])->name('admins.index');

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
        Route::post('/parametres/password', [AdminDashboardController::class, 'updatePassword'])->name('parametres.update-password');
        Route::get('/parametres/system', [AdminDashboardController::class, 'systemSettings'])->name('parametres.system');
        Route::get('/parametres/security', [AdminDashboardController::class, 'securitySettings'])->name('parametres.security');
        Route::get('/parametres/notifications', [AdminDashboardController::class, 'notificationSettings'])->name('parametres.notifications');
        Route::post('/parametres/notifications', [AdminDashboardController::class, 'updateNotifications'])->name('parametres.notifications.update');
        Route::get('/parametres/backup', [AdminDashboardController::class, 'backupSettings'])->name('parametres.backup');
        Route::post('/parametres/backup/create', [AdminDashboardController::class, 'createBackup'])->name('parametres.backup.create');
        Route::get('/parametres/logs', [AdminDashboardController::class, 'systemLogs'])->name('parametres.logs');

        // Projets Design Graphique
        Route::prefix('projets/design-graphique')->name('projets.design-graphique.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Admin\ProjectController::class, 'pendingDesignGraphique'])->name('pending');
            Route::get('/to-send', [App\Http\Controllers\Admin\ProjectController::class, 'toSendDesignGraphique'])->name('to-send');
            Route::get('/assigned', [App\Http\Controllers\Admin\ProjectController::class, 'assignedDesignGraphique'])->name('assigned');
            Route::get('/all', [App\Http\Controllers\Admin\ProjectController::class, 'allDesignGraphique'])->name('all');
        });

        // Projets CM/SMM
        Route::prefix('projets/cm-smm')->name('projets.cm-smm.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Admin\ProjectController::class, 'pendingCmSmm'])->name('pending');
            Route::get('/to-send', [App\Http\Controllers\Admin\ProjectController::class, 'toSendCmSmm'])->name('to-send');
            Route::get('/all', [App\Http\Controllers\Admin\ProjectController::class, 'allCmSmm'])->name('all');
        });

        // Projets Design & CM
        Route::prefix('projets/design-cm')->name('projets.design-cm.')->group(function () {
            Route::get('/pending', [App\Http\Controllers\Admin\ProjectController::class, 'pendingDesignCm'])->name('pending');
            Route::get('/to-send', [App\Http\Controllers\Admin\ProjectController::class, 'toSendDesignCm'])->name('to-send');
            Route::get('/all', [App\Http\Controllers\Admin\ProjectController::class, 'allDesignCm'])->name('all');
        });

        // Gestion des Abonnés WebTV
        Route::get('/webtv/subscribers', [App\Http\Controllers\Admin\WebtvAdminController::class, 'index'])->name('webtv.subscribers');
        Route::get('/webtv/subscribers/{id}', [App\Http\Controllers\Admin\WebtvAdminController::class, 'show'])->name('webtv.show');
        Route::post('/webtv/subscribers/{id}/verify', [App\Http\Controllers\Admin\WebtvAdminController::class, 'verify'])->name('webtv.verify');
        Route::post('/webtv/subscribers/{id}/deactivate', [App\Http\Controllers\Admin\WebtvAdminController::class, 'deactivate'])->name('webtv.deactivate');
        Route::post('/webtv/subscribers/{id}/activate', [App\Http\Controllers\Admin\WebtvAdminController::class, 'activate'])->name('webtv.activate');
        Route::delete('/webtv/subscribers/{id}', [App\Http\Controllers\Admin\WebtvAdminController::class, 'destroy'])->name('webtv.destroy');
        Route::post('/webtv/subscribers/{id}/send-test', [App\Http\Controllers\Admin\WebtvAdminController::class, 'sendTestEmail'])->name('webtv.send-test');
        Route::get('/webtv/export', [App\Http\Controllers\Admin\WebtvAdminController::class, 'export'])->name('webtv.export');
        Route::post('/webtv/notify-all', [App\Http\Controllers\Admin\WebtvAdminController::class, 'notifyAll'])->name('webtv.notifyAll');

        // Gestion des Vidéos WebTV - Programmer un Live
        Route::get('/webtv/videos', [App\Http\Controllers\Admin\WebtvVideoController::class, 'index'])->name('webtv.videos');
        Route::get('/webtv/videos/create', [App\Http\Controllers\Admin\WebtvVideoController::class, 'create'])->name('webtv.videos.create');
        Route::post('/webtv/videos', [App\Http\Controllers\Admin\WebtvVideoController::class, 'store'])->name('webtv.videos.store');
        Route::get('/webtv/videos/{id}', [App\Http\Controllers\Admin\WebtvVideoController::class, 'show'])->name('webtv.videos.show');
        Route::get('/webtv/videos/{id}/edit', [App\Http\Controllers\Admin\WebtvVideoController::class, 'edit'])->name('webtv.videos.edit');
        Route::put('/webtv/videos/{id}', [App\Http\Controllers\Admin\WebtvVideoController::class, 'update'])->name('webtv.videos.update');
        Route::delete('/webtv/videos/{id}', [App\Http\Controllers\Admin\WebtvVideoController::class, 'destroy'])->name('webtv.videos.destroy');
        Route::post('/webtv/videos/{id}/start', [App\Http\Controllers\Admin\WebtvVideoController::class, 'start'])->name('webtv.videos.start');
        Route::post('/webtv/videos/{id}/pause', [App\Http\Controllers\Admin\WebtvVideoController::class, 'pause'])->name('webtv.videos.pause');
        Route::post('/webtv/videos/{id}/end', [App\Http\Controllers\Admin\WebtvVideoController::class, 'end'])->name('webtv.videos.end');
        Route::post('/webtv/videos/update-order', [App\Http\Controllers\Admin\WebtvVideoController::class, 'updateOrder'])->name('webtv.videos.update-order');

        // Routes héritées (compatibilité)
        Route::get('/etudiants', [AdminDashboardController::class, 'users'])->name('etudiants.legacy');
        Route::get('/documents', [AdminDashboardController::class, 'documents'])->name('documents.legacy');
    });
});

// Routes supprimées - toutes les routes respectent maintenant la nomenclature /evc/compte/design-graphique/{menu}/{action}
// Anciennes routes avec préfixe 'compte-evc/' supprimées pour respecter la nouvelle nomenclature
