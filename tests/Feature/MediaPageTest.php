<?php

namespace Tests\Feature;

use App\Filament\Pages\MediaPage;
use App\Filament\Pages\SettingPage;
use App\Filament\Resources\Employees\Pages\CreateEmployee;
use App\Filament\Resources\Employees\Pages\EditEmployee;
use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\User;
use App\Services\AttendancePdfService;
use Filament\Support\Enums\Width;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\File;
use Livewire\Livewire;
use Tests\TestCase;

class MediaPageTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        File::ensureDirectoryExists(public_path('uploads/presensi'));
        File::ensureDirectoryExists(public_path('uploads/spesimen'));
    }

    public function test_media_page_can_be_rendered(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('filament.admin.pages.media'))
            ->assertSuccessful()
            ->assertSee('Kelola Media');
    }

    public function test_media_page_lists_files_and_calculates_stats(): void
    {
        $user = User::factory()->create();

        $testFile = public_path('uploads/presensi/test_unit_selfie.jpg');
        File::put($testFile, 'test content image data');

        try {
            Livewire::actingAs($user)
                ->test(MediaPage::class)
                ->assertSee('test_unit_selfie.jpg')
                ->assertSet('category', 'all');
        } finally {
            if (File::exists($testFile)) {
                File::delete($testFile);
            }
        }
    }

    public function test_can_safely_delete_media_file_and_nullify_database_references(): void
    {
        $user = User::factory()->create();

        $employee = Employee::create([
            'nip' => '198901012015011001',
            'nama' => 'Test Guru',
            'jabatan' => 'Guru',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '101',
            'status' => 'draf',
        ]);

        $relPath = 'presensi/unit_test_foto_datang.jpg';
        $fullPath = public_path('uploads/'.$relPath);
        File::put($fullPath, 'fake image data');

        $correction = AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => now()->toDateString(),
            'status_presensi' => 'TM',
            'jam_datang_asli' => null,
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
            'foto_datang' => $relPath,
        ]);

        $this->assertFileExists($fullPath);
        $this->assertEquals($relPath, $correction->foto_datang);

        Livewire::actingAs($user)
            ->test(MediaPage::class)
            ->call('deleteMedia', $relPath)
            ->assertNotified('Media Berhasil Dihapus');

        $this->assertFileDoesNotExist($fullPath);

        $correction->refresh();
        $this->assertNull($correction->foto_datang);
    }

    public function test_deleting_spesimen_file_safely_updates_settings(): void
    {
        $user = User::factory()->create();

        $relPath = 'spesimen/unit_test_stempel.png';
        $fullPath = public_path('uploads/'.$relPath);
        File::put($fullPath, 'fake stempel png data');

        $setting = Setting::current();
        $setting->update([
            'spesimen_stempel' => $relPath,
        ]);

        $this->assertEquals($relPath, $setting->fresh()->spesimen_stempel);

        Livewire::actingAs($user)
            ->test(MediaPage::class)
            ->call('deleteMedia', $relPath);

        $this->assertFileDoesNotExist($fullPath);
        $this->assertNull($setting->fresh()->spesimen_stempel);
    }

    public function test_deleting_photo_does_not_break_pdf_generation(): void
    {
        $employee = Employee::create([
            'nip' => '198901012015011002',
            'nama' => 'Test Guru Dua',
            'jabatan' => 'Guru',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '102',
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => now()->toDateString(),
            'status_presensi' => 'TM',
            'jam_datang_asli' => null,
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
            'foto_datang' => null,
            'foto_pulang' => null,
        ]);

        $pdfService = app(AttendancePdfService::class);
        $response = $pdfService->download($report, isDraft: true);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertFalse(file_exists(public_path('uploads/pdf/perbaikan_10_2026_draf.pdf')));
    }

    public function test_pdf_files_are_never_included_in_media_page(): void
    {
        $user = User::factory()->create();

        File::ensureDirectoryExists(public_path('uploads/test_legacy'));
        $pdfPath = public_path('uploads/test_legacy/document.pdf');
        File::put($pdfPath, '%PDF-1.4 test data');

        try {
            $component = Livewire::actingAs($user)->test(MediaPage::class);
            $items = $component->instance()->mediaItems;
            $filenames = array_column($items, 'filename');

            $this->assertNotContains('document.pdf', $filenames);
            $component->assertDontSee('document.pdf');
            $component->assertDontSee('Berkas PDF Laporan');
        } finally {
            if (File::exists($pdfPath)) {
                File::delete($pdfPath);
            }
            if (File::isDirectory(public_path('uploads/test_legacy'))) {
                File::deleteDirectory(public_path('uploads/test_legacy'));
            }
        }
    }

    public function test_can_bulk_delete_media_files(): void
    {
        $user = User::factory()->create();

        $file1 = 'presensi/unit_bulk_1.jpg';
        $file2 = 'presensi/unit_bulk_2.jpg';

        File::put(public_path('uploads/'.$file1), 'data 1');
        File::put(public_path('uploads/'.$file2), 'data 2');

        $this->assertFileExists(public_path('uploads/'.$file1));
        $this->assertFileExists(public_path('uploads/'.$file2));

        Livewire::actingAs($user)
            ->test(MediaPage::class)
            ->set('selectedFiles', [$file1, $file2])
            ->call('deleteSelected')
            ->assertNotified('Penghapusan Berhasil');

        $this->assertFileDoesNotExist(public_path('uploads/'.$file1));
        $this->assertFileDoesNotExist(public_path('uploads/'.$file2));
    }

    public function test_filament_delete_media_action_works(): void
    {
        $user = User::factory()->create();

        $relPath = 'presensi/action_test_selfie.jpg';
        $fullPath = public_path('uploads/'.$relPath);
        File::put($fullPath, 'action test data');

        $this->assertFileExists($fullPath);

        Livewire::actingAs($user)
            ->test(MediaPage::class)
            ->callAction('deleteMedia', arguments: ['path' => $relPath])
            ->assertNotified('Media Berhasil Dihapus');

        $this->assertFileDoesNotExist($fullPath);
    }

    public function test_filament_delete_selected_action_works(): void
    {
        $user = User::factory()->create();

        $file1 = 'presensi/action_bulk_1.jpg';
        $file2 = 'presensi/action_bulk_2.jpg';

        File::put(public_path('uploads/'.$file1), 'action bulk 1');
        File::put(public_path('uploads/'.$file2), 'action bulk 2');

        $this->assertFileExists(public_path('uploads/'.$file1));
        $this->assertFileExists(public_path('uploads/'.$file2));

        Livewire::actingAs($user)
            ->test(MediaPage::class)
            ->set('selectedFiles', [$file1, $file2])
            ->callAction('deleteSelected')
            ->assertNotified('Penghapusan Berhasil');

        $this->assertFileDoesNotExist(public_path('uploads/'.$file1));
        $this->assertFileDoesNotExist(public_path('uploads/'.$file2));
    }

    public function test_dashboard_does_not_contain_top_employees_widget(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user)
            ->get(route('filament.admin.pages.dasbor'))
            ->assertSuccessful()
            ->assertDontSee('Daftar 5 Pegawai Paling Sering Mengajukan Perbaikan Presensi');
    }

    public function test_employee_pages_are_configured_with_full_width(): void
    {
        $createPage = new CreateEmployee;
        $editPage = new EditEmployee;

        $this->assertEquals(Width::Full, $createPage->getMaxContentWidth());
        $this->assertEquals(Width::Full, $editPage->getMaxContentWidth());
    }

    public function test_setting_page_renders_with_tabs_in_card_and_saves_successfully(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(SettingPage::class)
            ->assertSee('Simpan Pengaturan')
            ->assertSee('Kepala Sekolah')
            ->assertSee('Spesimen')
            ->assertDontSee('Pratinjau Pengesahan PDF')
            ->fillForm([
                'nama_kepala_sekolah' => 'Dr. H. Ahmad Fauzi, M.Pd.',
                'nip_kepala_sekolah' => '197001011995031001',
                'gunakan_spesimen' => true,
            ])
            ->call('save')
            ->assertHasNoFormErrors()
            ->assertNotified('Pengaturan Berhasil Disimpan');

        $setting = Setting::current();
        $this->assertEquals('Dr. H. Ahmad Fauzi, M.Pd.', $setting->nama_kepala_sekolah);
        $this->assertEquals('197001011995031001', $setting->nip_kepala_sekolah);
        $this->assertTrue($setting->gunakan_spesimen);
    }
}
