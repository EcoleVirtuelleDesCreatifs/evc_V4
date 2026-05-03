<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Mail\PasswordResetMail;

class PasswordResetController extends Controller
{
    /**
     * Show the password reset request form
     */
    public function showResetRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send password reset email
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.exists' => 'Cette adresse email n\'est pas enregistrée dans notre système.'
        ]);

        try {
            // Generate reset token
            $token = Str::random(64);

            // Delete existing tokens for this email
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            // Insert new token
            DB::table('password_reset_tokens')->insert([
                'email' => $request->email,
                'token' => Hash::make($token),
                'created_at' => Carbon::now()
            ]);

            // Get user info
            $user = DB::table('users')->where('email', $request->email)->first();

            // Send email
            $resetUrl = url('/auth/evc/reset-password/' . $token . '?email=' . urlencode($request->email));

            // For now, we'll log the reset URL (in production, send actual email)
            Log::info('Password reset requested', [
                'email' => $request->email,
                'reset_url' => $resetUrl,
                'user_name' => $user->name
            ]);

            // Send email (simplified version for now)
            $this->sendPasswordResetEmail($request->email, $resetUrl, $user);

            return back()->with('success',
                'Un email de réinitialisation a été envoyé à votre adresse. ' .
                'Vérifiez votre boîte de réception et vos spams.'
            );

        } catch (\Exception $e) {
            Log::error('Password reset error: ' . $e->getMessage());
            return back()->with('error',
                'Une erreur s\'est produite lors de l\'envoi de l\'email. Veuillez réessayer.'
            );
        }
    }

    /**
     * Show password reset form
     */
    public function showResetForm(Request $request, $token)
    {
        $email = $request->query('email');

        if (!$email) {
            return redirect('/login')->with('error', 'Lien de réinitialisation invalide.');
        }

        // Verify token exists and is not expired (24 hours)
        $tokenRecord = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->where('created_at', '>', Carbon::now()->subHours(24))
            ->first();

        if (!$tokenRecord) {
            return redirect('/login')->with('error',
                'Ce lien de réinitialisation a expiré ou n\'est pas valide.'
            );
        }

        return view('auth.passwords.reset', [
            'token' => $token,
            'email' => $email
        ]);
    }

    /**
     * Reset the password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:8|confirmed',
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'email.exists' => 'Cette adresse email n\'est pas enregistrée.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 8 caractères.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        try {
            // Find the token
            $tokenRecord = DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->where('created_at', '>', Carbon::now()->subHours(24))
                ->first();

            if (!$tokenRecord || !Hash::check($request->token, $tokenRecord->token)) {
                return back()->with('error',
                    'Ce lien de réinitialisation a expiré ou n\'est pas valide.'
                );
            }

            // Update user password
            DB::table('users')
                ->where('email', $request->email)
                ->update([
                    'password' => Hash::make($request->password),
                    'updated_at' => Carbon::now()
                ]);

            // Delete the used token
            DB::table('password_reset_tokens')
                ->where('email', $request->email)
                ->delete();

            Log::info('Password reset successful', ['email' => $request->email]);

            return redirect()->route('login')->with('success',
                'Votre mot de passe a été réinitialisé avec succès. Vous pouvez maintenant vous connecter.'
            );

        } catch (\Exception $e) {
            Log::error('Password reset completion error: ' . $e->getMessage());
            return back()->with('error',
                'Une erreur s\'est produite lors de la réinitialisation. Veuillez réessayer.'
            );
        }
    }

    /**
     * Send password reset email using Laravel Mail
     */
    private function sendPasswordResetEmail($email, $resetUrl, $user)
    {
        try {
            // Extract token from URL for logging
            $token = basename(parse_url($resetUrl, PHP_URL_PATH));

            // Send email using Mailable
            Mail::to($email)->send(new PasswordResetMail($resetUrl, $user, $token));

            Log::info('Password reset email sent successfully', [
                'to' => $email,
                'user_name' => $user->name,
                'reset_url' => $resetUrl
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send password reset email', [
                'to' => $email,
                'error' => $e->getMessage(),
                'reset_url' => $resetUrl
            ]);

            // Re-throw the exception so the calling method can handle it
            throw $e;
        }
    }
}
