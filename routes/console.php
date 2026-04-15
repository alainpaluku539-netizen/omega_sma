<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use App\Events\EnergyUpdated;

/*

|--------------------------------------------------------------------------
| Console Routes & Scheduler
|--------------------------------------------------------------------------
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

/**
 * Simulation de consommation d'énergie toutes les minutes.
 * Cela envoie des données aléatoires à Reverb pour faire bouger le graphique.
 */
Schedule::call(function () {
    // Génère 10 points de données (entre 0.5kW et 4.0kW)
    $newData = collect(range(1, 10))->map(fn() => rand(5, 40) / 10)->toArray();

    // Diffuse l'événement sur le canal privé de l'utilisateur 1
    broadcast(new EnergyUpdated($newData));
})->everyMinute();