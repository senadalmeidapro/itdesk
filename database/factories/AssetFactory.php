<?php

namespace Database\Factories;

use App\Models\Asset;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Asset>
 */
class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition(): array
    {
        return [
            'asset_tag' => 'AST-'.fake()->unique()->numerify('#####'),
            'serial_number' => fake()->unique()->bothify('SN-????????'),
            'name' => fake()->words(3, true),
            'type' => 'laptop',
            'status' => 'in_stock',
            'manufacturer' => fake()->company(),
            'model' => fake()->bothify('Model-###'),
        ];
    }
}