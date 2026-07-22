<?php

namespace Database\Factories;

use App\Models\Media;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Media>
 */
class MediaFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'disk' => 'public',

            'path' => 'media/test.jpg',

            'original_name' => 'test.jpg',

            'mime_type' => 'image/jpeg',

            'extension' => 'jpg',

            'size' => fake()->numberBetween(10000, 500000),

            'uploaded_by' => User::factory(),
        ];
    }
}
