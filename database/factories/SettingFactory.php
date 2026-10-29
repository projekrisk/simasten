<?php

namespace Database\Factories;

use App\Models\Setting;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Setting>
 */
class SettingFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'nama_kepala_sekolah' => 'Drs. H. Ahmad Sudrajat, M.Pd.',
            'nip_kepala_sekolah' => '196805121994031005',
            'spesimen_ttd' => null,
            'spesimen_stempel' => null,
            'gunakan_spesimen' => false,
        ];
    }
}
