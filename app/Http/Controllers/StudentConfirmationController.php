<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\StudentCompletedRegistration;
use App\Models\PreRegistration;

class StudentConfirmationController extends Controller
{
    /**
     * Afficher la page de confirmation d'inscription
     */
    public function showConfirmationForm($token)
    {
        try {
            // Décoder le token
            $decodedToken = base64_decode($token);
            $tokenParts = explode('|', $decodedToken);

            if (count($tokenParts) !== 3) {
                return redirect()->route('home')->with('error', 'Lien de confirmation invalide.');
            }

            $email = $tokenParts[0];
            $timestamp = $tokenParts[1];
            $hash = $tokenParts[2];

            // Vérifier la validité du token (24h)
            if (time() - $timestamp > 86400) {
                return redirect()->route('home')->with('error', 'Ce lien de confirmation a expiré.');
            }

            // Vérifier l'intégrité du token
            $expectedHash = md5($email . config('app.key'));
            if ($hash !== $expectedHash) {
                return redirect()->route('home')->with('error', 'Lien de confirmation invalide.');
            }

            // Récupérer l'étudiant
            $student = DB::table('users')->where('email', $email)->first();

            if (!$student) {
                return redirect()->route('home')->with('error', 'Aucun compte trouvé avec cette adresse email.');
            }

            // Si déjà confirmé, rediriger vers la connexion
            if ($student->email_verified_at) {
                return redirect()->route('student.login')->with('success', 'Votre inscription est déjà confirmée. Vous pouvez vous connecter.');
            }

            // Hydrater des propriétés attendues par la vue (compatibilité)
            if (!property_exists($student, 'first_name') || !property_exists($student, 'last_name')) {
                $parts = preg_split('/\s+/', (string)($student->name ?? ''), 2);
                $student->first_name = $parts[0] ?? '';
                $student->last_name = $parts[1] ?? '';
            }
            // Hydrater le téléphone si attendu par la vue
            if (!property_exists($student, 'phone') || empty($student->phone)) {
                $pre = PreRegistration::where('email', $email)->latest('id')->first();
                if ($pre && !empty($pre->whatsapp)) {
                    $student->phone = $pre->whatsapp;
                } else {
                    $student->phone = '';
                }
            }
            // Hydrater la formation souhaitée si attendue par la vue
            if (!property_exists($student, 'formation_souhaitee') || empty($student->formation_souhaitee)) {
                $pre = isset($pre) ? $pre : PreRegistration::where('email', $email)->latest('id')->first();
                $label = '';
                if ($pre) {
                    $map = [
                        'design_graphique' => 'Design Graphique',
                        'community_management' => 'Community Management',
                        'intelligence_artificielle' => 'Intelligence Artificielle',
                        'gestion_informatique' => 'Gestion Informatique',
                        'infographie' => 'Design Graphique',
                        'informatique' => 'Gestion Informatique',
                    ];
                    $key = $pre->choix_formation ?: $pre->programme;
                    $label = $map[$key] ?? ($pre->programme ?? '');
                }
                $student->formation_souhaitee = $label;
            }
            return view('student.confirm-registration', compact('student', 'token'));

        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation d\'inscription: ' . $e->getMessage());
            return redirect()->route('home')->with('error', 'Une erreur est survenue lors de la confirmation.');
        }
    }

    /**
     * Traiter la confirmation d'inscription
     */
    public function confirmRegistration(Request $request, $token)
    {
        try {
            // Décoder le token
            $decodedToken = base64_decode($token);
            $tokenParts = explode('|', $decodedToken);

            if (count($tokenParts) !== 3) {
                return back()->with('error', 'Lien de confirmation invalide.');
            }

            $email = $tokenParts[0];
            $timestamp = $tokenParts[1];
            $hash = $tokenParts[2];

            // Vérifier la validité du token
            if (time() - $timestamp > 86400) {
                return back()->with('error', 'Ce lien de confirmation a expiré.');
            }

            // Vérifier l'intégrité du token
            $expectedHash = md5($email . config('app.key'));
            if ($hash !== $expectedHash) {
                return back()->with('error', 'Lien de confirmation invalide.');
            }

            // Validation des données
            $request->validate([
                'password' => 'required|min:8|confirmed',
                'biography' => 'nullable|string|max:500',
                'expectations' => 'nullable|string|max:500',
                'accepte_conditions' => 'required|accepted',
                // Autoriser valeurs typiques de checkbox: on, off, 1, 0, true, false, yes, no
                'newsletter_consent' => 'nullable|in:0,1,on,off,true,false,yes,no'
            ], [
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'accepte_conditions.required' => 'Vous devez accepter les conditions d\'utilisation.',
                'accepte_conditions.accepted' => 'Vous devez accepter les conditions d\'utilisation.',
                'newsletter_consent.in' => 'Le champ consentement newsletter est invalide.'
            ]);

            // Mettre à jour l'étudiant (tolérant au schéma de la table users)
            $updateData = [
                'password' => bcrypt($request->password),
                'email_verified_at' => now(),
                'updated_at' => now(),
            ];
            // Colonnes optionnelles si présentes en base
            $schema = \Illuminate\Support\Facades\Schema::getColumnListing('users');
            $has = function(string $col) use ($schema) { return in_array($col, $schema, true); };
            if ($has('biography')) { $updateData['biography'] = $request->biography; }
            if ($has('expectations')) { $updateData['expectations'] = $request->expectations; }
            if ($has('accepte_conditions')) { $updateData['accepte_conditions'] = true; }
            if ($has('newsletter_consent')) { $updateData['newsletter_consent'] = $request->has('newsletter_consent'); }
            if ($has('status')) { $updateData['status'] = 'Actif'; }

            DB::table('users')->where('email', $email)->update($updateData);

            // Notifier l'admin de la création de compte réussie
            try {
                $adminEmail = config('mail.admin_address') ?? config('mail.from.address');
                if ($adminEmail) {
                    // Récupérer l'utilisateur mis à jour pour l'email
                    $user = DB::table('users')->where('email', $email)->first();
                    Mail::to($adminEmail)->send(new StudentCompletedRegistration($user));
                }
            } catch (\Throwable $e) {
                Log::warning('Echec envoi mail admin (student completed registration): '.$e->getMessage());
            }

            // Mettre à jour le statut de la pré-inscription -> Actif
            try {
                $update = ['status' => 'Actif'];
                if (\Illuminate\Support\Facades\Schema::hasColumn('pre_registrations', 'activated_at')) {
                    $update['activated_at'] = now();
                }
                \App\Models\PreRegistration::where('email', $email)->update($update);
            } catch (\Throwable $e) {
                Log::warning('Impossible de mettre à jour le statut PreRegistration vers Actif', ['email' => $email, 'error' => $e->getMessage()]);
            }

            // CRÉER L'ENTRÉE DANS LA TABLE STUDENTS si elle n'existe pas encore
            try {
                // Vérifier via user_id au lieu de email (email n'existe pas dans students)
                $user = DB::table('users')->where('email', $email)->first();
                $studentExists = $user ? DB::table('students')->where('user_id', $user->id)->exists() : false;

                if (!$studentExists) {
                    // Récupérer les infos de la pré-inscription et de l'utilisateur
                    $preRegistration = PreRegistration::where('email', $email)->latest('id')->first();
                    $user = DB::table('users')->where('email', $email)->first();

                    if ($preRegistration && $user) {
                        // Générer un student_id unique (format: EVC-ANNÉE-JOUR-MOIS-NUMERO)
                        // Exemple: EVC-2025-141001 (14 octobre 2025, 1er étudiant du jour)
                        $year = date('Y');
                        $day = date('d');
                        $month = date('m');
                        $datePrefix = "{$year}-{$day}{$month}";

                        $lastStudent = DB::table('students')
                            ->where('student_id', 'like', "EVC-{$datePrefix}%")
                            ->orderBy('id', 'desc')
                            ->first();

                        if ($lastStudent && preg_match('/EVC-\d{4}-\d{4}(\d{2})/', $lastStudent->student_id, $matches)) {
                            $nextNumber = intval($matches[1]) + 1;
                        } else {
                            $nextNumber = 1;
                        }
                        $studentId = sprintf('EVC-%s-%s%02d', $year, $day . $month, $nextNumber);

                        // Mapper la formation de la pré-inscription
                        $formationMap = [
                            'design_graphique' => ['program' => 'Design Graphique', 'specialization' => 'design_graphique'],
                            'community_management' => ['program' => 'Community Management', 'specialization' => 'community_management'],
                            'intelligence_artificielle' => ['program' => 'Intelligence Artificielle', 'specialization' => 'intelligence_artificielle'],
                            'gestion_informatique' => ['program' => 'Gestion Informatique', 'specialization' => 'gestion_informatique'],
                            'infographie' => ['program' => 'Design Graphique', 'specialization' => 'design_graphique'],
                            'informatique' => ['program' => 'Gestion Informatique', 'specialization' => 'gestion_informatique'],
                        ];

                        $formationKey = $preRegistration->choix_formation ?? 'design_graphique';
                        $formationData = $formationMap[$formationKey] ?? ['program' => 'Design Graphique', 'specialization' => 'design_graphique'];

                        // Créer l'entrée student
                        DB::table('students')->insert([
                            'user_id' => $user->id,
                            'student_id' => $studentId,
                            'first_name' => $preRegistration->prenom ?? '',
                            'last_name' => $preRegistration->nom ?? '',
                            'email' => $email,
                            'phone' => $preRegistration->whatsapp ?? null,
                            'whatsapp' => $preRegistration->whatsapp ?? null,
                            'program' => $formationData['program'],
                            'specialization' => $formationData['specialization'],
                            'level' => $preRegistration->niveau_dans_formation ?? 'Débutant',
                            'Level_education' => $preRegistration->niveau_etude ?? null,
                            'degree' => 'En cours',
                            'status' => 'active',
                            'city' => $preRegistration->pays ?? null,
                            'country' => $preRegistration->pays ?? 'Côte d\'Ivoire',
                            'profile_photo' => $preRegistration->photo ?? null,
                            'created_at' => now(),
                            'updated_at' => now()
                        ]);

                        Log::info('Entrée student créée automatiquement lors de la confirmation', [
                            'email' => $email,
                            'student_id' => $studentId,
                            'formation' => $formationData['program']
                        ]);
                    }
                }
            } catch (\Throwable $e) {
                Log::error('Erreur lors de la création automatique de l\'entrée student', [
                    'email' => $email,
                    'error' => $e->getMessage()
                ]);
                // Ne pas bloquer l'inscription si la création du student échoue
            }

            Log::info('Inscription confirmée avec succès pour: ' . $email);

            return redirect()->route('student.login')->with('success', 'Félicitations ! Votre inscription a été confirmée avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.');

        } catch (\Illuminate\Validation\ValidationException $e) {
            throw $e;
        } catch (\Exception $e) {
            Log::error('Erreur confirmation inscription: ' . $e->getMessage(), ['email' => $email ?? null, 'line' => $e->getLine()]);
            return back()->with('error', 'Une erreur est survenue lors de la confirmation : ' . $e->getMessage())->withInput();
        }
    }
}
