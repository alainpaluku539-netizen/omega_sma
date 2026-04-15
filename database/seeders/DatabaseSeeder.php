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
        // 1. Creation de l'Administrateur principal
        User::factory()->create([
            'name' => 'Admin Omega',
            'email' => 'admin@omega.com',
            'password' => Hash::make('aaaaaaaa'),
            'role' => 'admin',
            'active' => true,
            'avatar' => 'https://ui-avatars.com',
        ]);

        // 2. Appel des Seeders (Appareils et Donnees Capteurs)
        $this->call([
            DeviceSeeder::class,
            SensorDataSeeder::class, // AJOUT : Pour peupler votre graphique
        ]);

        // 3. Creation de donnees historiques pour l'energie (24 dernieres heures)
        for ($i = 24; $i >= 0; $i--) {
            EnergyLog::create([
                'usage_kw' => rand(8, 35) / 10,
                'recorded_at' => now()->subHours($i),
            ]);
        }
    }
}
