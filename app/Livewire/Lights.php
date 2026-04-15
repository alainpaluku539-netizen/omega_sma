<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\Layout;
use PhpMqtt\Client\Facades\MQTT;

class Lights extends Component
{
    /**
     * Bascule l'état ON/OFF et envoie l'ordre MQTT à l'ESP32
     */
    public function toggleLight($id)
    {
        try {
            $device = Device::findOrFail($id);
            $device->toggle();

            // On calcule l'index (0 à 3) pour l'ESP32
            $relayIndex = $this->getRelayIndex($device->device_id);

            // Format JSON strict pour le code C++ : {"relay": 0, "state": "ON"}
            $payload = json_encode([
                'relay' => $relayIndex,
                'state' => $device->is_on ? 'ON' : 'OFF'
            ]);

            // Envoi au topic de commande
            MQTT::publish('esp32/01/cmd', $payload);

            $this->dispatch('notify', [
                'type' => 'success',
                'text' => "{$device->name} : " . ($device->is_on ? 'Allumé' : 'Éteint') . " (Canal {$relayIndex})"
            ]);

        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error', 
                'text' => 'Erreur de liaison MQTT'
            ]);
        }
    }

    /**
     * Mise à jour de l'intensité (Slider)
     */
    public function updateBrightness($id, $value)
    {
        try {
            $device = Device::findOrFail($id);
            $device->update(['value' => $value]);

            $relayIndex = $this->getRelayIndex($device->device_id);

            // Format pour la gradation : {"relay": 0, "dim": 75}
            $payload = json_encode([
                'relay' => $relayIndex,
                'dim'   => (int)$value
            ]);
            
            MQTT::publish('esp32/01/dim', $payload);

        } catch (\Exception $e) {
            // Silencieux pour le slider
        }
    }

    /**
     * Allumage général de tous les relais
     */
    public function allOn()
    {
        Device::whereIn('type', ['light', 'switch'])->update(['is_on' => true]);
        
        // Envoi d'une commande globale gérée par le C++
        MQTT::publish('esp32/01/cmd', json_encode(['cmd' => 'ON']));
        
        $this->dispatch('notify', ['type' => 'info', 'text' => 'Full Power : Tous les systèmes actifs']);
    }

    /**
     * Extinction générale
     */
    public function allOff()
    {
        Device::whereIn('type', ['light', 'switch'])->update(['is_on' => false]);
        
        // Envoi d'une commande globale gérée par le C++
        MQTT::publish('esp32/01/cmd', json_encode(['cmd' => 'OFF']));
        
        $this->dispatch('notify', ['type' => 'info', 'text' => 'Blackout : Veille générale activée']);
    }

    /**
     * HELPER : Extrait le numéro de l'ID et le convertit en index 0-3
     * Exemple : "NODE_01" -> retourne 0
     * Exemple : "RELAY_4"  -> retourne 3
     */
    private function getRelayIndex($deviceId) 
    {
        // On extrait uniquement les chiffres de la chaîne (ex: "NODE_02" -> 02)
        $number = (int) filter_var($deviceId, FILTER_SANITIZE_NUMBER_INT);
        
        // On s'assure que si le chiffre est entre 1 et 4, on retourne 0 à 3
        // Sinon on retourne 0 par défaut pour éviter les crashs d'index C++
        return ($number > 0) ? ($number - 1) : 0;
    }

    #[Layout('layouts.app')]
    public function render()
    {
        return view('livewire.lights', [
            'lights' => Device::whereIn('type', ['light', 'switch'])
                             ->orderBy('room')
                             ->get()
        ]);
    }
}
