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
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            
            // --- IDENTIFICATION ---
            $table->string('reference')->unique(); // Ex: IN-2024-001
            $table->integer('entry_number')->nullable(); // Numéro chronologique
            $table->year('entry_year'); // Année pour reset le compteur
            
            // --- CONTENU ---
            $table->string('title');
            $table->text('description')->nullable();
            
            // --- TYPOLOGIE (Note, Lettre, Sitrep...) ---
            $table->string('doc_type')->default('NOTE'); 
            $table->enum('direction', ['IN', 'OUT'])->default('IN');
            
            // --- EXPÉDITEUR / DESTINATAIRE ---
            $table->string('origin_destination'); 
            
            // --- MENTIONS & SÉCURITÉ ---
            $table->string('mention')->default('ORDINAIRE'); // URGENT, SECRET...
            $table->string('classification')->default('UNCLASSIFIED');
            
            // --- DÉCISION DU CHEF ---
            $table->string('leader_decision')->nullable(); // ACCORD, REFUS...
            $table->text('leader_notes')->nullable(); // Annotation manuscrite du chef
            
            // --- TRACKING ---
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Opérateur
            $table->timestamp('action_date'); // Date de réception/envoi effective
            
            $table->timestamps();
            $table->softDeletes(); // Archive de sécurité
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documents');
    }
};
