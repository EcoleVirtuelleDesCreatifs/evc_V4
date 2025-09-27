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
            return redirect()
                ->route('design-graphique.parametres.index')
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
        $user = DB::table('users')->where('id', $userId)->first();
        
        if (!$user) {
            throw new Exception('Utilisateur non trouvé');
        }
        
        return $user;
    }

    /**
     * Update user data in database.
     */
    private function updateUserInDatabase(int $userId, array $data): void
    {
        // Build update array with only non-empty fields
        $updateData = ['updated_at' => now()];
        
        if (!empty($data['firstName'])) {
            $updateData['first_name'] = $data['firstName'];
        }
        
        if (!empty($data['lastName'])) {
            $updateData['last_name'] = $data['lastName'];
        }
        
        if (!empty($data['email'])) {
            $updateData['email'] = $data['email'];
        }
        
        if (!empty($data['phone'])) {
            $updateData['phone'] = $data['phone'];
        }
        
        if (!empty($data['whatsapp'])) {
            $updateData['whatsapp'] = $data['whatsapp'];
        }
        
        if (!empty($data['age'])) {
            $updateData['age'] = $data['age'];
        }
        
        if (!empty($data['country'])) {
            $updateData['country'] = $data['country'];
        }
        
        if (!empty($data['city'])) {
            $updateData['city'] = $data['city'];
        }
        
        if (!empty($data['district'])) {
            $updateData['district'] = $data['district'];
        }
        
        if (!empty($data['address'])) {
            $updateData['address'] = $data['address'];
        }
        
        if (!empty($data['biography'])) {
            $updateData['biography'] = $data['biography'];
        }
        
        if (!empty($data['educationLevel'])) {
            $updateData['education_level'] = $data['educationLevel'];
        }
        
        if (!empty($data['lastDiploma'])) {
            $updateData['last_diploma'] = $data['lastDiploma'];
        }

        // Only update if there are fields to update (besides updated_at)
        if (count($updateData) > 1) {
            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);
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
}
