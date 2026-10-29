<?php

namespace App\Models;

use Database\Factories\AttendanceCorrectionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AttendanceCorrection extends Model
{
    /** @use HasFactory<AttendanceCorrectionFactory> */
    use HasFactory;

    protected $table = 'perbaikan_detail';

    protected $fillable = [
        'perbaikan_id',
        'pegawai_id',
        'tanggal',
        'status_presensi',
        'jam_datang_asli',
        'jam_datang_ubah',
        'jam_pulang_asli',
        'jam_pulang_ubah',
        'foto_datang',
        'foto_pulang',
    ];

    protected function casts(): array
    {
        return [
            'tanggal' => 'date',
        ];
    }

    public function report(): BelongsTo
    {
        return $this->belongsTo(AttendanceReport::class, 'perbaikan_id');
    }

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class, 'pegawai_id');
    }
}
