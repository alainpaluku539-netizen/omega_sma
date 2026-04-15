<?php

namespace Database\Seeders;

use App\Models\SensorData;
use Illuminate\Database\Seeder;
use Carbon\Carbon;

class SensorDataSeeder extends Seeder
{
    public function run(): void
    {
        $now = Carbon::now();

        // Génère 20 points de données pour peupler le graphique
        for ($i = 20; $i >= 0; $i--) {
            SensorData::create([
                'device_id' => 'ESP32-001',
                'temperature' => 22 + (rand(-10, 10) / 5), // Oscille entre 20 et 24
                'humidity' => 55 + rand(-5, 5),           // Oscille entre 50 et 60
                'pressure' => 1012 + rand(-2, 2),
                'status' => 'online',
                'measured_at' => $now->copy()->subMinutes($i * 5),
                'created_at' => $now->copy()->subMinutes($i * 5),
                'updated_at' => $now->copy()->subMinutes($i * 5),
            ]);
        }
    }
}
