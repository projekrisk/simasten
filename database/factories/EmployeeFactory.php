<?php

namespace Database\Factories;

use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Employee>
 */
class EmployeeFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nip' => fake()->numerify('19##########00#'),
            'nama' => fake()->name(),
            'jabatan' => fake()->randomElement(['Guru Matematika', 'Guru Bahasa Indonesia', 'Guru BK', 'Staff TU', 'Wakil Kepala Sekolah', 'Guru PJOK']),
        ];
    }
}
