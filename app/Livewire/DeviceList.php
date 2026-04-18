<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\SensorData;

class DeviceList extends Component
{
    public $devices = [];
    public $totalDevices = 0;
    public $onlineDevices = 0;
    public $offlineDevices = 0;

    public function mount()
    {
        $this->loadDevices();
    }

    /**
     * ======================================================
     * REALTIME UPDATE VIA REVERB / ECHO
     * ======================================================
     */
    #[On('echo:sensors,sensor.updated')]
    public function refreshDevices()
    {
        $this->loadDevices();
    }

    /**
     * ======================================================
     * LOAD DEVICE LIST
     * ======================================================
     */
    public function loadDevices()
    {
        $latestDevices = SensorData::select('device_id')
            ->distinct()
            ->pluck('device_id');

        $devices = [];

        foreach ($latestDevices as $deviceId) {

            $last = SensorData::where('device_id', $deviceId)
                ->latest('measured_at')
                ->first();

            if (!$last) continue;

            $seconds = now()->diffInSeconds($last->measured_at);

            $status = $seconds <= 30 ? 'online' : 'offline';

            $devices[] = [
                'device_id'   => $deviceId,
                'temperature' => (float) $last->temperature,
                'humidity'    => (float) $last->humidity,
                'pressure'    => (float) ($last->pressure ?? 0),
                'rssi'        => (int) ($last->rssi ?? 0),
                'uptime'      => (int) ($last->uptime ?? 0),
                'status'      => $status,
// Remplace la ligne 65 par :
'last_seen' => \Carbon\Carbon::parse($last->measured_at)->diffForHumans(),
                'updated_at'  => $last->measured_at,
            ];
        }

        usort(
            $devices,
            fn($a, $b) =>
            strtotime($b['updated_at']) <=> strtotime($a['updated_at'])
        );

        $this->devices = $devices;

        $this->totalDevices = count($devices);
        $this->onlineDevices = collect($devices)->where('status', 'online')->count();
        $this->offlineDevices = collect($devices)->where('status', 'offline')->count();
    }

    /**
     * ======================================================
     * FORCE MANUAL REFRESH
     * ======================================================
     */
    public function refreshNow()
    {
        $this->loadDevices();
    }

    public function render()
    {
        return view('livewire.device-list');
    }
}
