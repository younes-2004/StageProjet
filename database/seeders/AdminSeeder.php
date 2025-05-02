<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Utilisateur;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Utilisateur::create([
            'nom' => 'Admin',
            'prenom' => 'Super',
            'email' => 'admin@projetstage.com', // Email de l'administrateur
            'mot_de_passe' => Hash::make('Admin@2025'), // Mot de passe sécurisé
            'role' => 'greffier_en_chef', // Rôle de greffier en chef
            'service_id' => 1, // Pas de service associé
        ]);
    }
}
