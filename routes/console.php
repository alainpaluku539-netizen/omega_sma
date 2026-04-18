<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Models\SensorData;
use App\Events\SensorUpdated;

/*

|--------------------------------------------------------------------------
| OMEGA IOT CONSOLE - SYSTEM CONTROL
|--------------------------------------------------------------------------
*/

// Commande inspirante par défaut
Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * ⚡ SIMULATION GLOBALE (MODE DÉMO GITHUB)
 * Planifie la simulation de consommation d'énergie chaque minute.
 * Utilise la commande existante pour garantir l'enregistrement en DB + Broadcast.
 */
Schedule::command('app:simulate-energy-usage')->everyMinute();

/**
 * 🌡️ SIMULATION CAPTEURS (OPTIONNEL)
 * Si vous n'avez pas l'ESP32 branché, cette tâche simule une activité thermique.
 */
Schedule::call(function () {
    $temp = rand(210, 250) / 10; // 21.0 à 25.0 °C
    $hum  = rand(45, 60);

    $data = SensorData::create([
        'device_id'   => 'VIRTUAL_NODE_01',
        'temperature' => $temp,
        'humidity'    => $hum,
        'rssi'        => -55,
        'measured_at' => now(),
    ]);

    broadcast(new SensorUpdated($data));
})->everyTwoMinutes();

/**
 * 🧹 NETTOYAGE DU SYSTÈME
 * Supprime les logs de capteurs vieux de plus de 30 jours pour garder MySQL rapide.
 */
Schedule::call(function () {
    SensorData::where('measured_at', '<', now()->subDays(30))->delete();
})->daily();
