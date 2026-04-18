<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DeviceIdentitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nettoyage de la table pour éviter les doublons
        \App\Models\DeviceIdentity::truncate();

        $devices = [
            [
                'device_id' => 'ESP32-TH-01',
                'name'      => 'Capteur Salon',
                'type'      => 'sensor',
                'room'      => 'Salon',
                'is_active' => true,
                'metadata'  => [
                    'model' => 'DHT22',
                    'connection' => 'WiFi',
                    'battery' => '98%'
                ],
            ],
            [
                'device_id' => 'ESP32-LGT-01',
                'name'      => 'Ruban LED TV',
                'type'      => 'light',
                'room'      => 'Salon',
                'is_active' => true,
                'metadata'  => [
                    'protocol' => 'MQTT',
                    'features' => ['dimmable', 'rgb']
                ],
            ],
            [
                'device_id' => 'ESP32-CAM-01',
                'name'      => 'Portier Vidéo',
                'type'      => 'camera',
                'room'      => 'Entrée',
                'is_active' => true,
                'metadata'  => [
                    'resolution' => '1080p',
                    'stream_url' => '/live/entry'
                ],
            ],
            [
                'device_id' => 'ESP32-SEC-01',
                'name'      => 'Détecteur Mouvement',
                'type'      => 'security',
                'room'      => 'Couloir',
                'is_active' => true,
                'metadata'  => [
                    'sensor' => 'PIR',
                    'sensitivity' => 'medium'
                ],
            ],
        ];

        foreach ($devices as $device) {
            \App\Models\DeviceIdentity::create($device);
        }
    }

}
