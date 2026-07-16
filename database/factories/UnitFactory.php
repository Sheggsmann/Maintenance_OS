<?php

namespace Database\Factories;

use App\Enums\UnitStatusEnum;
use App\Enums\UnitTypeEnum;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Unit>
 */
class UnitFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_id' => Property::factory(),
            'tenant_id' => null,
            'unit_number' => strtoupper(fake()->randomLetter())
                . fake()->numberBetween(101, 999),
            'floor' => fake()->numberBetween(1, 10),
            'type' => fake()->randomElement(UnitTypeEnum::cases()),
            'status' => UnitStatusEnum::VACANT,
            'bedrooms' => fake()->numberBetween(1, 5),
            'bathrooms' => fake()->numberBetween(1, 4),
            'area' => fake()->randomFloat(2, 40, 250),
            'monthly_rent' => fake()->numberBetween(50000, 500000),
            'description' => fake()->sentence(),
        ];
    }
}
