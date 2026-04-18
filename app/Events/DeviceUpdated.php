<?php

namespace App\Events;

use App\Models\Device;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class DeviceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Device $device;

    public function __construct(Device $device)
    {
        $this->device = $device;
    }

    // Canal public pour la gestion globale des périphériques
    public function broadcastOn(): array
    {
        return [
            new Channel('devices'),
        ];
    }

    // Nom de l'événement pour Echo/Reverb
    public function broadcastAs(): string
    {
        return 'device.status.updated';
    }

    // Données envoyées au Dashboard
    public function broadcastWith(): array
    {
        return [
            'device_id' => $this->device->device_id,
            'name'      => $this->device->name,
            'status'    => $this->device->status, // online / offline
            'is_on'     => (bool) $this->device->is_on,
            'last_seen' => $this->device->last_seen?->diffForHumans(), // "Il y a 2 min"
        ];
    }
}
