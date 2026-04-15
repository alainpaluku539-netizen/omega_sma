<?php

namespace App\Console\Commands;

use App\Models\EnergyLog;
use App\Events\EnergyUpdated;
use Illuminate\Console\Command;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;

#[Signature('app:simulate-energy-usage')]
#[Description('Simule une consommation électrique et diffuse l\'événement en temps réel')]
class SimulateEnergyUsage extends Command
{
    public function handle()
    {
        // 1. Générer une valeur réaliste (entre 0.5 et 3.5 kW)
        $usage = rand(5, 35) / 10;

        // 2. Enregistrer en base de données
        $log = EnergyLog::create([
            'usage_kw' => $usage,
            'recorded_at' => now(),
        ]);

        // 3. Diffuser l'événement pour Reverb / Dashboard
        broadcast(new EnergyUpdated($log));

        $this->info("Consommation simulée : {$usage} kW (Donnée envoyée au Dashboard)");
    }
}
