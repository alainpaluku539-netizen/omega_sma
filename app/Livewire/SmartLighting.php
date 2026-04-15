<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\On;

class SmartLighting extends Component
{
    public $brightness = 64;
    public $activeColor = '#fbbf24';
    public $selectedRoom = 'Living Room';

    /**
     * Recupere l'etat initial d'une piece au chargement.
     */
    public function mount()
    {
        $device = Device::where('room', $this->selectedRoom)->where('type', 'light')->first();
        if ($device) {
            $this->brightness = $device->value;
            $this->activeColor = $device->color ?? '#fbbf24';
        }
    }

    /**
     * Alume/Eteint une piece specifique.
     */
    public function toggleRoom($room)
    {
        try {
            $this->selectedRoom = $room;
            $device = Device::where('room', $room)->where('type', 'light')->firstOrFail();
            $device->update(['is_on' => !$device->is_on]);

            $this->dispatch('notify', [
                'type' => 'success',
                'title' => 'Eclairage',
                'text' => "Zone $room mise a jour."
            ]);
        } catch (\Exception $e) {
            $this->dispatch('notify', [
                'type' => 'error',
                'title' => 'Erreur Systeme',
                'text' => "Impossible de joindre le module $room."
            ]);
        }
    }

    /**
     * Met a jour l'intensite lumineuse en base de donnees.
     */
    public function updatedBrightness($value)
    {
        Device::where('room', $this->selectedRoom)
            ->where('type', 'light')
            ->update(['value' => (int) $value]);
    }

    /**
     * Met a jour la couleur en base de donnees.
     */
    public function updatedActiveColor($value)
    {
        Device::where('room', $this->selectedRoom)
            ->where('type', 'light')
            ->update(['color' => $value]);
    }

    /**
     * Allume toutes les lumieres.
     */
    public function allOn()
    {
        Device::where('type', 'light')->update(['is_on' => true]);
        $this->dispatch('notify', ['type' => 'info', 'text' => 'Toutes les lumieres sont allumees']);
    }

    /**
     * Eteint tout.
     */
    public function allOff()
    {
        Device::where('type', 'light')->update(['is_on' => false]);
        $this->dispatch('notify', ['type' => 'info', 'text' => 'Toutes les lumieres sont eteintes']);
    }

    /**
     * Ecoute les mises a jour en temps reel via Reverb.
     */
    #[On('echo:sensors,SensorUpdated')]
    public function handleExternalUpdate()
    {
        // Recharge les donnees de la piece selectionnee pour synchroniser l'UI
        $device = Device::where('room', $this->selectedRoom)->where('type', 'light')->first();
        if ($device) {
            $this->brightness = $device->value;
            $this->activeColor = $device->color;
        }
    }

    public function render()
    {
        return view('livewire.smart-lighting', [
            'devices' => Device::where('type', 'light')->get()
        ]);
    }
}
