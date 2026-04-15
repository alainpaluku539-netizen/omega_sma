<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;

class SecuritySystem extends Component
{
    public bool $isArmed = true;
    public array $alerts = [];
    public string $lastMovement = 'No activity detected';

    /**
     * Chargement initial de l'état de sécurité.
     */
    public function mount()
    {
        $device = Device::where('type', 'security')->first();
        $this->isArmed = $device ? $device->is_on : true;
    }

    /**
     * Active ou désactive le système de sécurité global.
     */
    public function toggleSecurity()
    {
        $this->isArmed = !$this->isArmed;
        Device::where('type', 'security')->update(['is_on' => $this->isArmed]);
    }

    /**
     * Écoute les alertes de sécurité diffusées par Reverb.
     * Déclenche une notification visuelle sur le dashboard.
     */
    #[On('echo-private:user.1.energy,SecurityAlert')]
    public function handleSecurityAlert($event)
    {
        $this->lastMovement = $event['message'] . " in " . $event['location'];

        // Ajouter à la liste des alertes récentes
        array_unshift($this->alerts, [
            'time' => now()->format('H:i'),
            'location' => $event['location'],
            'message' => $event['message']
        ]);

        // Limiter à 5 alertes
        $this->alerts = array_slice($this->alerts, 0, 5);

        // Envoyer un événement au navigateur pour le "Toast" de notification Alpine.js
        $this->dispatch('notify',
            title: 'Security Alert',
            message: $this->lastMovement
        );
    }

    public function render()
    {
        return view('livewire.security-system');
    }
}
