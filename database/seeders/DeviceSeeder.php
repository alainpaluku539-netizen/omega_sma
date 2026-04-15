<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Device;
use Illuminate\Support\Carbon;

class DeviceSeeder extends Seeder
{
    public function run(): void
    {
        // On définit 4 appareils uniques, un pour chaque canal (0, 1, 2, 3)
        $devices = [
            [
                'device_id' => 'OMEGA_NODE_01', // Relay 0 -> Pin 12 + LED 18
                'name'      => 'Éclairage Principal',
                'type'      => 'light',
                'room'      => 'Salon',
                'value'     => 100,
                'is_on'     => false,
                'status'    => 'online',
                'last_seen' => Carbon::now(),
            ],
            [
                'device_id' => 'OMEGA_NODE_02', // Relay 1 -> Pin 13 + LED 19
                'name'      => 'Ventilation Nord',
                'type'      => 'switch',
                'room'      => 'Cuisine',
                'is_on'     => false,
                'status'    => 'online',
                'last_seen' => Carbon::now(),
            ],
            [
                'device_id' => 'OMEGA_NODE_03', // Relay 2 -> Pin 14 + LED 21
                'name'      => 'Système Chauffage',
                'type'      => 'switch',
                'room'      => 'Chambre',
                'is_on'     => false,
                'status'    => 'online',
                'last_seen' => Carbon::now(),
            ],
            [
                'device_id' => 'OMEGA_NODE_04', // Relay 3 -> Pin 27 + LED 22
                'name'      => 'Projecteur Jardin',
                'type'      => 'light',
                'room'      => 'Garage',
                'value'     => 50,
                'is_on'     => false,
                'status'    => 'online',
                'last_seen' => Carbon::now(),
            ],
        ];

        foreach ($devices as $device) {
            Device::updateOrCreate(
                ['device_id' => $device['device_id']], 
                $device
            );
        }
    }
}
