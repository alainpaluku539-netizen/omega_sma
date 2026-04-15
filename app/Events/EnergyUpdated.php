<?php

namespace App\Events;

use App\Models\EnergyLog;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class EnergyUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $energyData;

    /**
     * On passe les données au constructeur pour qu'elles soient envoyées au JS
     */
    public function __construct(EnergyLog $energyLog)
    {
        $this->energyData = [
            'usage_kw' => $energyLog->usage_kw,
            'recorded_at' => $energyLog->recorded_at->format('H:i:s'),
        ];
    }

    /**
     * On diffuse sur un canal public pour que le Dashboard puisse l'écouter
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('energy'),
        ];
    }

    /**
     * Nom de l'événement côté JavaScript
     */
    public function broadcastAs()
    {
        return 'EnergyDataChanged';
    }
}
