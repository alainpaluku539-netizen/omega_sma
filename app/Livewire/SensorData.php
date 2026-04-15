<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\SensorData as SensorModel;
use Livewire\Attributes\On;

class SensorData extends Component
{
    public $device;
    public $temperature = 0;
    public $humidity = 0;
    public $pressure = 0;
    public $status = 'offline';

    public function mount()
    {
        $this->loadData();
    }

    /**
     * Charge les dernières données et dispatch un événement pour le graphique JS
     */
    #[On('echo:sensors,SensorUpdated')] 
    public function loadData()
    {
        $data = SensorModel::latest('measured_at')->first();

        if ($data) {
            $this->device = trim(mb_convert_encoding($data->device_id, 'UTF-8', 'UTF-8'));
            $this->status = trim(mb_convert_encoding($data->status, 'UTF-8', 'UTF-8'));
            $this->temperature = (float) $data->temperature;
            $this->humidity = (float) $data->humidity;
            $this->pressure = (float) $data->pressure;

            // Envoie les données vers Chart.js via un événement navigateur
            $this->dispatch('update-sensor-chart', [
                'temp' => $this->temperature,
                'hum' => $this->humidity
            ]);
        }
    }

    public function render()
    {
        return view('livewire.sensor-data');
    }
}
