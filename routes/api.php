<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Broadcast;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\DeviceController;
use App\Events\SensorUpdated;
use App\Models\SensorData;
use Illuminate\Support\Facades\Log;

/*
|--------------------------------------------------------------------------
| OMEGA IOT API - PRO 2026
|--------------------------------------------------------------------------
*/

// ==========================================================
// AUTH (SANCTUM)
// ==========================================================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ==========================================================
// SENSORS API
// ==========================================================
Route::prefix('sensors')->group(function () {

    // save manual sensor (optional)
    Route::post('/update', [SensorController::class, 'store']);

    // latest data for dashboard
    Route::get('/latest', [SensorController::class, 'latest']);
});


// ==========================================================
// DEVICES MANAGEMENT
// ==========================================================
Route::prefix('devices')->group(function () {

    Route::get('/', [DeviceController::class, 'index']);
    Route::post('/update', [DeviceController::class, 'update']);
    Route::post('/toggle', [DeviceController::class, 'toggle']);
    Route::post('/offline', [DeviceController::class, 'offline']);
});


// ==========================================================
// CONTROL (RELAY / ESP32 COMMANDS)
// ==========================================================
Route::prefix('controls')->group(function () {

    Route::post('/relay', function (Request $request) {

        $data = $request->validate([
            'relay' => 'nullable|integer',
            'state' => 'nullable|string',
            'cmd'   => 'nullable|string',
        ]);

        return response()->json([
            'status' => 'ok',
            'message' => 'Command received',
            'data' => $data
        ]);
    });
});


// ==========================================================
// REALTIME TRIGGER (FLASK → LARAVEL → REVERB)
// ==========================================================
Route::get('/trigger-sync', function () {

    try {

        $data = SensorData::latest('id')->first();

        if (!$data) {
            return response()->json([
                'status' => 'empty',
                'message' => 'No sensor data'
            ]);
        }

        // LOG DEBUG
        Log::info("IoT Broadcast", [
            'device' => $data->device_id,
            'temp'   => $data->temperature,
            'hum'    => $data->humidity,
            'rssi'   => $data->rssi
        ]);

        // BROADCAST EVENT (REALTIME DASHBOARD)
        broadcast(new SensorUpdated($data));

        return response()->json([
            'status' => 'success',
            'message' => 'Event broadcasted',
            'data' => $data
        ]);
    } catch (\Exception $e) {

        Log::error("Trigger Sync Error", [
            'error' => $e->getMessage()
        ]);

        return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 500);
    }
});


// ==========================================================
// MQTT / FLASK INTERNAL HOOK (OPTIONAL DEBUG)
// ==========================================================
Route::get('/status', function () {

    return response()->json([
        'system' => 'OMEGA IOT PLATFORM',
        'status' => 'running',
        'time' => now(),
        'mqtt' => 'enabled',
        'reverb' => 'enabled'
    ]);
});
