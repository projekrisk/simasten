<?php

namespace Tests\Feature;

use App\Filament\Pages\SettingPage;
use App\Filament\Resources\AttendanceCorrections\Pages\CreateAttendanceCorrection;
use App\Filament\Resources\AttendanceReports\AttendanceReportResource;
use App\Filament\Resources\AttendanceReports\Pages\EditAttendanceReport;
use App\Filament\Resources\AttendanceReports\Pages\ListAttendanceReports;
use App\Filament\Resources\AttendanceReports\RelationManagers\CorrectionsRelationManager;
use App\Imports\EmployeeImport;
use App\Models\AttendanceCorrection;
use App\Models\AttendanceReport;
use App\Models\Employee;
use App\Models\Setting;
use App\Models\User;
use App\Services\AttendancePdfService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use setasign\Fpdi\PdfParser\StreamReader;
use setasign\Fpdi\Tcpdf\Fpdi;
use Tests\TestCase;

class AttendanceReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_nomor_surat_lengkap_is_formatted_correctly(): void
    {
        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '045',
            'status' => 'draf',
        ]);

        $this->assertEquals('800/045/SMA.1-MLP/2026', $report->nomor_surat_lengkap);
    }

    public function test_can_generate_pdf_for_attendance_report(): void
    {
        $employee = Employee::firstOrCreate(
            ['nip' => '198501152010011002'],
            ['nama' => 'Budi Santoso, S.Pd.', 'jabatan' => 'Guru Matematika']
        );

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '099',
            'tanggal_surat' => now()->toDateString(),
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
        ]);

        $service = new AttendancePdfService;
        $response = $service->download($report, false);

        $this->assertEquals(200, $response->getStatusCode());
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertEquals('final', $report->fresh()->status);
        $this->assertFalse(file_exists(public_path('uploads/pdf/perbaikan_10_2026_final.pdf')));

        $fpdi = new Fpdi;
        $pageCount = $fpdi->setSourceFile(StreamReader::createByString($response->getContent()));
        $this->assertEquals(2, $pageCount);

        $page1 = $fpdi->getTemplateSize($fpdi->importPage(1));
        $this->assertTrue($page1['height'] > $page1['width'], 'Page 1 must be portrait');

        $page2 = $fpdi->getTemplateSize($fpdi->importPage(2));
        $this->assertTrue($page2['width'] > $page2['height'], 'Page 2 must be landscape');
    }

    public function test_attendance_pdf_chunks_corrections_for_4x6_photo_layout(): void
    {
        $employee = Employee::firstOrCreate(
            ['nip' => '198501152010011002'],
            ['nama' => 'Budi Santoso, S.Pd.', 'jabatan' => 'Guru Matematika']
        );

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '100',
            'tanggal_surat' => now()->toDateString(),
            'status' => 'draf',
        ]);

        for ($i = 0; $i < 5; $i++) {
            AttendanceCorrection::create([
                'perbaikan_id' => $report->id,
                'pegawai_id' => $employee->id,
                'tanggal' => now()->toDateString(),
                'status_presensi' => 'TM',
                'jam_datang_asli' => null,
                'jam_datang_ubah' => '07:00:00',
                'jam_pulang_asli' => null,
                'jam_pulang_ubah' => '15:30:00',
            ]);
        }

        $service = new AttendancePdfService;
        $response = $service->download($report, false);

        $fpdi = new Fpdi;
        $pageCount = $fpdi->setSourceFile(StreamReader::createByString($response->getContent()));
        $this->assertEquals(3, $pageCount);

        $page1 = $fpdi->getTemplateSize($fpdi->importPage(1));
        $this->assertTrue($page1['height'] > $page1['width'], 'Page 1 must be portrait');

        $page2 = $fpdi->getTemplateSize($fpdi->importPage(2));
        $this->assertTrue($page2['width'] > $page2['height'], 'Page 2 must be landscape');

        $page3 = $fpdi->getTemplateSize($fpdi->importPage(3));
        $this->assertTrue($page3['width'] > $page3['height'], 'Page 3 must be landscape');
    }

    public function test_attendance_pdf_with_four_corrections_places_signature_on_next_page(): void
    {
        $employee = Employee::firstOrCreate(
            ['nip' => '198501152010011002'],
            ['nama' => 'Budi Santoso, S.Pd.', 'jabatan' => 'Guru Matematika']
        );

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '100b',
            'tanggal_surat' => now()->toDateString(),
            'status' => 'draf',
        ]);

        for ($i = 0; $i < 4; $i++) {
            AttendanceCorrection::create([
                'perbaikan_id' => $report->id,
                'pegawai_id' => $employee->id,
                'tanggal' => now()->toDateString(),
                'status_presensi' => 'TM',
                'jam_datang_asli' => null,
                'jam_datang_ubah' => '07:00:00',
                'jam_pulang_asli' => null,
                'jam_pulang_ubah' => '15:30:00',
            ]);
        }

        $lampiranPdf = Pdf::loadView('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $fpdiLampiran = new Fpdi;
        $lampiranPages = $fpdiLampiran->setSourceFile(StreamReader::createByString($lampiranPdf->output()));
        $this->assertEquals(2, $lampiranPages, "4 tall rows with full signature should place signature on page 2 of lampiran, but got {$lampiranPages}");

        $renderedView = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->render();
        $this->assertStringContainsString('Lembar 1 dari 2', $renderedView);
        $this->assertStringContainsString('Lembar 2 dari 2', $renderedView);

        $service = new AttendancePdfService;
        $response = $service->download($report, false);

        $fpdiFull = new Fpdi;
        $fullPages = $fpdiFull->setSourceFile(StreamReader::createByString($response->getContent()));
        $this->assertEquals(3, $fullPages, "Full PDF should be 3 pages (1 surat + 2 lampiran), but got {$fullPages}");
    }

    public function test_attendance_pdf_with_two_corrections_fits_on_single_lampiran_page(): void
    {
        $employee = Employee::firstOrCreate(
            ['nip' => '198501152010011002'],
            ['nama' => 'Budi Santoso, S.Pd.', 'jabatan' => 'Guru Matematika']
        );

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '101',
            'tanggal_surat' => now()->toDateString(),
            'status' => 'draf',
        ]);

        for ($i = 0; $i < 2; $i++) {
            AttendanceCorrection::create([
                'perbaikan_id' => $report->id,
                'pegawai_id' => $employee->id,
                'tanggal' => now()->toDateString(),
                'status_presensi' => 'TM',
                'jam_datang_asli' => null,
                'jam_datang_ubah' => '07:00:00',
                'jam_pulang_asli' => null,
                'jam_pulang_ubah' => '15:30:00',
            ]);
        }

        $lampiranPdf = Pdf::loadView('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $fpdiLampiran = new Fpdi;
        $lampiranPages = $fpdiLampiran->setSourceFile(StreamReader::createByString($lampiranPdf->output()));
        $this->assertEquals(1, $lampiranPages, "Lampiran with 2 corrections should be 1 page, but got {$lampiranPages}");

        $renderedView = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->render();
        $this->assertStringContainsString('Lembar 1 dari 1', $renderedView);

        $service = new AttendancePdfService;
        $response = $service->download($report, false);

        $fpdiFull = new Fpdi;
        $fullPages = $fpdiFull->setSourceFile(StreamReader::createByString($response->getContent()));
        $this->assertEquals(2, $fullPages, "Full PDF should be 2 pages (1 surat + 1 lampiran), but got {$fullPages}");
    }

    public function test_attendance_pdf_orders_corrections_by_date_then_name(): void
    {
        $zulfa = Employee::create([
            'nip' => '199001012015011099',
            'nama' => 'Zulfa Farida, S.Pd.',
            'jabatan' => 'Guru Biologi',
        ]);

        $ahmad = Employee::create([
            'nip' => '199102022016021088',
            'nama' => 'Ahmad Dahlan, S.Pd.',
            'jabatan' => 'Guru Kimia',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '102',
            'tanggal_surat' => now()->toDateString(),
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $zulfa->id,
            'tanggal' => '2026-10-15',
            'status_presensi' => 'TM',
            'jam_datang_asli' => null,
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $zulfa->id,
            'tanggal' => '2026-10-10',
            'status_presensi' => 'TM',
            'jam_datang_asli' => null,
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $ahmad->id,
            'tanggal' => '2026-10-10',
            'status_presensi' => 'TM',
            'jam_datang_asli' => null,
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $rendered = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->render();

        $posAhmad = strpos($rendered, 'Ahmad Dahlan');
        $posZulfa1 = strpos($rendered, 'Zulfa Farida');
        $this->assertNotFalse($posAhmad);
        $this->assertNotFalse($posZulfa1);
        $this->assertTrue($posAhmad < $posZulfa1, 'Ahmad must appear before Zulfa for the same date 10/10/2026');

        $posTgl10 = strpos($rendered, '10/10/2026');
        $posTgl15 = strpos($rendered, '15/10/2026');
        $this->assertNotFalse($posTgl10);
        $this->assertNotFalse($posTgl15);
        $this->assertTrue($posTgl10 < $posTgl15, 'Date 10/10/2026 must appear before 15/10/2026');
    }

    public function test_setting_page_can_save_tte_configuration(): void
    {
        $user = User::factory()->create();

        Livewire::actingAs($user)
            ->test(SettingPage::class)
            ->set('data.nama_kepala_sekolah', 'Drs. H. Ahmad Sudrajat, M.Pd.')
            ->set('data.nip_kepala_sekolah', '196805121994031005')
            ->set('data.gunakan_tte', true)
            ->set('data.teks_tte', '# $(pengirim)')
            ->call('save')
            ->assertHasNoErrors();

        $setting = Setting::current();
        $this->assertTrue($setting->gunakan_tte);
        $this->assertEquals('# $(pengirim)', $setting->teks_tte);
    }

    public function test_attendance_pdf_renders_tte_tag_when_tte_is_enabled(): void
    {
        $setting = Setting::current();
        $setting->update([
            'gunakan_tte' => true,
            'teks_tte' => '# $(pengirim)',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '103',
            'tanggal_surat' => now()->toDateString(),
            'status' => 'draf',
        ]);

        $renderedSurat = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => $setting,
            'isDraft' => false,
            'section' => 'surat',
        ])->render();

        $this->assertStringContainsString('ttd-tte-space', $renderedSurat);
        $this->assertStringContainsString('# $(pengirim)', $renderedSurat);
        $this->assertStringContainsString('padding-top: 54px;', $renderedSurat);
        $this->assertStringContainsString('text-align: left;', $renderedSurat);

        $renderedLampiran = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => $setting,
            'isDraft' => false,
            'section' => 'lampiran',
        ])->render();

        $this->assertStringContainsString('ttd-tte-space', $renderedLampiran);
        $this->assertStringContainsString('# $(pengirim)', $renderedLampiran);
        $this->assertStringContainsString('padding-top: 54px;', $renderedLampiran);
        $this->assertStringContainsString('background: none;', $renderedLampiran);
        $this->assertStringContainsString('border: none;', $renderedLampiran);

        $employee = Employee::firstOrCreate(
            ['nip' => '198501152010011002'],
            ['nama' => 'Budi Santoso, S.Pd.', 'jabatan' => 'Guru Matematika']
        );

        for ($i = 0; $i < 4; $i++) {
            AttendanceCorrection::create([
                'perbaikan_id' => $report->id,
                'pegawai_id' => $employee->id,
                'tanggal' => now()->toDateString(),
                'status_presensi' => 'TM',
                'jam_datang_asli' => null,
                'jam_datang_ubah' => '07:00:00',
                'jam_pulang_asli' => null,
                'jam_pulang_ubah' => '15:30:00',
            ]);
        }

        $report->unsetRelation('corrections');

        $service = new AttendancePdfService;
        $response = $service->download($report, false);

        $fpdi = new Fpdi;
        $pageCount = $fpdi->setSourceFile(StreamReader::createByString($response->getContent()));
        $this->assertEquals(3, $pageCount, '4 tall rows with full-height signature should place signature on the next page (3 pages total)');
    }

    public function test_attendance_pdf_view_renders_correct_format(): void
    {
        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '045',
            'tanggal_surat' => '2026-10-28',
            'status' => 'draf',
        ]);

        $rendered = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
        ])->render();

        $this->assertStringContainsString('PEMERINTAH PROVINSI BANTEN', $rendered);
        $this->assertStringContainsString('DINAS PENDIDIKAN DAN KEBUDAYAAN', $rendered);
        $this->assertStringContainsString('SMAN 1 MALINGPING', $rendered);
        $this->assertStringContainsString('NPSN: 20601875 - AKREDITASI: A (96)', $rendered);
        $this->assertStringContainsString('Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391', $rendered);
        $this->assertStringContainsString('Website: https://sman1malingping.sch.id – Email: info@sman1malingping.sch.id', $rendered);
        $this->assertStringContainsString('Kepala Cabang Dinas Pendidikan dan Kebudayaan', $rendered);
        $this->assertStringContainsString('Wilayah Kabupaten Lebak', $rendered);
        $this->assertStringContainsString('Keterangan:', $rendered);
        $this->assertStringContainsString('badge-tm', $rendered);
        $this->assertStringContainsString('badge-hd', $rendered);
        $this->assertStringContainsString('font-family: Arial', $rendered);
        $this->assertStringContainsString('uploads/logo/kiri.png', $rendered);
        $this->assertStringContainsString('uploads/logo/kanan.png', $rendered);
        $this->assertStringContainsString('evidence-photo', $rendered);
        $this->assertStringContainsString('photo-cell', $rendered);
        $this->assertStringContainsString('Bukti Foto<br>Datang', $rendered);
        $this->assertStringContainsString('employee-cell', $rendered);
        $this->assertStringContainsString('font-size: 11pt;', $rendered);
        $this->assertStringContainsString('margin: 2cm;', $rendered);
        $this->assertStringContainsString('lampiran-meta-table', $rendered);
        $this->assertStringContainsString('Nomor Surat', $rendered);
        $this->assertStringContainsString('Periode', $rendered);
        $this->assertStringContainsString('Halaman', $rendered);

        $renderedLampiran = view('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => Setting::current(),
            'isDraft' => false,
            'section' => 'lampiran',
        ])->render();

        $this->assertStringContainsString('margin: 1.5cm;', $renderedLampiran);
        $this->assertStringContainsString('size: A4 landscape;', $renderedLampiran);
    }

    public function test_employee_import_saves_data(): void
    {
        $import = new EmployeeImport;
        $import->collection(collect([
            [
                'nip' => '199001012015011001',
                'nama_lengkap' => 'Guru Pengajar, M.Pd.',
                'jabatan' => 'Guru Fisika',
            ],
            [
                'nip' => null,
                'nama' => 'Staff Non PNS',
                'jabatan' => 'Staf Tata Usaha',
            ],
        ]));

        $this->assertEquals(2, $import->importedCount);
        $this->assertDatabaseHas('pegawai', ['nip' => '199001012015011001']);
        $this->assertDatabaseHas('pegawai', ['nama' => 'Staff Non PNS']);
    }

    public function test_setting_singleton_and_update(): void
    {
        $setting = Setting::current();
        $this->assertNotNull($setting);
        $this->assertEquals('Drs. H. Ahmad Sudrajat, M.Pd.', $setting->nama_kepala_sekolah);

        $setting->update([
            'nama_kepala_sekolah' => 'Dr. H. Bambang Irawan, M.Si.',
            'gunakan_spesimen' => true,
        ]);

        $this->assertEquals('Dr. H. Bambang Irawan, M.Si.', Setting::current()->nama_kepala_sekolah);
        $this->assertTrue(Setting::current()->gunakan_spesimen);
    }

    public function test_authenticated_user_can_download_pdf(): void
    {
        $user = User::factory()->create();

        $employee = Employee::create([
            'nama' => 'Siti Aminah, S.Pd.',
            'nip' => '198802022012012002',
            'jabatan' => 'Guru Biologi',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'nomor_surat' => '077',
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => now()->toDateString(),
            'status_presensi' => 'HD',
            'jam_datang_asli' => '07:10:00',
            'jam_datang_ubah' => '07:10:00',
            'jam_pulang_asli' => null,
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $response = $this->actingAs($user)->get(route('perbaikan.download-final', $report));
        $response->assertOk();
        $this->assertEquals('application/pdf', $response->headers->get('Content-Type'));
        $this->assertStringContainsString('Perbaikan Presensi Bulan', (string) $response->headers->get('Content-Disposition'));
        $this->assertStringNotContainsString('-', (string) $response->headers->get('Content-Disposition'));

        $draftResponse = $this->actingAs($user)->get(route('perbaikan.download-draft', $report));
        $draftResponse->assertOk();
        $this->assertEquals('application/pdf', $draftResponse->headers->get('Content-Type'));
        $this->assertStringContainsString('Draf Perbaikan Presensi Bulan', (string) $draftResponse->headers->get('Content-Disposition'));
        $this->assertStringNotContainsString('-', (string) $draftResponse->headers->get('Content-Disposition'));
    }

    public function test_widgets_and_dashboard_pages_render_without_errors(): void
    {
        $user = User::factory()->create();

        $this->actingAs($user);

        $response = $this->get('/admin');
        $response->assertOk();

        $responsePegawai = $this->get('/admin/pegawai');
        $responsePegawai->assertOk();

        $responsePerbaikan = $this->get('/admin/perbaikan');
        $responsePerbaikan->assertOk();

        $responseLaporan = $this->get('/admin/laporan');
        $responseLaporan->assertOk();

        $responsePengaturan = $this->get('/admin/pengaturan');
        $responsePengaturan->assertOk();
    }

    public function test_employee_and_attendance_relation_manager_pages_render_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::create([
            'nama' => 'Dra. Hj. Siti Fatimah, M.Pd.',
            'nip' => '197901012005012003',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 11,
            'tahun' => 2026,
            'nomor_surat' => '088',
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => '2026-11-05',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $this->get('/admin/pegawai/create')->assertOk();

        $this->get("/admin/pegawai/{$employee->id}/edit")->assertOk();

        $this->get('/admin/perbaikan/create')->assertOk();

        $responseReportEdit = $this->get("/admin/perbaikan/{$report->id}/edit");
        $responseReportEdit->assertOk();

        Livewire::test(CorrectionsRelationManager::class, [
            'ownerRecord' => $report,
            'pageClass' => EditAttendanceReport::class,
        ])
            ->assertSuccessful()
            ->assertSee('Dra. Hj. Siti Fatimah, M.Pd.');
    }

    public function test_can_trigger_generate_pdf_table_action_with_notification(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::create([
            'nama' => 'Drs. H. Maman Suratman, M.Pd.',
            'nip' => '196803151994031005',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 12,
            'tahun' => 2026,
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $employee->id,
            'tanggal' => '2026-12-10',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        Livewire::test(ListAttendanceReports::class)
            ->callTableAction('generatePdf', $report, data: [
                'nomor_surat' => '115',
                'tanggal_surat' => '2026-12-30',
            ])
            ->assertHasNoTableActionErrors()
            ->assertNotified();

        $this->assertEquals('final', $report->fresh()->status);
        $this->assertEquals('800/115/SMA.1-MLP/2026', $report->fresh()->nomor_surat_lengkap);
    }

    public function test_attendance_correction_create_and_edit_pages_render_and_submit_successfully(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $employee = Employee::create([
            'nama' => 'Deden Kurnia, S.Pd.',
            'nip' => '198205202008011006',
        ]);

        $report = AttendanceReport::create([
            'bulan' => 5,
            'tahun' => 2026,
            'status' => 'draf',
        ]);

        $this->get("/admin/detail-perbaikan/create?perbaikan_id={$report->id}")
            ->assertOk()
            ->assertSee('Tambah Pegawai');

        Livewire::withQueryParams(['perbaikan_id' => $report->id])
            ->test(CreateAttendanceCorrection::class)
            ->fillForm([
                'perbaikan_id' => $report->id,
                'pegawai_id' => $employee->id,
                'tanggal' => '2026-05-15',
                'status_presensi' => 'HD',
                'jam_datang_asli' => '07:10:00',
                'jam_datang_ubah' => '07:10:00',
                'jam_pulang_asli' => null,
                'jam_pulang_ubah' => '15:30:00',
            ])
            ->call('create')
            ->assertHasNoFormErrors()
            ->assertRedirect(AttendanceReportResource::getUrl('edit', ['record' => $report->id]));

        $correction = AttendanceCorrection::where('perbaikan_id', $report->id)->first();
        $this->assertNotNull($correction);
        $this->assertEquals('HD', $correction->status_presensi);

        $this->get("/admin/detail-perbaikan/{$correction->id}/edit")
            ->assertOk()
            ->assertSee('Ubah Data Presensi Pegawai');
    }

    public function test_corrections_relation_manager_orders_by_tanggal_and_nama(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $empB = Employee::create(['nama' => 'Budi Santoso', 'nip' => '198001012005011001']);
        $empA = Employee::create(['nama' => 'Andi Wijaya', 'nip' => '198001012005011002']);

        $report = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'status' => 'draf',
        ]);

        $c3 = AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $empB->id,
            'tanggal' => '2026-10-20',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $c1 = AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $empB->id,
            'tanggal' => '2026-10-10',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $c2 = AttendanceCorrection::create([
            'perbaikan_id' => $report->id,
            'pegawai_id' => $empA->id,
            'tanggal' => '2026-10-10',
            'status_presensi' => 'HD',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $test = Livewire::test(CorrectionsRelationManager::class, [
            'ownerRecord' => $report,
            'pageClass' => EditAttendanceReport::class,
        ]);

        $records = $test->instance()->getTableRecords();
        $this->assertEquals(3, $records->count());

        $this->assertEquals($c2->id, $records->values()->get(0)->id);
        $this->assertEquals($c1->id, $records->values()->get(1)->id);
        $this->assertEquals($c3->id, $records->values()->get(2)->id);
    }

    public function test_employee_monthly_submission_stats_and_edit_page(): void
    {
        $user = User::factory()->create();
        $this->actingAs($user);

        $emp = Employee::create(['nama' => 'Dewi Anggraini', 'nip' => '199001012015012001']);

        $report1 = AttendanceReport::create([
            'bulan' => 9,
            'tahun' => 2026,
            'status' => 'final',
        ]);

        $report2 = AttendanceReport::create([
            'bulan' => 10,
            'tahun' => 2026,
            'status' => 'draf',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report1->id,
            'pegawai_id' => $emp->id,
            'tanggal' => '2026-09-12',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report2->id,
            'pegawai_id' => $emp->id,
            'tanggal' => '2026-10-05',
            'status_presensi' => 'TM',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        AttendanceCorrection::create([
            'perbaikan_id' => $report2->id,
            'pegawai_id' => $emp->id,
            'tanggal' => '2026-10-15',
            'status_presensi' => 'HD',
            'jam_datang_ubah' => '07:00:00',
            'jam_pulang_ubah' => '15:30:00',
        ]);

        $stats = $emp->getMonthlySubmissionStats();
        $this->assertCount(2, $stats);

        $october = $stats->firstWhere('bulan', 10);
        $this->assertNotNull($october);
        $this->assertEquals(2, $october->total_pengajuan);
        $this->assertEquals(1, $october->tm_count);
        $this->assertEquals(1, $october->hd_count);
        $this->assertEquals('Oktober 2026', $october->periode);

        $september = $stats->firstWhere('bulan', 9);
        $this->assertNotNull($september);
        $this->assertEquals(1, $september->total_pengajuan);
        $this->assertEquals('September 2026', $september->periode);

        $this->get("/admin/pegawai/{$emp->id}/edit")
            ->assertOk()
            ->assertSee('Riwayat')
            ->assertSee('Oktober 2026')
            ->assertSee('2 kali pengajuan')
            ->assertSee('September 2026')
            ->assertSee('1 kali pengajuan');
    }
}
