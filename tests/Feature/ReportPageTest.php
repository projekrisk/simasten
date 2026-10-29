<?php

namespace Tests\Feature;

use App\Filament\Pages\ReportPage;
use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class ReportPageTest extends TestCase
{
    use RefreshDatabase;

    public function test_report_page_is_accessible_by_authenticated_user(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->get('/admin/laporan');

        $response->assertOk();
    }

    public function test_report_page_defaults_to_current_year(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ReportPage::class)
            ->assertSet('selectedYear', (int) date('Y'));
    }

    public function test_report_page_can_download_ranking_pdf(): void
    {
        $user = User::factory()->create();

        Setting::current();

        $employee = Employee::create([
            'nip' => '198501152010011002',
            'nama' => 'Ahmad Suhendar, S.Pd',
            'jabatan' => 'Guru Ahli Pertama',
            'status_kepegawaian' => 'PNS',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => (int) date('Y'),
            'nomor_surat' => '045',
            'status' => 'final',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => now()->toDateString(),
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $component = Livewire::actingAs($user)->test(ReportPage::class);

        $downloadResponse = $component->call('downloadRankingPdf');

        $downloadResponse->assertFileDownloaded('Laporan Peringkat Pegawai Tahun '.date('Y').'.pdf');
    }

    public function test_report_page_updates_when_year_changes(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(ReportPage::class)
            ->set('selectedYear', 2025)
            ->assertSet('selectedYear', 2025);
    }

    public function test_ranking_pdf_view_matches_surat_format_and_arial_font(): void
    {
        $setting = Setting::current();
        $setting->update([
            'gunakan_tte' => true,
            'teks_tte' => 'Dokumen ini telah ditandatangani secara elektronik.',
        ]);

        $html = view('pdf.peringkat-pegawai', [
            'year' => 2026,
            'employees' => [
                [
                    'id' => 1,
                    'nama' => 'Budi Santoso, S.Pd.',
                    'nip' => '198501152010011002',
                    'jabatan' => 'Guru Matematika',
                    'tm_count' => 3,
                    'hd_count' => 1,
                    'corrections_count' => 4,
                ],
            ],
            'setting' => $setting->fresh(),
        ])->render();

        $this->assertStringContainsString('font-family: Arial, Helvetica, sans-serif;', $html);
        $this->assertStringNotContainsString('monospace', $html);
        $this->assertStringContainsString('kop-separator', $html);
        $this->assertStringContainsString('kop-line-thin', $html);
        $this->assertStringContainsString('kop-line-thick', $html);
        $this->assertStringContainsString('TOTAL KESELURUHAN:', $html);
        $this->assertStringContainsString('Dokumen ini telah ditandatangani secara elektronik.', $html);
    }
}
