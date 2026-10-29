<?php

namespace App\Models;

use Database\Factories\SettingFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    /** @use HasFactory<SettingFactory> */
    use HasFactory;

    protected $table = 'pengaturan';

    protected $fillable = [
        'nama_kepala_sekolah',
        'nip_kepala_sekolah',
        'spesimen_ttd',
        'spesimen_stempel',
        'gunakan_spesimen',
        'gunakan_tte',
        'teks_tte',
    ];

    protected function casts(): array
    {
        return [
            'gunakan_spesimen' => 'boolean',
            'gunakan_tte' => 'boolean',
        ];
    }

    public static function current(): self
    {
        return static::firstOrCreate([], [
            'nama_kepala_sekolah' => 'Drs. H. Ahmad Sudrajat, M.Pd.',
            'nip_kepala_sekolah' => '196805121994031005',
            'gunakan_spesimen' => false,
            'gunakan_tte' => false,
            'teks_tte' => '# $(pengirim)',
        ]);
    }
}
