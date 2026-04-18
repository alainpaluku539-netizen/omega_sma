<?php

namespace Database\Seeders;

use App\Models\Document; // AJOUTE CETTE LIGNE
use Illuminate\Database\Seeder;

class DocumentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Génère 50 documents aléatoires liés à ton admin
        Document::factory()->count(50)->create();

        // Ou génère 5 documents spécifiquement Urgents
        Document::factory()->count(5)->urgent()->create();
    }
}
