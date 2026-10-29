<?php

namespace App\Http\Controllers;

use App\Models\AttendanceReport;
use App\Services\AttendancePdfService;
use Symfony\Component\HttpFoundation\Response;

class AttendanceReportController extends Controller
{
    public function __construct(
        protected AttendancePdfService $pdfService
    ) {}

    public function downloadFinal(AttendanceReport $report): Response
    {
        return $this->pdfService->download($report, isDraft: false);
    }

    public function downloadDraft(AttendanceReport $report): Response
    {
        return $this->pdfService->download($report, isDraft: true);
    }
}
