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
    Schema::create('devices', function (Blueprint $table) {
        $table->id();

        $table->string('device_id')->unique();

        $table->string('name')->nullable();

        // 🔥 Type mieux contrôlé
        $table->enum('type', ['temperature', 'humidity', 'light', 'energy', 'switch'])
              ->default('switch');

        $table->string('room')->nullable();
        $table->string('location')->nullable();

        // 🔥 meilleure précision
        $table->decimal('value', 10, 2)->nullable();

        // RGB / HEX
        $table->string('color')->nullable();

        $table->boolean('is_on')->default(false);

        $table->enum('status', ['online','offline'])->default('offline');

        $table->timestamp('last_seen')->nullable();

        $table->timestamps();

        // ⚡ performance
        $table->index('type');
        $table->index('status');
        $table->index('room');
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
