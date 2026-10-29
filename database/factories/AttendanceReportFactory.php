<?php

namespace Database\Factories;

use App\Models\AttendanceReport;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceReport>
 */
class AttendanceReportFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $bulan = fake()->numberBetween(1, 12);
        $tahun = (int) date('Y');
        $nomor = sprintf('%03d', fake()->numberBetween(1, 100));

        return [
            'bulan' => $bulan,
            'tahun' => $tahun,
            'nomor_surat' => $nomor,
            'nomor_surat_lengkap' => "800/{$nomor}/SMA.1-MLP/{$tahun}",
            'tanggal_surat' => fake()->dateTimeThisYear(),
            'status' => fake()->randomElement(['draf', 'final']),
            'pdf_file_path' => null,
        ];
    }
}
