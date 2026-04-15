<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Device;

class DeviceController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LISTE DES DEVICES (Dashboard Livewire)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        return response()->json(
            Device::latest()->get()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE / UPDATE DEVICE (Flask / ESP32 / MQTT Bridge)
    |--------------------------------------------------------------------------
    */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'device_id' => 'required|string',
            'name'      => 'nullable|string',
            'type'      => 'nullable|string',
            'room'      => 'nullable|string',
            'location'  => 'nullable|string',
            'value'     => 'nullable|numeric',
            'is_on'     => 'nullable|boolean',
            'status'    => 'nullable|in:online,offline'
        ]);

        $device = Device::updateOrCreate(
            ['device_id' => $validated['device_id']],
            [
                'name'      => $validated['name'] ?? $validated['device_id'],
                'type'      => $validated['type'] ?? 'sensor',
                'room'      => $validated['room'] ?? null,
                'location'  => $validated['location'] ?? null,
                'value'     => $validated['value'] ?? null,
                'is_on'     => $validated['is_on'] ?? false,
                'status'    => $validated['status'] ?? 'online',
                'last_seen' => now(),
            ]
        );

        return response()->json([
            'status' => 'success',
            'device' => $device
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | MARK DEVICE OFFLINE (optionnel mais PRO)
    |--------------------------------------------------------------------------
    */
    public function offline(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $device = Device::where('device_id', $request->device_id)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $device->update([
            'status' => 'offline',
            'last_seen' => now()
        ]);

        return response()->json([
            'status' => 'offline_set',
            'device' => $device
        ]);
    }

    /*
    |--------------------------------------------------------------------------
    | TOGGLE DEVICE (utile pour Lights UI)
    |--------------------------------------------------------------------------
    */
    public function toggle(Request $request)
    {
        $request->validate([
            'device_id' => 'required|string'
        ]);

        $device = Device::where('device_id', $request->device_id)->first();

        if (!$device) {
            return response()->json(['error' => 'Device not found'], 404);
        }

        $device->update([
            'is_on' => !$device->is_on,
            'last_seen' => now(),
            'status' => 'online'
        ]);

        return response()->json([
            'status' => 'toggled',
            'device' => $device
        ]);
    }
}