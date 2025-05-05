<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role; // 🇬🇧 Import the Role model / 🇫🇷 Importer le modèle Role
use Illuminate\Support\Facades\Hash;

/**
 * 🇬🇧 UserSeeder class for populating the "users" table.
 * 🇫🇷 Classe UserSeeder pour le peuplement de la table "users".
 */
class UserSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Retrieve roles by their name.
        // 🇫🇷 Récupérer les rôles par leur nom.
        $adminRole = Role::where('name', 'admin')->first();
        $userRole = Role::where('name', 'user')->first();
        $athletRole = Role::where('name', 'athlet')->first();
        $coachRole = Role::where('name', 'coach')->first();

        // 🇬🇧 Create users.
        // 🇫🇷 Créer les utilisateurs.

        // 🇬🇧 Create an admin user.
        // 🇫🇷 Créer un utilisateur administrateur.
        User::create([
            'username' => 'admin',
            'email' => 'admin@admin.net',
            'password' => Hash::make('admin123'), // 🇬🇧 Hashed password / 🇫🇷 Mot de passe haché
            'first_name' => 'Hassan',
            'last_name' => 'ELHAOUAT',
            'role_id' => $adminRole->id,
        ]);

        // 🇬🇧 Create a regular user.
        // 🇫🇷 Créer un utilisateur standard.
        User::create([
            'username' => 'user1',
            'email' => 'user@user.net',
            'password' => Hash::make('user123'),
            'first_name' => 'Hassan1',
            'last_name' => 'ELHAOUAT2',
            'role_id' => $userRole->id,
        ]);

        // 🇬🇧 Create an athlete user.
        // 🇫🇷 Créer un utilisateur athlète.
        User::create([
            'username' => 'athlet',
            'email' => 'athlet@athlet.net',
            'password' => Hash::make('athlet123'),
            'first_name' => 'athlet',
            'last_name' => 'sportif',
            'role_id' => $athletRole->id,
        ]);

        // 🇬🇧 Create a coach user.
        // 🇫🇷 Créer un utilisateur entraîneur.
        User::create([
            'username' => 'coach',
            'email' => 'coach@coach.net',
            'password' => Hash::make('coach123'),
            'first_name' => 'coach',
            'last_name' => 'entraineur',
            'role_id' => $coachRole->id,
        ]);
    }
}
