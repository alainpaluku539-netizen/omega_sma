<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Document>
 */
class DocumentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $direction = $this->faker->randomElement(['IN', 'OUT']);
        $year = 2026;
        $number = $this->faker->unique()->numberBetween(1, 500);

        return [
            'reference' => $direction . "-" . $year . "-" . str_pad($number, 3, '0', STR_PAD_LEFT),
            'entry_number' => $number,
            'entry_year' => $year,
            'title' => $this->faker->sentence(4),
            'description' => $this->faker->paragraph(),
            'doc_type' => $this->faker->randomElement(['NOTE', 'LETTRE', 'TELEGRAMME', 'SITREP', 'COMMUNIQUE']),
            'direction' => $direction,
            'origin_destination' => $this->faker->company() . " - " . $this->faker->city(),
            'mention' => $this->faker->randomElement(['ORDINAIRE', 'URGENT', 'TRÈS URGENT', 'POUR ATTRIBUTION']),
            'classification' => $this->faker->randomElement(['UNCLASSIFIED', 'RESTRICTED', 'CONFIDENTIAL', 'SECRET']),
            'leader_decision' => $this->faker->randomElement(['ACCORD', 'REFUS', 'SOUMIS', 'CLASSE', null]),
            'leader_notes' => $this->faker->optional(0.7)->sentence(),
            'user_id' => User::where('role', 'admin')->first()?->id ?? User::factory(),
            'action_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
        ];
    }

    /**
     * État spécifique pour les documents Urgents
     */
    public function urgent(): static
    {
        return $this->state(fn (array $attributes) => [
            'mention' => 'URGENT',
            'classification' => 'SECRET',
        ]);
    }
}
