<style>
    .emp-subs-wrapper {
        display: flex;
        flex-direction: column;
        gap: 16px;
        width: 100%;
        box-sizing: border-box;
        font-family: inherit;
    }

    .emp-subs-stats {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 12px;
    }
    @media (min-width: 768px) {
        .emp-subs-stats {
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }
    }
    .emp-subs-card {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        padding: 14px 16px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
    }
    .emp-subs-card-icon {
        width: 42px;
        height: 42px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
    }
    .emp-subs-card-icon svg {
        width: 22px;
        height: 22px;
    }
    .emp-subs-card-icon.is-primary {
        background-color: #eff6ff;
        color: #2563eb;
    }
    .emp-subs-card-icon.is-danger {
        background-color: #fef2f2;
        color: #ef4444;
    }
    .emp-subs-card-icon.is-warning {
        background-color: #fffbeb;
        color: #f59e0b;
    }
    .emp-subs-card-icon.is-info {
        background-color: #f8fafc;
        color: #64748b;
    }
    .emp-subs-card-content {
        display: flex;
        flex-direction: column;
    }
    .emp-subs-card-label {
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #64748b;
    }
    .emp-subs-card-val {
        font-size: 22px;
        font-weight: 800;
        color: #0f172a;
        line-height: 1.2;
        margin-top: 2px;
    }
    .emp-subs-card-val span {
        font-size: 12px;
        font-weight: 500;
        color: #64748b;
        margin-left: 2px;
    }

    .emp-subs-table-box {
        background-color: #ffffff;
        border: 1px solid #e2e8f0;
        border-radius: 12px;
        overflow: hidden;
        box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        box-sizing: border-box;
    }
    .emp-subs-table-header {
        padding: 12px 18px;
        background-color: #f8fafc;
        border-bottom: 1px solid #e2e8f0;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 8px;
    }
    .emp-subs-table-header-title {
        font-size: 13px;
        font-weight: 700;
        color: #1e293b;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .emp-subs-table-header-sub {
        font-size: 11.5px;
        color: #64748b;
    }
    .emp-subs-table-scroll {
        width: 100%;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    .emp-subs-table {
        width: 100%;
        border-collapse: collapse;
        text-align: left;
        font-size: 13px;
    }
    .emp-subs-table thead th {
        background-color: #f8fafc;
        padding: 11px 16px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.05em;
        color: #475569;
        border-bottom: 1px solid #e2e8f0;
        white-space: nowrap;
    }
    .emp-subs-table tbody tr {
        border-bottom: 1px solid #f1f5f9;
        transition: background-color 0.15s ease;
    }
    .emp-subs-table tbody tr:hover {
        background-color: #f8fafc;
    }
    .emp-subs-table tbody td {
        padding: 12px 16px;
        color: #1e293b;
        vertical-align: middle;
    }
    .emp-subs-table tfoot td {
        padding: 12px 16px;
        background-color: #f8fafc;
        font-weight: 700;
        border-top: 2px solid #e2e8f0;
        color: #0f172a;
    }

    .emp-subs-pill {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 4px 12px;
        border-radius: 9999px;
        font-size: 12px;
        font-weight: 700;
        line-height: 1.2;
        white-space: nowrap;
    }
    .emp-subs-pill.is-primary {
        background-color: #eff6ff;
        color: #1d4ed8;
        border: 1px solid #bfdbfe;
    }
    .emp-subs-badge-status {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 3px 8px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
        white-space: nowrap;
    }
    .emp-subs-badge-status.is-tm {
        background-color: #fef2f2;
        color: #dc2626;
        border: 1px solid #fecaca;
    }
    .emp-subs-badge-status.is-hd {
        background-color: #fffbeb;
        color: #d97706;
        border: 1px solid #fde68a;
    }
    .emp-subs-dot {
        width: 6px;
        height: 6px;
        border-radius: 9999px;
        display: inline-block;
    }
    .emp-subs-dot.is-tm { background-color: #ef4444; }
    .emp-subs-dot.is-hd { background-color: #f59e0b; }

    .emp-subs-progress-track {
        width: 100%;
        max-width: 120px;
        height: 8px;
        background-color: #f1f5f9;
        border-radius: 9999px;
        overflow: hidden;
        display: flex;
    }
    .emp-subs-progress-bar {
        height: 100%;
        background-color: #2563eb;
        border-radius: 9999px;
        transition: width 0.3s ease;
    }

    .dark .emp-subs-card {
        background-color: #18181b;
        border-color: #27272a;
    }
    .dark .emp-subs-card-val {
        color: #f4f4f5;
    }
    .dark .emp-subs-card-label,
    .dark .emp-subs-card-val span {
        color: #a1a1aa;
    }
    .dark .emp-subs-card-icon.is-primary {
        background-color: rgba(37, 99, 235, 0.15);
        color: #60a5fa;
    }
    .dark .emp-subs-card-icon.is-danger {
        background-color: rgba(239, 68, 68, 0.15);
        color: #f87171;
    }
    .dark .emp-subs-card-icon.is-warning {
        background-color: rgba(245, 158, 11, 0.15);
        color: #fbbf24;
    }
    .dark .emp-subs-card-icon.is-info {
        background-color: #27272a;
        color: #a1a1aa;
    }
    .dark .emp-subs-table-box {
        background-color: #18181b;
        border-color: #27272a;
    }
    .dark .emp-subs-table-header {
        background-color: #202024;
        border-bottom-color: #27272a;
    }
    .dark .emp-subs-table-header-title {
        color: #f4f4f5;
    }
    .dark .emp-subs-table-header-sub {
        color: #a1a1aa;
    }
    .dark .emp-subs-table thead th {
        background-color: #202024;
        border-bottom-color: #27272a;
        color: #94a3b8;
    }
    .dark .emp-subs-table tbody tr {
        border-bottom-color: #27272a;
    }
    .dark .emp-subs-table tbody tr:hover {
        background-color: #202024;
    }
    .dark .emp-subs-table tbody td {
        color: #e4e4e7;
    }
    .dark .emp-subs-table tfoot td {
        background-color: #202024;
        border-top-color: #3f3f46;
        color: #f4f4f5;
    }
    .dark .emp-subs-pill.is-primary {
        background-color: rgba(37, 99, 235, 0.2);
        color: #93c5fd;
        border-color: rgba(37, 99, 235, 0.4);
    }
    .dark .emp-subs-badge-status.is-tm {
        background-color: rgba(239, 68, 68, 0.15);
        color: #fca5a5;
        border-color: rgba(239, 68, 68, 0.3);
    }
    .dark .emp-subs-badge-status.is-hd {
        background-color: rgba(245, 158, 11, 0.15);
        color: #fcd34d;
        border-color: rgba(245, 158, 11, 0.3);
    }
    .dark .emp-subs-progress-track {
        background-color: #27272a;
    }
</style>

<div class="emp-subs-wrapper">
    @if($stats->isEmpty())
        <div class="emp-subs-card" style="padding: 20px; border-style: dashed; justify-content: flex-start;">
            <div class="emp-subs-card-icon" style="background-color: #ecfdf5; color: #059669;">
                <svg fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>
            <div>
                <div style="font-size: 14px; font-weight: 700; color: #0f172a;" class="dark:text-white">Tidak Ada Catatan Kendala Presensi</div>
                <div style="font-size: 12px; color: #64748b;" class="dark:text-gray-400">Pegawai ini belum pernah mengajukan perbaikan kendala presensi.</div>
            </div>
        </div>
    @else
        {{-- KPI Summary Cards --}}
        <div class="emp-subs-stats">
            <div class="emp-subs-card">
                <div class="emp-subs-card-icon is-primary">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                </div>
                <div class="emp-subs-card-content">
                    <span class="emp-subs-card-label">Total</span>
                    <span class="emp-subs-card-val">{{ $totalAll }} <span>kali</span></span>
                </div>
            </div>

            <div class="emp-subs-card">
                <div class="emp-subs-card-icon is-danger">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </div>
                <div class="emp-subs-card-content">
                    <span class="emp-subs-card-label">TM</span>
                    <span class="emp-subs-card-val">{{ $totalTm }} <span>kali</span></span>
                </div>
            </div>

            <div class="emp-subs-card">
                <div class="emp-subs-card-icon is-warning">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                </div>
                <div class="emp-subs-card-content">
                    <span class="emp-subs-card-label">HD</span>
                    <span class="emp-subs-card-val">{{ $totalHd }} <span>kali</span></span>
                </div>
            </div>

            <div class="emp-subs-card">
                <div class="emp-subs-card-icon is-info">
                    <svg fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </div>
                <div class="emp-subs-card-content">
                    <span class="emp-subs-card-label">Pengajuan</span>
                    <span class="emp-subs-card-val">{{ $stats->count() }} <span>bulan</span></span>
                </div>
            </div>
        </div>

        {{-- Table Container --}}
        <div class="emp-subs-table-box">
            <div class="emp-subs-table-header">
                <div class="emp-subs-table-header-title">
                    <svg style="width: 16px; height: 16px; color: #2563eb;" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 12h16.5m-16.5 3.75h16.5M3.75 19.5h16.5M5.625 4.5h12.75a1.875 1.875 0 0 1 0 3.75H5.625a1.875 1.875 0 0 1 0-3.75Z" />
                    </svg>
                    <span>Rekapitulasi</span>
                </div>
            </div>

            <div class="emp-subs-table-scroll">
                <table class="emp-subs-table">
                    <thead>
                        <tr>
                            <th style="width: 50px; text-align: center;">No</th>
                            <th style="min-width: 180px;">Periode</th>
                            <th style="min-width: 170px;">Frekuensi</th>
                            <th style="min-width: 230px;">Status Kendala</th>
                            <th style="min-width: 150px;">Porsi Pengajuan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($stats as $index => $row)
                            @php
                                $percent = $totalAll > 0 ? round(($row->total_pengajuan / $totalAll) * 100) : 0;
                            @endphp
                            <tr>
                                <td style="text-align: center; color: #94a3b8; font-weight: 600;">{{ $index + 1 }}</td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 10px;">
                                        <div style="width: 32px; height: 32px; border-radius: 8px; background-color: #eff6ff; color: #2563eb; display: flex; align-items: center; justify-content: center; font-weight: 800; font-size: 11.5px; flex-shrink: 0;" class="dark:bg-blue-950/40 dark:text-blue-400">
                                            {{ str_pad($row->bulan, 2, '0', STR_PAD_LEFT) }}
                                        </div>
                                        <div>
                                            <div style="font-weight: 700; font-size: 13.5px;">{{ $row->periode }}</div>
                                            <div style="font-size: 11px; color: #64748b;" class="dark:text-gray-400">Tahun Kalender {{ $row->tahun }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td style="text-align: left;">
                                    <span class="emp-subs-pill is-primary">
                                        {{ $row->total_pengajuan }} kali pengajuan
                                    </span>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 6px; flex-wrap: wrap;">
                                        @if($row->tm_count > 0)
                                            <span class="emp-subs-badge-status is-tm" title="Tidak Absen Sama Sekali (TM)">
                                                <span class="emp-subs-dot is-tm"></span>
                                                {{ $row->tm_count }}x TM
                                            </span>
                                        @endif
                                        @if($row->hd_count > 0)
                                            <span class="emp-subs-badge-status is-hd" title="Hadir tapi Tidak Absen Pulang (HD)">
                                                <span class="emp-subs-dot is-hd"></span>
                                                {{ $row->hd_count }}x HD
                                            </span>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div style="display: flex; align-items: center; gap: 8px;">
                                        <div class="emp-subs-progress-track">
                                            <div class="emp-subs-progress-bar" style="width: {{ $percent }}%;"></div>
                                        </div>
                                        <span style="font-size: 11.5px; font-weight: 600; color: #64748b;" class="dark:text-gray-400">{{ $percent }}%</span>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2" style="text-align: right; text-transform: uppercase; font-size: 11.5px; letter-spacing: 0.04em;">
                                Total:
                            </td>
                            <td style="text-align: left;">
                                <span class="emp-subs-pill is-primary" style="font-size: 12.5px;">
                                    {{ $totalAll }} kali pengajuan
                                </span>
                            </td>
                            <td colspan="2">
                                <div style="display: flex; align-items: center; gap: 8px;">
                                    <span class="emp-subs-badge-status is-tm">{{ $totalTm }}x TM</span>
                                    <span class="emp-subs-badge-status is-hd">{{ $totalHd }}x HD</span>
                                </div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endif
</div>
