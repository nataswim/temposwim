<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Role;

/**
 * 🇬🇧 RoleSeeder class for populating the "roles" table.
 * 🇫🇷 Classe RoleSeeder pour le peuplement de la table "roles".
 */
class RoleSeeder extends Seeder
{
    /**
     * 🇬🇧 Run the database seeds.
     * 🇫🇷 Exécuter le peuplement de la base de données.
     */
    public function run(): void
    {
        // 🇬🇧 Create default roles.
        // 🇫🇷 Créer les rôles par défaut.
        Role::create(['name' => 'admin']); // 🇬🇧 Administrator / 🇫🇷 Administrateur
        Role::create(['name' => 'user']); // 🇬🇧 Regular user / 🇫🇷 Utilisateur standard
        Role::create(['name' => 'athlet']); // 🇬🇧 Athlete role / 🇫🇷 Rôle d'athlète
        Role::create(['name' => 'coach']); // 🇬🇧 Coach role / 🇫🇷 Rôle d'entraîneur
    }
}
