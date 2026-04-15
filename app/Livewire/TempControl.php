<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;
use PhpMqtt\Client\Facades\MQTT;

class TempControl extends Component
{
    public float $temp;
    public bool $isHeating = true;

    /**
     * Initialisation : on recupere la consigne enregistree en base.
     */
    public function mount()
    {
        $device = Device::where('type', 'temp')->first();
        $this->temp = $device ? (float)$device->value : 21.0;
        $this->isHeating = $this->temp > 20;
    }

    /**
     * Augmenter la temperature de 0.5 C.
     */
    public function increment()
    {
        if ($this->temp < 40) { // Securite max
            $this->temp += 0.5;
            $this->syncState();
        }
    }

    /**
     * Diminuer la temperature de 0.5 C.
     */
    public function decrement()
    {
        if ($this->temp > 5) { // Securite min
            $this->temp -= 0.5;
            $this->syncState();
        }
    }

    /**
     * Synchronise la base de donnees et envoie l'ordre au materiel via MQTT.
     */
    private function syncState()
    {
        // 1. Sauvegarde SQL
        Device::where('type', 'temp')->update(['value' => $this->temp]);

        // 2. Logique de chauffage
        $this->isHeating = $this->temp > 20;

        // 3. Envoi direct a l'ESP32 via MQTT (Topic de consigne)
        try {
            MQTT::publish('esp32/01/setpoint', (string)$this->temp);
        } catch (\Exception $e) {
            // Silencieux si le broker est injoignable
        }
    }

    /**
     * Mise a jour en temps reel si la valeur change via un autre terminal.
     */
    #[On('echo:sensors,SensorUpdated')]
    public function handleExternalUpdate($event)
    {
        // Si l'evenement contient une nouvelle consigne de temperature
        if (isset($event['sensorData']['temperature']) && $event['sensorData']['device_id'] === 'ESP32-001') {
            // Note: ici on decide si on suit le capteur reel ou la consigne
            // Pour ce widget, on rafraichit generalement la vue
            $this->render();
        }
    }

    public function render()
    {
        return view('livewire.temp-control');
    }
}
