<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $medicineTypes = [
            'Panadol',
            'Aspirin',
            'Ibuprofen',
            'Amoxicillin',
            'Vitamin',
            'Antibiotic',
            'Painkiller',
            'Cough Syrup',
            'Tablet',
            'Capsule',
            'Cream',
            'Ointment',
            'Drops',
            'Injection',
            'Syrup'
        ];
        $strengths = ['50mg', '100mg', '200mg', '500mg', '1g', '250ml', '500ml'];

        $medicineType = fake()->randomElement($medicineTypes);
        $strength = fake()->randomElement($strengths);
        return [
            'title' => "{$medicineType} {$strength}",
            'description' => fake()->sentence(15),
            'image' =>null,
            'price' => fake()->randomFloat(2, 5, 500),
            'quantity' => fake()->numberBetween(0 , 1000),
        ];
    }
}
