<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                'newsletter_consent' => 'nullable|boolean'
            ], [
                'password.required' => 'Le mot de passe est obligatoire.',
                'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
                'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
                'accepte_conditions.required' => 'Vous devez accepter les conditions d\'utilisation.',
                'accepte_conditions.accepted' => 'Vous devez accepter les conditions d\'utilisation.'
            ]);
            
            // Mettre à jour l'étudiant
            $updateData = [
                'password' => bcrypt($request->password),
                'biography' => $request->biography,
                'expectations' => $request->expectations,
                'accepte_conditions' => true,
                'newsletter_consent' => $request->has('newsletter_consent'),
                'email_verified_at' => now(),
                'status' => 'Actif',
                'updated_at' => now()
            ];
            
            DB::table('users')
                ->where('email', $email)
                ->update($updateData);
            
            Log::info('Inscription confirmée avec succès pour: ' . $email);
            
            return redirect()->route('student.login')->with('success', 'Félicitations ! Votre inscription a été confirmée avec succès. Vous pouvez maintenant vous connecter avec votre nouveau mot de passe.');
            
        } catch (\Exception $e) {
            Log::error('Erreur lors de la confirmation d\'inscription: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la confirmation.')->withInput();
        }
    }
}
