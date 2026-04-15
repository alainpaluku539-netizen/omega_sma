<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\SensorController;
use App\Http\Controllers\Api\DeviceController;
use App\Events\SensorUpdated;
use App\Models\SensorData;

/*

|--------------------------------------------------------------------------
| API IoT PLATFORM - OMEGA CORE
|--------------------------------------------------------------------------
*/

// ==========================================
// AUTHENTICATION (Sanctum)
// ==========================================
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// ==========================================
// SENSORS (Données télémétriques de l'ESP32)
// ==========================================
Route::prefix('sensors')->group(function () {
    // Enregistrement manuel (si utilisé sans Python)
    Route::post('/update', [SensorController::class, 'store']);
    
    // Récupération pour Chart.js / Frontend
    Route::get('/latest', [SensorController::class, 'latest']);
});


// ==========================================
// DEVICES (Gestion des états et du parc)
// ==========================================
Route::prefix('devices')->group(function () {
    Route::get('/', [DeviceController::class, 'index']);
    Route::post('/update', [DeviceController::class, 'update']);
    Route::post('/toggle', [DeviceController::class, 'toggle']);
    Route::post('/offline', [DeviceController::class, 'offline']);
});


// ==========================================
// CONTROLS (MQTT Actions via Laravel)
// ==========================================
Route::prefix('controls')->group(function () {
    // Route appelée par le Dashboard pour piloter l'ESP32
    Route::post('/relay', [SensorController::class, 'controlLed']);
});


// ==========================================
// SYSTEM (Bridge Python -> Reverb)
// ==========================================
/**
 * Cette route est CRUCIALE : elle est appelée par ton script Python (app.py) 
 * dès qu'une donnée du DHT11 est insérée en base de données.
 * Elle déclenche l'événement qui fait bouger tes graphiques en temps réel.
 */
Route::get('/trigger-sync', function () {
    try {
        $data = SensorData::latest('measured_at')->first();
        
        if ($data) {
            // Diffuse l'événement via WebSockets (Laravel Reverb)
            broadcast(new SensorUpdated($data))->toOthers();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Broadcast sent to dashboard',
                'timestamp' => now()
            ], 200);
        }
        
        return response()->json(['status' => 'empty', 'message' => 'No data found'], 404);
        
    } catch (\Exception $e) {
        return response()->json(['status' => 'error', 'message' => $e->getMessage()], 500);
    }
});
