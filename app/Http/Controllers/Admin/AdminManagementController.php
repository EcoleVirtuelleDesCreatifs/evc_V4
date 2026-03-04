<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\AdminAccountCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;

class AdminManagementController extends Controller
{
    /**
     * S'assurer que l'ENUM role inclut 'manager'
     */
    private function ensureManagerRoleExists(): void
    {
        try {
            $columnType = DB::selectOne("SHOW COLUMNS FROM admins WHERE Field = 'role'");
            if ($columnType && !str_contains($columnType->Type, 'manager')) {
                DB::statement("ALTER TABLE `admins` MODIFY COLUMN `role` ENUM('super_admin','manager','assistant','comptable') DEFAULT 'assistant'");
                Log::info('ENUM role mis à jour: ajout de manager');
            }
        } catch (\Exception $e) {
            Log::warning('Impossible de vérifier/mettre à jour l\'ENUM role: ' . $e->getMessage());
        }
    }

    /**
     * Afficher le formulaire de création d'un administrateur
     */
    public function create()
    {
        $this->checkSuperAdminPermission();

        $roles = $this->getRolesWithPermissions();

        return view('admin.admins.create', compact('roles'));
    }

    /**
     * Enregistrer un nouvel administrateur
     */
    public function store(Request $request)
    {
        $this->checkSuperAdminPermission();
        $this->ensureManagerRoleExists();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:admins,email',
            'password' => 'required|string|min:6|confirmed',
            'role' => 'required|in:super_admin,manager,assistant,comptable',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'password.required' => 'Le mot de passe est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
        ]);

        try {
            $permissions = $this->getPermissionsByRole($validated['role']);

            // Sauvegarder le mot de passe en clair pour l'email (avant le hachage)
            $plainPassword = $validated['password'];

            DB::table('admins')->insert([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'role' => $validated['role'],
                'permissions' => json_encode($permissions),
                'bio' => $validated['bio'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'is_active' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Envoyer l'email avec les identifiants
            try {
                Mail::to($validated['email'])->send(
                    new AdminAccountCreated(
                        $validated['name'],
                        $validated['email'],
                        $plainPassword,
                        $validated['role']
                    )
                );

                Log::info('Email d\'identifiants envoyé', [
                    'admin_id' => session('admin_id'),
                    'new_admin_email' => $validated['email']
                ]);
            } catch (\Exception $mailException) {
                Log::error('Erreur lors de l\'envoi de l\'email: ' . $mailException->getMessage());
                // On continue même si l'email échoue
            }

            Log::info('Nouvel administrateur créé', [
                'admin_id' => session('admin_id'),
                'new_admin_email' => $validated['email'],
                'role' => $validated['role']
            ]);

            return redirect()->route('admin.statistics.total-admins')
                ->with('success', 'Administrateur créé avec succès. Un email avec les identifiants a été envoyé à ' . $validated['email']);

        } catch (\Exception $e) {
            Log::error('Erreur lors de la création d\'un administrateur: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la création.');
        }
    }

    /**
     * Afficher le formulaire d'édition d'un administrateur
     */
    public function edit($id)
    {
        $this->checkSuperAdminPermission();

        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            return redirect()->route('admin.statistics.total-admins')
                ->with('error', 'Administrateur introuvable.');
        }

        $roles = $this->getRolesWithPermissions();
        $currentAdminId = session('admin_id');

        return view('admin.admins.edit', compact('admin', 'roles', 'currentAdminId'));
    }

    /**
     * Mettre à jour un administrateur
     */
    public function update(Request $request, $id)
    {
        $this->checkSuperAdminPermission();
        $this->ensureManagerRoleExists();

        $admin = DB::table('admins')->where('id', $id)->first();

        if (!$admin) {
            return redirect()->route('admin.statistics.total-admins')
                ->with('error', 'Administrateur introuvable.');
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email',
                Rule::unique('admins', 'email')->ignore($id)
            ],
            'role' => 'required|in:super_admin,manager,assistant,comptable',
            'bio' => 'nullable|string|max:500',
            'phone' => 'nullable|string|max:20',
            'is_active' => 'required|boolean',
            'password' => 'nullable|string|min:6|confirmed',
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être valide.',
            'email.unique' => 'Cet email est déjà utilisé.',
            'role.required' => 'Le rôle est obligatoire.',
            'role.in' => 'Le rôle sélectionné n\'est pas valide.',
            'is_active.required' => 'Le statut est obligatoire.',
            'password.min' => 'Le mot de passe doit contenir au moins 6 caractères.',
            'password.confirmed' => 'Les mots de passe ne correspondent pas.',
        ]);

        try {
            $permissions = $this->getPermissionsByRole($validated['role']);

            $updateData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
                'role' => $validated['role'],
                'permissions' => json_encode($permissions),
                'bio' => $validated['bio'] ?? null,
                'phone' => $validated['phone'] ?? null,
                'is_active' => $validated['is_active'],
                'updated_at' => now(),
            ];

            // Mettre à jour le mot de passe uniquement si fourni
            if (!empty($validated['password'])) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            DB::table('admins')->where('id', $id)->update($updateData);

            Log::info('Administrateur mis à jour', [
                'admin_id' => session('admin_id'),
                'updated_admin_id' => $id,
                'role' => $validated['role']
            ]);

            return redirect()->route('admin.statistics.total-admins')
                ->with('success', 'Administrateur modifié avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la modification d\'un administrateur: ' . $e->getMessage());
            return back()->withInput()->with('error', 'Une erreur est survenue lors de la modification.');
        }
    }

    /**
     * Supprimer un administrateur
     */
    public function destroy($id)
    {
        $this->checkSuperAdminPermission();

        $currentAdminId = session('admin_id');

        // Empêcher la suppression de son propre compte
        if ($id == $currentAdminId) {
            return back()->with('error', 'Vous ne pouvez pas supprimer votre propre compte.');
        }

        try {
            $admin = DB::table('admins')->where('id', $id)->first();

            if (!$admin) {
                return back()->with('error', 'Administrateur introuvable.');
            }

            DB::table('admins')->where('id', $id)->delete();

            Log::info('Administrateur supprimé', [
                'admin_id' => $currentAdminId,
                'deleted_admin_id' => $id,
                'deleted_admin_email' => $admin->email
            ]);

            return redirect()->route('admin.statistics.total-admins')
                ->with('success', 'Administrateur supprimé avec succès.');

        } catch (\Exception $e) {
            Log::error('Erreur lors de la suppression d\'un administrateur: ' . $e->getMessage());
            return back()->with('error', 'Une erreur est survenue lors de la suppression.');
        }
    }

    /**
     * Vérifier si l'utilisateur connecté est un Super Admin
     */
    private function checkSuperAdminPermission()
    {
        if (session('admin_role') !== 'super_admin') {
            abort(403, 'Accès non autorisé. Seuls les Super Admins peuvent gérer les administrateurs.');
        }
    }

    /**
     * Obtenir les rôles avec leurs permissions
     */
    private function getRolesWithPermissions(): array
    {
        return [
            'super_admin' => [
                'label' => 'Super Admin',
                'description' => 'Accès complet à toutes les fonctionnalités',
                'access' => [
                    'Dashboard', 'Formations', 'Pré-inscriptions', 'Étudiants', 'Évènements',
                    'Actualités', 'Bibliothèque', 'TP', 'Projets', 'Paiements',
                    'Rapports', 'Statistiques', 'Gestion des Admins'
                ],
                'color' => '#1e3c72',
            ],
            'manager' => [
                'label' => 'Manager',
                'description' => 'Accès complet sauf comptabilité et paiements',
                'access' => [
                    'Dashboard', 'Formations', 'Pré-inscriptions', 'Étudiants', 'Évènements',
                    'Actualités', 'Bibliothèque', 'TP', 'Projets', 'Badges', 'Certificats',
                    'CVthèque', 'Rapports', 'Statistiques', 'WebTV', 'Plaquettes',
                    'Partenariats', 'Communiqués', 'Dons', 'Candidatures'
                ],
                'color' => '#f59e0b',
            ],
            'assistant' => [
                'label' => 'Assistant',
                'description' => 'Accès aux formations et gestion académique',
                'access' => [
                    'Formations', 'Pré-inscriptions', 'Étudiants', 'Évènements',
                    'Actualités', 'Bibliothèque', 'TP', 'Projets'
                ],
                'color' => '#4fc3f7',
            ],
            'comptable' => [
                'label' => 'Comptable',
                'description' => 'Accès aux paiements et étudiants par formation',
                'access' => [
                    'Paiements', 'Étudiants Design Graphique', 'Étudiants Community Management',
                    'Étudiants Gestion Informatique', 'Étudiants Intelligence Artificielle'
                ],
                'color' => '#9c27b0',
            ],
        ];
    }

    /**
     * Obtenir les permissions par rôle
     */
    private function getPermissionsByRole(string $role): array
    {
        $roles = $this->getRolesWithPermissions();
        return $roles[$role]['access'] ?? [];
    }
}
