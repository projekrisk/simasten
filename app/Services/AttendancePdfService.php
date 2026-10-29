<?php

namespace App\Services;

use App\Models\AttendanceReport;
use App\Models\Setting;
use Barryvdh\DomPDF\Facade\Pdf;
use iio\libmergepdf\Merger;
use Illuminate\Http\Response;

class AttendancePdfService
{
    /**
     * Download the generated PDF directly on the fly without saving to disk
     */
    public function download(AttendanceReport $report, bool $isDraft = false): Response
    {
        $content = $this->render($report, $isDraft);

        if (! $isDraft) {
            $report->update([
                'status' => 'final',
            ]);
        }

        $prefix = $isDraft ? 'Draf ' : '';
        $nomorClean = $report->nomor_surat ? preg_replace('/[^a-zA-Z0-9]+/', ' ', $report->nomor_surat) : null;
        $nomorPart = $nomorClean ? ' No '.trim($nomorClean) : '';
        $filename = "{$prefix}Perbaikan Presensi Bulan {$report->nama_bulan} Tahun {$report->tahun}{$nomorPart}.pdf";

        return response($content, 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ]);
    }

    /**
     * Render raw PDF content in-memory without saving to disk
     */
    public function render(AttendanceReport $report, bool $isDraft = false): string
    {
        $report->loadMissing(['corrections.employee']);

        $sortedCorrections = $report->corrections->sortBy([
            ['tanggal', 'asc'],
            ['employee.nama', 'asc'],
        ])->values();
        $report->setRelation('corrections', $sortedCorrections);

        $setting = Setting::current();

        $suratPdf = Pdf::loadView('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => $setting,
            'isDraft' => $isDraft,
            'section' => 'surat',
        ]);
        $suratPdf->setPaper('a4', 'portrait')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $lampiranPdf = Pdf::loadView('pdf.perbaikan-presensi', [
            'report' => $report,
            'setting' => $setting,
            'isDraft' => $isDraft,
            'section' => 'lampiran',
        ]);
        $lampiranPdf->setPaper('a4', 'landscape')
            ->setOption('isRemoteEnabled', true)
            ->setOption('isHtml5ParserEnabled', true);

        $merger = new Merger;
        $merger->addRaw($suratPdf->output());
        $merger->addRaw($lampiranPdf->output());

        if (! $isDraft) {
            $report->update([
                'status' => 'final',
            ]);
        }

        return $merger->merge();
    }
}
