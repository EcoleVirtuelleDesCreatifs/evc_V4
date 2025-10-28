<?php

namespace App\Http\Controllers;

use App\Http\Requests\UpdateProfileRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class ProfileController extends Controller
{
    /**
     * Display the profile settings page.
     */
    public function index()
    {
        if (!$this->isAuthenticated()) {
            return redirect()->route('login')->with('error', 'Vous devez être connecté pour accéder à cette page.');
        }

        try {
            $userId = (int) session('user_id');
            $user = $this->getUserData($userId);
            
            return view('parametres.index', compact('user'));
        } catch (Exception $e) {
            Log::error('Erreur lors du chargement de la page paramètres: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Erreur lors du chargement de la page.');
        }
    }

    /**
     * Update the user's profile information.
     */
    public function update(UpdateProfileRequest $request)
    {
        try {
            $userId = (int) session('user_id');
            $validatedData = $request->validated();
            
            // Debug: Log received data
            Log::info('Données reçues du formulaire', [
                'raw_request' => $request->all(),
                'validated_data' => $validatedData
            ]);
            
            // Update user in database
            $this->updateUserInDatabase($userId, $validatedData);
            
            // Update session data
            $this->updateSessionData($validatedData);
            
            // Log successful update
            Log::info('Profil utilisateur mis à jour avec succès', [
                'user_id' => $userId,
                'updated_fields' => array_keys($validatedData)
            ]);

            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Profil mis à jour avec succès !',
                    'data' => [
                        'full_name' => $validatedData['firstName'] . ' ' . $validatedData['lastName'],
                        'email' => $validatedData['email']
                    ]
                ]);
            }

            // Handle regular form submissions
            // Redirection dynamique selon la formation de l'utilisateur
            $userFormation = session('user_formation_raw', 'design-graphique');
            return redirect()
                ->route($userFormation . '.parametres.index')
                ->with('success', 'Profil mis à jour avec succès !');

        } catch (Exception $e) {
            Log::error('Erreur mise à jour profil: ' . $e->getMessage(), [
                'user_id' => session('user_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            // Handle AJAX requests
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Erreur lors de la mise à jour du profil. Veuillez réessayer.'
                ], 500);
            }

            // Handle regular form submissions
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour du profil. Veuillez réessayer.');
        }
    }

    /**
     * Check if user is authenticated.
     */
    private function isAuthenticated(): bool
    {
        return session('logged_in', false) && session('user_id');
    }

    /**
     * Get user data from database.
     */
    private function getUserData(int $userId): object
    {
        // Récupérer les données de l'utilisateur depuis la table users
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            throw new Exception('Utilisateur non trouvé');
        }
        
        // Essayer de récupérer les informations du profil étudiant
        $student = DB::table('students')->where('user_id', $userId)->first();
        
        // Si un profil étudiant existe, fusionner les données
        if ($student) {
            // Convertir les objets en tableaux pour fusionner
            $userData = (array) $user;
            $studentData = (array) $student;
            
            // Fusionner les données (les données de student ont la priorité)
            $mergedData = array_merge($userData, $studentData);
            
            // IMPORTANT: L'email doit TOUJOURS provenir de la table users (pour la connexion)
            $mergedData['email'] = $user->email;
            $mergedData['name'] = $user->name;
            $mergedData['password'] = $user->password;
            
            // Reconvertir en objet
            return (object) $mergedData;
        }
        
        // Sinon, essayer avec la table pre_registrations
        $preReg = DB::table('pre_registrations')->where('user_id', $userId)->first();
        
        if ($preReg) {
            $userData = (array) $user;
            $preRegData = (array) $preReg;
            $mergedData = array_merge($userData, $preRegData);
            
            // IMPORTANT: L'email doit TOUJOURS provenir de la table users
            $mergedData['email'] = $user->email;
            $mergedData['name'] = $user->name;
            $mergedData['password'] = $user->password;
            
            return (object) $mergedData;
        }
        
        return $user;
    }

    /**
     * Update user data in database.
     */
    private function updateUserInDatabase(int $userId, array $data): void
    {
        // Données pour la table USERS (authentification)
        // NOTE: L'email n'est PAS mis à jour ici, seulement via le formulaire "Informations de connexion"
        $usersData = [
            'updated_at' => now()
        ];
        
        // Mettre à jour uniquement le nom complet (pour l'affichage)
        if (!empty($data['firstName'])) {
            $usersData['name'] = $data['firstName'] . ' ' . ($data['lastName'] ?? '');
        }
        
        // Données pour la table STUDENTS (informations supplémentaires)
        $studentsData = [
            'updated_at' => now()
        ];
        
        if (!empty($data['firstName'])) {
            $studentsData['first_name'] = $data['firstName'];
        }
        
        if (!empty($data['lastName'])) {
            $studentsData['last_name'] = $data['lastName'];
        }
        
        if (!empty($data['phone'])) {
            $studentsData['phone'] = $data['phone'];
        }
        
        if (!empty($data['whatsapp'])) {
            $studentsData['whatsapp'] = $data['whatsapp'];
        }
        
        if (!empty($data['age'])) {
            $studentsData['age'] = $data['age'];
        }
        
        if (!empty($data['country'])) {
            $studentsData['country'] = $data['country'];
        }
        
        if (!empty($data['city'])) {
            $studentsData['city'] = $data['city'];
        }
        
        // Map district to quartier (the actual column name in students table)
        if (!empty($data['district'])) {
            $studentsData['quartier'] = $data['district'];
        }
        
        if (!empty($data['address'])) {
            $studentsData['address'] = $data['address'];
        }
        
        if (!empty($data['biography'])) {
            $studentsData['biography'] = $data['biography'];
        }
        
        // Map educationLevel to Level_education (the actual column name)
        if (!empty($data['educationLevel'])) {
            $studentsData['Level_education'] = $data['educationLevel'];
        }
        
        // Map lastDiploma to degree (the actual column name)
        if (!empty($data['lastDiploma'])) {
            $studentsData['degree'] = $data['lastDiploma'];
        }

        // 1. Mettre à jour la table USERS (toujours)
        if (count($usersData) > 1) {
            DB::table('users')
                ->where('id', $userId)
                ->update($usersData);
            
            Log::info('Table users mise à jour', ['user_id' => $userId, 'fields' => array_keys($usersData)]);
        }
        
        // 2. Mettre à jour la table STUDENTS si elle existe
        if (count($studentsData) > 1) {
            $student = DB::table('students')->where('user_id', $userId)->first();
            
            if ($student) {
                DB::table('students')
                    ->where('user_id', $userId)
                    ->update($studentsData);
                
                Log::info('Table students mise à jour', ['user_id' => $userId, 'fields' => array_keys($studentsData)]);
            } else {
                // Si pas de profil étudiant, vérifier dans pre_registrations
                $preReg = DB::table('pre_registrations')->where('user_id', $userId)->first();
                
                if ($preReg) {
                    DB::table('pre_registrations')
                        ->where('user_id', $userId)
                        ->update($studentsData);
                    
                    Log::info('Table pre_registrations mise à jour', ['user_id' => $userId, 'fields' => array_keys($studentsData)]);
                } else {
                    Log::warning('Aucun profil étudiant trouvé pour user_id: ' . $userId);
                }
            }
        }
    }

    /**
     * Update login information (email, username, password) - TABLE USERS ONLY
     */
    public function updateLoginInfo(Request $request)
    {
        try {
            $userId = (int) session('user_id');
            
            Log::info('Tentative de mise à jour des informations de connexion', [
                'user_id' => $userId,
                'email' => $request->email
            ]);
            
            // Vérifier que l'utilisateur est connecté
            if (!$userId) {
                throw new Exception('Session utilisateur invalide');
            }
            
            // Validation
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email,' . $userId . ',id',
                'username' => 'nullable|string|max:255',
                'current_password' => 'required|string',
                'new_password' => 'nullable|string|min:8|confirmed',
            ]);
            
            Log::info('Validation réussie', ['validated_fields' => array_keys($validated)]);
            
            // Vérifier le mot de passe actuel
            $user = DB::table('users')->where('id', $userId)->first();
            
            if (!$user) {
                Log::error('Utilisateur non trouvé dans la base de données', ['user_id' => $userId]);
                throw new Exception('Utilisateur non trouvé');
            }
            
            Log::info('Utilisateur trouvé, vérification du mot de passe');
            
            if (!password_verify($validated['current_password'], $user->password)) {
                Log::warning('Mot de passe actuel incorrect', ['user_id' => $userId]);
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Le mot de passe actuel est incorrect.');
            }
            
            Log::info('Mot de passe vérifié avec succès');
            
            // Préparer les données à mettre à jour
            $updateData = [
                'email' => $validated['email'],
                'updated_at' => now()
            ];
            
            if (!empty($validated['username'])) {
                $updateData['name'] = $validated['username'];
            }
            
            // Si un nouveau mot de passe est fourni
            if (!empty($validated['new_password'])) {
                $updateData['password'] = bcrypt($validated['new_password']);
            }
            
            // Mettre à jour la table users
            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);
            
            // Mettre à jour la session
            session([
                'user_email' => $validated['email'],
                'user_nom' => $validated['username'] ?? $user->name
            ]);
            
            Log::info('Informations de connexion mises à jour', [
                'user_id' => $userId,
                'email_updated' => $validated['email'],
                'password_changed' => !empty($validated['new_password'])
            ]);
            
            // Redirection dynamique selon la formation de l'utilisateur
            $userFormation = session('user_formation_raw', 'design-graphique');
            return redirect()
                ->route($userFormation . '.parametres.index')
                ->with('success', 'Informations de connexion mises à jour avec succès !');
                
        } catch (Exception $e) {
            Log::error('Erreur mise à jour informations de connexion: ' . $e->getMessage(), [
                'user_id' => session('user_id'),
                'trace' => $e->getTraceAsString()
            ]);
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erreur lors de la mise à jour. Veuillez réessayer.');
        }
    }

    /**
     * Update session data with new user information.
     */
    private function updateSessionData(array $data): void
    {
        $sessionData = [];
        
        if (!empty($data['firstName'])) {
            $sessionData['user_prenom'] = $data['firstName'];
        }
        
        if (!empty($data['lastName'])) {
            $sessionData['user_nom'] = $data['lastName'];
        }
        
        if (!empty($data['email'])) {
            $sessionData['user_email'] = $data['email'];
        }
        
        if (!empty($data['phone'])) {
            $sessionData['user_telephone'] = $data['phone'];
        }
        
        if (!empty($data['whatsapp'])) {
            $sessionData['user_whatsapp'] = $data['whatsapp'];
        }
        
        if (!empty($data['city'])) {
            $sessionData['user_ville'] = $data['city'];
        }
        
        if (!empty($data['country'])) {
            $sessionData['user_pays'] = $data['country'];
        }
        
        if (!empty($data['address'])) {
            $sessionData['user_adresse'] = $data['address'];
        }
        
        // Only update session if there are fields to update
        if (!empty($sessionData)) {
            session($sessionData);
        }
    }

    /**
     * Upload profile photo.
     */
    public function uploadPhoto(Request $request)
    {
        try {
            // Vérifier l'authentification
            if (!session('logged_in') || !session('user_id')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vous devez être connecté pour effectuer cette action.'
                ], 401);
            }

            // Valider le fichier
            $request->validate([
                'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:5120' // 5MB max
            ]);

            $userId = (int) session('user_id');
            
            // Récupérer l'ancien fichier pour le supprimer
            $student = DB::table('students')->where('user_id', $userId)->first();
            $oldPhoto = $student ? $student->profile_photo : null;

            // Uploader le nouveau fichier
            if ($request->hasFile('photo')) {
                $file = $request->file('photo');
                $filename = 'profile_' . $userId . '_' . time() . '.' . $file->getClientOriginalExtension();
                
                // Créer le dossier s'il n'existe pas
                $uploadPath = public_path('uploads/photos');
                if (!file_exists($uploadPath)) {
                    mkdir($uploadPath, 0755, true);
                }
                
                // Déplacer le fichier
                $file->move($uploadPath, $filename);
                $photoPath = 'uploads/photos/' . $filename;

                // Mettre à jour la base de données
                DB::table('students')
                    ->where('user_id', $userId)
                    ->update([
                        'profile_photo' => $photoPath,
                        'updated_at' => now()
                    ]);

                // Mettre à jour la session
                session(['user_photo' => $photoPath]);

                // Supprimer l'ancienne photo si elle existe
                if ($oldPhoto && file_exists(public_path($oldPhoto))) {
                    @unlink(public_path($oldPhoto));
                }

                Log::info('Photo de profil mise à jour', [
                    'user_id' => $userId,
                    'photo_path' => $photoPath
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Photo de profil mise à jour avec succès !',
                    'photo_url' => asset($photoPath)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => 'Aucun fichier n\'a été uploadé.'
            ], 400);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Fichier invalide. Veuillez sélectionner une image (JPEG, PNG, JPG, GIF) de moins de 5MB.'
            ], 422);
        } catch (Exception $e) {
            Log::error('Erreur upload photo profil: ' . $e->getMessage(), [
                'user_id' => session('user_id'),
                'trace' => $e->getTraceAsString()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'upload de la photo. Veuillez réessayer.'
            ], 500);
        }
    }
}
