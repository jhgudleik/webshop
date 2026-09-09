<?php

namespace Database\Factories;

use App\Models\Categogy;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Categogy>
 */
class CategogyFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $active = rand(0, 1); // Генерация случайного значения true или false
        $title = 'Тестовая категория ' . rand(100, 999); // Генерация случайного заголовка категории
        $slug = Str::slug($title); // Генерация случайного слага

        return [
            'slug' => $slug,
            'title' => $title,
            'active' => $active
        ];
    }
}
