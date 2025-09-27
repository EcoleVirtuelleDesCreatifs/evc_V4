<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class AdminAuthController extends Controller
{
    /**
     * Show admin login form
     */
    public function showLoginForm(): View
    {
        return view('admin.auth.login');
    }

    /**
     * Handle admin login
     */
    public function login(Request $request): RedirectResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6'
        ], [
            'email.required' => 'L\'adresse email est obligatoire.',
            'email.email' => 'Veuillez saisir une adresse email valide.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.'
        ]);

        try {
            // Check if admin exists
            $admin = DB::table('admins')->where('email', $request->email)->first();

            if (!$admin) {
                return back()->with('error', 'Identifiants administrateur invalides.');
            }

            // Verify password
            if (!Hash::check($request->password, $admin->password)) {
                return back()->with('error', 'Identifiants administrateur invalides.');
            }

            // Check if admin is active
            if (!$admin->is_active) {
                return back()->with('error', 'Votre compte administrateur est désactivé.');
            }

            // Create admin session
            session([
                'admin_logged_in' => true,
                'admin_id' => $admin->id,
                'admin_name' => $admin->name,
                'admin_email' => $admin->email,
                'admin_role' => $admin->role,
                'admin_permissions' => json_decode($admin->permissions ?? '[]', true)
            ]);

            // Update last login
            DB::table('admins')->where('id', $admin->id)->update([
                'last_login_at' => now(),
                'updated_at' => now()
            ]);

            Log::info('Admin login successful', [
                'admin_id' => $admin->id,
                'admin_email' => $admin->email,
                'ip' => $request->ip()
            ]);

            return redirect()->route('admin.dashboard')->with('success', 'Connexion administrateur réussie.');

        } catch (\Exception $e) {
            Log::error('Admin login error: ' . $e->getMessage());
            return back()->with('error', 'Une erreur s\'est produite lors de la connexion.');
        }
    }

    /**
     * Handle admin logout
     */
    public function logout(Request $request): RedirectResponse
    {
        $adminId = session('admin_id');
        
        // Clear admin session
        session()->forget([
            'admin_logged_in',
            'admin_id', 
            'admin_name',
            'admin_email',
            'admin_role',
            'admin_permissions'
        ]);

        Log::info('Admin logout', ['admin_id' => $adminId]);

        return redirect()->route('admin.login')->with('success', 'Déconnexion réussie.');
    }

    /**
     * Check if admin is authenticated
     */
    protected function isAdminAuthenticated(): bool
    {
        return session('admin_logged_in', false) && session('admin_id');
    }

    /**
     * Redirect to admin login if not authenticated
     */
    protected function redirectToAdminLogin(string $message = 'Vous devez être connecté en tant qu\'administrateur.'): RedirectResponse
    {
        return redirect()->route('admin.login')->with('error', $message);
    }
}
