<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Livewire\HomeDashboard;
use App\Livewire\Devices;
use App\Livewire\SensorData;
use App\Livewire\Lights;

/*
|--------------------------------------------------------------------------
| WEB ROUTES - SMART HOME IoT
|--------------------------------------------------------------------------
*/

// ==========================
// PAGE D'ACCUEIL (LOGIN)
// ==========================



// ==========================
// PROTECTED ROUTES
// ==========================
Route::middleware(['auth', 'verified'])->group(function () {

    // --------------------------
    // DASHBOARD PRINCIPAL
    // --------------------------
    Route::get('/dashboard', HomeDashboard::class)
        ->name('dashboard');

    Route::get('/sensor-data', SensorData::class)
        ->name('sensors');

    // --------------------------
    // DEVICES (IoT Management)
    // --------------------------
    Route::get('/devices', Devices::class)
        ->name('devices');

    // --------------------------
    // LIGHTS / RELAIS CONTROL
    // --------------------------
    Route::get('/lights', Lights::class)
        ->name('lights');

    // --------------------------
    // SECURITY PAGE
    // --------------------------
    //Route::get('/security', function () {
    //    return view('security.index');
    //})->name('security');

    // --------------------------
    // ANALYTICS PAGE
    // --------------------------
    //Route::get('/analytics', function () {
    //    return view('analytics.index');
    //})->name('analytics');

    Route::livewire('/', HomeDashboard::class)->name('dashboard');

});