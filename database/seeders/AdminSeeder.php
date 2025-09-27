<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if admin already exists
        $existingAdmin = DB::table('admins')->where('email', 'admin@ecolevirtuelledescreatifs.com')->first();
        
        if (!$existingAdmin) {
            DB::table('admins')->insert([
                'name' => 'Administrateur EVC',
                'email' => 'admin@ecolevirtuelledescreatifs.com',
                'password' => Hash::make('admin123'),
                'role' => 'super_admin',
                'permissions' => json_encode([
                    'users_management',
                    'documents_validation',
                    'statistics_view',
                    'admin_management',
                    'system_settings'
                ]),
                'is_active' => true,
                'email_verified_at' => now(),
                'created_at' => now(),
                'updated_at' => now()
            ]);
            
            echo "✅ Administrateur par défaut créé :\n";
            echo "Email: admin@ecolevirtuelledescreatifs.com\n";
            echo "Mot de passe: admin123\n";
        } else {
            echo "ℹ️ Administrateur par défaut existe déjà.\n";
        }
    }
}
