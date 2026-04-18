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
    Schema::create('device_identities', function (Blueprint $table) {
        $table->id();
        $table->string('device_id')->unique(); // ID unique matériel (ex: ESP32-A1B2)
        $table->string('name');                // Nom amical (ex: Salon Température)
        $table->string('type');                // Type (sensor, light, switch)
        $table->string('room')->nullable();    // Pièce
        $table->boolean('is_active')->default(true);
        $table->json('metadata')->nullable();  // Pour stocker des infos spécifiques
        $table->timestamps();
    });
}


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('device_identities');
    }
};
