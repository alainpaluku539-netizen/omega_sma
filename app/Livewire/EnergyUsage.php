<?php
// Fichier : app/Livewire/EnergyUsage.php

namespace App\Livewire;

use App\Models\EnergyLog;
use Livewire\Component;
use Livewire\Attributes\On;

class EnergyUsage extends Component
{
    public array $stats = [];
    public float $currentUsage = 0.0;
    public float $totalToday = 0.0;
    public float $average = 0.0;
    public float $peak = 0.0;

    /**
     * Initialisation
     */
    public function mount()
    {
        $this->loadStats();
    }

    /**
     * Charger les données initiales depuis la DB
     */
    public function loadStats()
    {
        $logs = EnergyLog::latest('recorded_at')
            ->take(10)
            ->get()
            ->reverse();

        $this->stats = $logs->pluck('usage_kw')
            ->map(fn($v) => (float) $v)
            ->toArray();

        if (!empty($this->stats)) {
            $this->currentUsage = end($this->stats);
            $this->peak = max($this->stats);
            $this->average = array_sum($this->stats) / count($this->stats);
        }

        $this->totalToday = (float) EnergyLog::whereDate('recorded_at', today())
            ->sum('usage_kw');
    }

    /**
     * Mise à jour temps réel (Reverb)
     */
    #[On('echo:energy,EnergyDataChanged')]
    public function handleEnergyUpdate($event)
    {
        $newValue = (float) ($event['usage_kw'] ?? 0);

        // Ajouter nouvelle valeur
        $this->stats[] = $newValue;

        // Garder 10 valeurs max
        if (count($this->stats) > 10) {
            array_shift($this->stats);
        }

        $this->currentUsage = $newValue;
        $this->totalToday += $newValue;

        // Recalcul stats
        $this->peak = max($this->stats);
        $this->average = array_sum($this->stats) / count($this->stats);

        // Envoyer au frontend
        $this->dispatch('statsUpdated', data: $this->stats);
    }

    public function render()
    {
        return view('livewire.energy-usage');
    }
}