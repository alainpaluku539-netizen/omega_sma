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

    public EnergyLog $energyLog;

    public function __construct(EnergyLog $energyLog)
    {
        $this->energyLog = $energyLog;
    }

    // CANAL PUBLIC ENERGY DASHBOARD
    public function broadcastOn(): array
    {
        return [
            new Channel('energy'),
        ];
    }

    // NOM EVENT FRONTEND
    public function broadcastAs(): string
    {
        return 'energy.updated';
    }

    // PAYLOAD CLEAN POUR CHART.JS
    public function broadcastWith(): array
    {
        return [
            'id'         => $this->energyLog->id,
            'usage_kw'   => (float) $this->energyLog->usage_kw,
            'recorded_at' => $this->energyLog->recorded_at?->format('H:i:s'),
            'timestamp'  => $this->energyLog->recorded_at?->timestamp,
        ];
    }
}
