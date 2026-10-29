<?php

namespace Database\Factories;

use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<AttendanceCorrection>
 */
class AttendanceCorrectionFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $status = fake()->randomElement(['TM', 'HD']);

        return [
            'perbaikan_id' => AttendanceReport::factory(),
            'pegawai_id' => Employee::factory(),
            'tanggal' => fake()->date(),
            'status_presensi' => $status,
            'jam_datang_asli' => $status === 'HD' ? '07:12:00' : null,
            'jam_datang_ubah' => '07:05:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
            'foto_datang' => null,
            'foto_pulang' => null,
        ];
    }
}
