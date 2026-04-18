<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;
use App\Events\SensorUpdated;

class SensorController extends Controller
{
    // ==========================================================
    // STORE SENSOR DATA (ESP32 / MQTT / FLASK)
    // ==========================================================
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id'   => 'required|string',
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'pressure'    => 'nullable|numeric',
            'rssi'        => 'nullable|integer',
            'uptime'      => 'nullable|integer'
        ]);

        $data = SensorData::create([
            'device_id'   => $validated['device_id'],
            'temperature' => $validated['temperature'],
            'humidity'    => $validated['humidity'],
            'pressure'    => $validated['pressure'] ?? null,
            'rssi'        => $validated['rssi'] ?? null,
            'measured_at' => now(),
        ]);

        // REALTIME BROADCAST
        broadcast(new SensorUpdated($data));

        return response()->json([
            'status' => 'success',
            'id' => $data->id
        ], 201);
    }

    // ==========================================================
    // LATEST + HISTORY (DASHBOARD)
    // ==========================================================
    public function latest()
    {
        try {

            $latest = SensorData::latest('id')->first();

            if (!$latest) {
                return response()->json([
                    'current' => null,
                    'history' => []
                ]);
            }

            $history = SensorData::latest('id')
                ->take(30)
                ->get()
                ->reverse()
                ->values()
                ->map(function ($item) {
                    return [
                        'measured_at' => $item->measured_at,
                        'temperature' => (float) $item->temperature,
                        'humidity'    => (float) $item->humidity,
                        'rssi'        => $item->rssi
                    ];
                });

            return response()->json([
                'current' => [
                    'device_id'  => $latest->device_id,
                    'temperature' => (float) $latest->temperature,
                    'humidity'   => (float) $latest->humidity,
                    'rssi'       => $latest->rssi,
                    'measured_at' => $latest->measured_at
                ],
                'history' => $history
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => $e->getMessage()
            ], 500);
        }
    }

    // ==========================================================
    // RELAY CONTROL (MQTT)
    // ==========================================================
    public function controlLed(Request $request)
    {
        $validated = $request->validate([
            'relay'  => 'nullable|integer|min:0|max:3',
            'action' => 'nullable|in:ON,OFF',
            'cmd'    => 'nullable|string'
        ]);

        try {

            $payload = [];

            if (isset($validated['cmd'])) {
                $payload = ['cmd' => $validated['cmd']];
            } else {
                $payload = [
                    'relay' => $validated['relay'],
                    'state' => $validated['action']
                ];
            }

            \PhpMqtt\Client\Facades\MQTT::publish(
                'esp32/01/cmd',
                json_encode($payload)
            );

            return response()->json([
                'status' => 'success',
                'sent' => $payload
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'error' => 'MQTT Error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
