<?php

namespace App\Observers;

use App\Models\Device;
use App\Events\SecurityAlert;

class DeviceObserver
{
    /**
     * Se déclenche après chaque modification (update) en base de données.
     */
    public function updated(Device $device): void
    {
        // 1. Si c'est un changement de température
        if ($device->type === 'temp') {
            // On pourrait créer un Event spécifique ou utiliser un canal générique
            // Pour cet exemple, on utilise la capacité de Livewire à écouter les modèles
        }

        // 2. Si c'est une alerte de sécurité (ex: passage de is_on à true pour une alerte)
        if ($device->type === 'security' && $device->wasChanged('is_on') && $device->is_on) {
            event(new SecurityAlert($device->room, "Mouvement détecté !"));
        }

        // Note : Avec Livewire 3, les composants peuvent aussi écouter
        // directement les changements de modèles via Echo si tu le souhaites.
    }

    /**
     * Optionnel : Se déclenche à la création d'un nouvel appareil.
     */
    public function created(Device $device): void
    {
        // Utile pour notifier qu'un nouvel appareil a été ajouté au réseau
    }
}
