<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Pharmacy>
 */
class PharmacyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $pharmacyNames = [
            'Anshas Pharmacy',
            'HealthPlus',
            'MediCare',
            'WellCare',
            'QuickHealth',
            'Sheraton Pharmacy',
            'Community Health',
            'Family Pharmacy',
            'Belbis Pharmacy'
        ];

        return [
            'name' => fake()->randomElement($pharmacyNames) , 
            'address' => fake()->address(),
        ];
    }
}
