<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\On;
use App\Models\SensorData as SensorModel;

class SensorData extends Component
{
    public $device;
    public $temperature = 0;
    public $humidity = 0;
    public $pressure = 0;
    public $status = 'offline';

    public function mount()
    {
        $this->loadInitialData();
    }

    // ======================================================
    // CHARGE INITIALE (UNE SEULE FOIS)
    // ======================================================
    public function loadInitialData()
    {
        $data = SensorModel::latest('id')->first();

        if (!$data) return;

        $this->applyData($data);
    }

    // ======================================================
    // REALTIME EVENT REVERB
    // ======================================================
    #[On('echo:sensors,sensor.updated')]
    public function sensorUpdated($event)
    {
        // Directement les données broadcastWith()
        $this->device = $event['device_id'] ?? $this->device;
        $this->temperature = (float) ($event['temperature'] ?? 0);
        $this->humidity = (float) ($event['humidity'] ?? 0);
        $this->pressure = (float) ($event['pressure'] ?? 0);
        $this->status = 'online';

        // PUSH FRONT CHART
        $this->dispatch('update-sensor-chart', [
            'temp' => $this->temperature,
            'hum'  => $this->humidity,
            'time' => now()->format('H:i:s')
        ]);
    }

    // ======================================================
    // APPLY DATA (UTILITAIRE)
    // ======================================================
    private function applyData($data)
    {
        $this->device = $data->device_id;
        $this->temperature = (float) $data->temperature;
        $this->humidity = (float) $data->humidity;
        $this->pressure = (float) $data->pressure;
        $this->status = $data->status ?? 'offline';
    }

    public function render()
    {
        return view('livewire.sensor-data');
    }
}
