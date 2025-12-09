<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PreRegistration;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\AdmissionApprovedRegistrationLink;
use App\Models\User;

class PreRegistrationAdminController extends Controller
{
    public function index(Request $request)
    {
        // Synchroniser les statuts: (accepted|Validé) -> Actif si l'utilisateur a confirmé son compte
        try {
            $verifiedEmails = DB::table('users')->whereNotNull('email_verified_at')->pluck('email');
            if ($verifiedEmails->count() > 0) {
                $update = ['status' => 'Actif'];
                if (\Illuminate\Support\Facades\Schema::hasColumn('pre_registrations', 'activated_at')) {
                    $update['activated_at'] = now();
                }
                PreRegistration::whereIn('status', ['accepted','Validé'])
                    ->whereIn('email', $verifiedEmails)
                    ->update($update);
            }
        } catch (\Throwable $e) {
            Log::warning('Sync accepted->Actif failed', ['error' => $e->getMessage()]);
        }

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
        try {
            $request->validate([
                'ids' => 'required|array|min:1',
                'ids.*' => 'integer',
                'action' => 'required|string'
            ]);

            // Vérifier que les IDs existent
            $ids = array_filter($request->ids, 'is_numeric');
            if (empty($ids)) {
                return redirect()->back()->with('warning', 'Aucun élément sélectionné.');
            }

            // Vérifier que l'action est valide
            $validActions = ['accepted', 'rejected', 'pending', 'Validé', 'Rejeté', 'En attente', 'delete'];
            if (!in_array($request->action, $validActions)) {
                return redirect()->back()->with('error', 'Action non valide.');
            }

            // Si l'action est delete, supprimer les enregistrements
            if ($request->action === 'delete') {
                $pres = PreRegistration::whereIn('id', $ids)->get();

                if ($pres->isEmpty()) {
                    return redirect()->back()->with('warning', 'Aucune pré-inscription trouvée avec ces IDs.');
                }

                // Supprimer les photos associées
                foreach ($pres as $pre) {
                    if ($pre->photo) {
                        $photoPath = storage_path('app/public/' . $pre->photo);
                        if (file_exists($photoPath)) {
                            @unlink($photoPath);
                        }
                    }
                }

                // Supprimer les enregistrements
                $count = PreRegistration::whereIn('id', $ids)->delete();

                return redirect()->route('admin.preinscriptions.index', $request->only(['q','formation','status']))
                    ->with('success', "✅ {$count} pré-inscription(s) supprimée(s) avec succès.");
            }

            // Sinon, mettre à jour le statut
            $count = PreRegistration::whereIn('id', $ids)->update(['status' => $request->action]);

            if ($count === 0) {
                return redirect()->back()->with('warning', 'Aucune pré-inscription trouvée avec ces IDs.');
            }

            Log::info('Statut groupé mis à jour', [
                'count' => $count,
                'action' => $request->action,
                'updated_by' => session('admin_id')
            ]);

            return redirect()->route('admin.preinscriptions.index', $request->only(['q','formation','status']))
                ->with('success', "✅ Statut mis à jour pour {$count} élément(s) sélectionné(s).");

        } catch (\Exception $e) {
            Log::error('Erreur action groupée pré-inscriptions', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return redirect()->back()
                ->with('error', '❌ Erreur lors de l\'action groupée : ' . $e->getMessage());
        }
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
        // 1) Marquer Validé
        $pre->status = 'Validé';
        $pre->save();

        // 2) Créer ou récupérer l'utilisateur lié à cette candidature
        $user = User::where('email', $pre->email)->first();
        if (!$user) {
            $user = new User();
            $user->name = trim(($pre->prenom ? $pre->prenom.' ' : '').($pre->nom ?? '')) ?: $pre->email;
            $user->email = $pre->email;
            // Mot de passe temporaire aléatoire (sera remplacé lors de la confirmation)
            $user->password = bcrypt(str()->random(32));
            $user->save();
        }

        // 2.5) Créer ou mettre à jour l'enregistrement student
        $student = \App\Models\Student::where('user_id', $user->id)->first();
        if (!$student) {
            $student = new \App\Models\Student();
            $student->user_id = $user->id;
        }

        // Mapper les champs de pré-inscription vers student
        $student->first_name = $pre->prenom;
        $student->last_name = $pre->nom;
        $student->email = $pre->email;
        $student->phone = $pre->whatsapp ?? null;
        $student->whatsapp = $pre->whatsapp ?? null;
        $student->age = $pre->age ?? null;
        $student->date_of_birth = $pre->date_naissance ?? null;

        // Mapper le sexe (F/M) vers gender (Femme/Homme/Autre)
        if (!empty($pre->sexe)) {
            $genderMap = [
                'F' => 'Femme',
                'M' => 'Homme',
                'f' => 'Femme',
                'm' => 'Homme',
                'Femme' => 'Femme',
                'Homme' => 'Homme',
                'Autre' => 'Autre',
            ];
            $student->gender = $genderMap[$pre->sexe] ?? null;
        } else {
            $student->gender = null;
        }

        $student->city = $pre->ville ?? null;
        $student->country = $pre->pays ?? 'Côte d\'Ivoire';
        $student->program = $pre->choix_formation ?? null;
        $student->level = $pre->niveau_dans_formation ?? null;
        $student->Level_education = $pre->niveau_etude ?? null;
        $student->degree = $pre->niveau_etude ?? null; // Ajout du champ degree
        $student->profile_photo = $pre->photo ?? null;
        $student->status = 'active';

        // Générer un student_id unique si nouveau
        if (!$student->exists || empty($student->student_id)) {
            $year = date('Y');
            $lastStudent = \App\Models\Student::where('student_id', 'like', "EVC{$year}%")
                ->orderBy('student_id', 'desc')
                ->first();

            if ($lastStudent && preg_match('/EVC' . $year . '(\d+)/', $lastStudent->student_id, $matches)) {
                $nextNumber = intval($matches[1]) + 1;
            } else {
                $nextNumber = 1;
            }

            $student->student_id = 'EVC' . $year . str_pad($nextNumber, 4, '0', STR_PAD_LEFT);
        }

        $student->save();

        // 3) Générer un lien unique de création de compte (valide 24h)
        $email = $pre->email;
        $timestamp = time();
        $hash = md5($email . config('app.key'));
        $token = base64_encode($email.'|'.$timestamp.'|'.$hash);
        $registerUrl = route('student.confirm-registration', ['token' => $token]);

        // 4) Envoyer l'e-mail de félicitations avec le lien
        try {
            Mail::to($pre->email)->send(new AdmissionApprovedRegistrationLink($pre, $registerUrl));
        } catch (\Throwable $e) {
            Log::error('Echec envoi mail de validation de candidature', ['error' => $e->getMessage(), 'pre_id' => $pre->id]);
            return redirect()->back()->with('warning', "Candidature validée mais l'e-mail n'a pas pu être envoyé.");
        }

        return redirect()->back()->with('success', 'Pré-inscription validée, profil étudiant créé et e-mail envoyé au candidat.');
    }

    public function destroy($id)
    {
        try {
            $pre = PreRegistration::findOrFail($id);
            $name = $pre->prenom . ' ' . $pre->nom;
            $email = $pre->email;

            // Supprimer la photo associée si elle existe
            if ($pre->photo) {
                $photoPath = storage_path('app/public/' . $pre->photo);
                if (file_exists($photoPath)) {
                    @unlink($photoPath);
                }
            }

            $pre->delete();

            Log::info('Pré-inscription supprimée', [
                'id' => $id,
                'name' => $name,
                'email' => $email,
                'deleted_by' => session('admin_id')
            ]);

            return redirect()->route('admin.preinscriptions.index')
                ->with('success', "✅ Pré-inscription de {$name} ({$email}) supprimée avec succès.");

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression de pré-inscription', [
                'id' => $id,
                'error' => $e->getMessage()
            ]);

            return redirect()->back()
                ->with('error', '❌ Erreur lors de la suppression : ' . $e->getMessage());
        }
    }

    public function resendRegistrationLink($id)
    {
        $pre = PreRegistration::findOrFail($id);

        // Regénérer un token 24h
        $email = $pre->email;
        $timestamp = time();
        $hash = md5($email . config('app.key'));
        $token = base64_encode($email.'|'.$timestamp.'|'.$hash);
        $registerUrl = route('student.confirm-registration', ['token' => $token]);

        try {
            Mail::to($pre->email)->send(new AdmissionApprovedRegistrationLink($pre, $registerUrl));
        } catch (\Throwable $e) {
            Log::error('Echec renvoi lien d\'inscription', ['error' => $e->getMessage(), 'pre_id' => $pre->id]);
            return redirect()->back()->with('warning', "Le lien n'a pas pu être renvoyé. Veuillez réessayer.");
        }

        return redirect()->back()->with('success', 'Lien de création de compte renvoyé au candidat.');
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
