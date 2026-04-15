<?php

namespace App\Livewire;

use App\Models\Device;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Database\QueryException;

class Devices extends Component
{
    public $showModal = false;

    // Champs du formulaire
    public $name, $device_id, $room, $unit;
    public $type = 'switch';

    /**
     * Règles de validation
     */
    protected $rules = [
        'name' => 'required|min:3|max:50',
        'device_id' => 'required|unique:devices,device_id',
        'type' => 'required|in:temperature,humidity,light,energy,switch',
        'room' => 'required|min:2',
    ];

    /**
     * Traduction française des erreurs
     */
    protected $messages = [
        'name.required' => 'Le nom est obligatoire.',
        'name.min' => 'Le nom est trop court (min 3).',
        'device_id.required' => 'L\'ID MQTT est requis.',
        'device_id.unique' => 'Cet ID est déjà utilisé.',
        'room.required' => 'Précisez une pièce.',
        'type.required' => 'Le type est obligatoire.',
    ];

    /**
     * Enregistre un nouvel appareil
     */
    public function save()
    {
        $this->validate();

        try {
            Device::create([
                'name' => $this->name,
                'device_id' => trim($this->device_id),
                'type' => $this->type,
                'room' => $this->room,
                'unit' => $this->unit,
                'status' => 'offline',
                'is_on' => false,
                'last_seen' => now(),
            ]);

            $this->reset(['name', 'device_id', 'room', 'unit', 'showModal']);
            $this->dispatch('notify', ['type' => 'success', 'text' => 'Module ajouté avec succès !']);

        } catch (QueryException $e) {
            $this->dispatch('notify', ['type' => 'error', 'text' => 'Erreur SQL : Vérifiez vos données.']);
        }
    }

    public function toggleDevice($id)
    {
        try {
            $device = Device::findOrFail($id);
            $device->toggle();
            $this->dispatch('notify', ['type' => 'success', 'text' => "{$device->name} mis à jour."]);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'text' => 'Action impossible.']);
        }
    }

    public function deleteDevice($id)
    {
        try {
            $device = Device::findOrFail($id);
            $device->delete();
            $this->dispatch('notify', ['type' => 'info', 'text' => 'Appareil supprimé.']);
        } catch (\Exception $e) {
            $this->dispatch('notify', ['type' => 'error', 'text' => 'Suppression échouée.']);
        }
    }

    #[Layout('layouts::app')]
    public function render()
    {
        return view('livewire.devices', [
            'devices' => Device::orderBy('room')->get()
        ]);
    }
}
