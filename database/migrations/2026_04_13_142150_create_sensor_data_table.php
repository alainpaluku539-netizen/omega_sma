<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
        public function up(): void
        {
                Schema::create('sensor_data', function (Blueprint $table) {

                        $table->id();

                        // =========================
                        // DEVICE INFO
                        // =========================
                        $table->string('device_id')->index();

                        // =========================
                        // SENSOR DATA
                        // =========================
                        $table->float('temperature')->nullable();
                        $table->float('humidity')->nullable();
                        $table->float('pressure')->nullable();

                        // =========================
                        // NETWORK / SIGNAL
                        // =========================
                        $table->integer('rssi')->nullable();

                        // =========================
                        // DEVICE STATUS
                        // =========================
                        $table->enum('status', ['online', 'offline', 'error'])
                                    ->default('online')
                                    ->index();

                        // =========================
                        // SENSOR TYPE
                        // =========================
                        $table->string('sensor_type')->nullable()->index();

                        // =========================
                        // TIME HANDLING (IMPORTANT IoT)
                        // =========================
                        $table->timestamp('measured_at')
                                    ->nullable()
                                    ->index();

                        $table->timestamps();

                        // =========================
                        // PERFORMANCE INDEXES
                        // =========================
                        $table->index(['device_id', 'measured_at']);
                });
                


        }

        public function down(): void
        {
                Schema::dropIfExists('sensor_data');
        }
};