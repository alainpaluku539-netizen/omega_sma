<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\EnergyLog;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Création de l'Administrateur principal (Nécessaire pour les relations)
        User::factory()->create([
            'name' => 'Admin Omega',
            'email' => 'admin@omega.com',
            'password' => Hash::make('aaaaaaaa'),
            'role' => 'admin',
            'active' => true,
            'avatar' => null, // Laissé à null pour utiliser le getter par défaut de ton Model
        ]);

        // 2. Appel des Seeders structurés
        $this->call([
            DeviceIdentitySeeder::class, // Identités des appareils (ESP32, etc.)
            DeviceSeeder::class,         // Instances d'appareils
            SensorDataSeeder::class,     // Historique des capteurs (Graphiques)
            DocumentSeeder::class,       // Système militaire (Docs In/Out)
        ]);

        // 3. Données historiques pour l'énergie
        for ($i = 24; $i >= 0; $i--) {
            EnergyLog::create([
                'usage_kw' => rand(8, 35) / 10,
                'recorded_at' => now()->subHours($i),
            ]);
        }
    }
}
