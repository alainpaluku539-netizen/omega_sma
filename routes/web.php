<?php

use Illuminate\Support\Facades\Route;
use App\Livewire\HomeDashboard;
use App\Livewire\Pages\Home;
use App\Livewire\Pages\Settings;
use App\Livewire\SensorData;
use App\Livewire\Lights;
use App\Livewire\SecuritySystem;
use App\Livewire\DeviceList;
use App\Livewire\Cameras;
use App\Livewire\Army\DocumentsInOut;

/*

|--------------------------------------------------------------------------
| OMEGA SMART HOME - WEB INTERFACE
|--------------------------------------------------------------------------
*/

// Accueil public (Landing Page)
Route::get('/', Home::class)->name('home');

// Routes protégées par Fortify (Auth + Vérification Email)
Route::middleware(['auth', 'verified'])->group(function () {

    /**
     * DASHBOARD & ANALYTICS
     */
    Route::get('/dashboard', HomeDashboard::class)->name('dashboard');
    Route::get('/analytics', SensorData::class)->name('analytics');
    Route::get('/sensors-data', SensorData::class)->name('sensors');

    /**
     * GESTION DES ÉQUIPEMENTS
     */
    Route::get('/devices', DeviceList::class)->name('devices');
    Route::get('/smart-lighting', Lights::class)->name('lights');

    /**
     * SÉCURITÉ & SURVEILLANCE
     */
    Route::get('/security', SecuritySystem::class)->name('security');
    Route::get('/cameras', Cameras::class)->name('cameras');

    /**
     * CONFIGURATION
     */
    Route::get('/settings', Settings::class)->name('settings');

    /**
     * SYSTEME MILITAIRE (Accès restreint via Gate)
     */
    Route::get('/army/documents', DocumentsInOut::class)
        ->name('army.documents')
        ->middleware('can:access-military');

});
