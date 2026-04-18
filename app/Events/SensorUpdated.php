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

    public SensorData $sensorData;

    public function __construct(SensorData $sensorData)
    {
        $this->sensorData = $sensorData;
    }

    // Canal public pour dashboard IoT
    public function broadcastOn(): array
    {
        return [
            new Channel('sensors'),
        ];
    }

    // Nom de l’événement côté frontend (Echo / Reverb)
    public function broadcastAs(): string
    {
        return 'sensor.updated';
    }

    // Payload optimisé pour Chart + UI (IMPORTANT)
    public function broadcastWith(): array
    {
        return [
            'id'          => $this->sensorData->id,
            'device_id'   => $this->sensorData->device_id,
            'temperature' => (float) $this->sensorData->temperature,
            'humidity'    => (float) $this->sensorData->humidity,
            'pressure'    => $this->sensorData->pressure,
            'rssi'        => $this->sensorData->rssi,
            'uptime'      => $this->sensorData->uptime,
            'time'        => $this->sensorData->measured_at,
        ];
    }
}
