<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('device_data', function (Blueprint $table) {
            $table->id();

            $table->string('device_id')->index();

            $table->string('type'); // temp, humidity, light, security

            $table->float('value'); // valeur mesurée

            $table->string('unit')->nullable(); // °C, %, lux

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_data');
    }
};
