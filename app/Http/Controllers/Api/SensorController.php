<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SensorData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use PhpMqtt\Client\Facades\MQTT;
use App\Events\SensorUpdated; // Importation de l'événement

class SensorController extends Controller
{
    // 1. Pour l'ESP32 : Reçoit les données et les diffuse en temps réel
    public function store(Request $request)
    {
        $validated = $request->validate([
            'device_id'   => 'required|string',
            'temperature' => 'required|numeric',
            'humidity'    => 'required|numeric',
            'pressure'    => 'nullable|numeric',
            'uptime'      => 'nullable|integer'
        ]);

        $data = SensorData::create([
            'device_id'   => $validated['device_id'],
            'temperature' => $validated['temperature'],
            'humidity'    => $validated['humidity'],
            'pressure'    => $validated['pressure'] ?? 0,
            'uptime'      => $validated['uptime'] ?? 0,
            'measured_at' => now(),
        ]);

        // DIFFUSION TEMPS RÉEL : Envoie les données au Dashboard instantanément
        broadcast(new SensorUpdated($data))->toOthers();

        return response()->json(['status' => 'success', 'id' => $data->id], 201);
    }

    // 2. Pour le Dashboard : Historique pour Chart.js au chargement de page
    public function latest()
    {
        try {
            $latest = SensorData::orderBy('measured_at', 'desc')->first();

            if (!$latest) {
                return response()->json(['message' => 'Aucune donnée'], 200);
            }

            $history = SensorData::orderBy('measured_at', 'desc')
                ->take(15)
                ->get()
                ->map(fn($item) => [
                    'measured_at' => $item->measured_at,
                    'temperature' => (float) $item->temperature,
                    'humidity' => (float) $item->humidity
                ]);

            return response()->json([
                'current' => $latest,
                'history' => $history->reverse()->values()
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    // 3. Contrôle des 4 Relais via MQTT (R1_ON, R2_OFF, etc.)
    public function controlLed(Request $request)
    {
        $validated = $request->validate([
            'relay' => 'required|integer|min:1|max:4',
            'action' => 'required|in:ON,OFF'
        ]);

        $message = "R" . $validated['relay'] . "_" . $validated['action'];

        try {
            // Envoi au broker MQTT
            MQTT::publish('esp32/01/cmd', $message);

            return response()->json([
                'status' => 'success',
                'sent' => $message
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => 'MQTT Error: ' . $e->getMessage()], 500);
        }
    }
}
