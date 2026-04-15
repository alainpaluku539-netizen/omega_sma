<?php

namespace App\Events;

use App\Models\SensorData;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SensorUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $sensorData;

    /**
     * On passe l'objet SensorData au constructeur.
     * Toutes les propriétés publiques de cet événement seront envoyées en JSON.
     */
    public function __construct(SensorData $sensorData)
    {
        $this->sensorData = $sensorData;
    }

    /**
     * On utilise un canal public "sensors" pour que le dashboard puisse écouter sans login complexe.
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('sensors'),
        ];
    }

    /**
     * Optionnel : Le nom de l'événement tel qu'il apparaîtra dans le JavaScript.
     * Par défaut, c'est le nom de la classe.
     */
    public function broadcastAs(): string
    {
        return 'SensorUpdated';
    }
}
