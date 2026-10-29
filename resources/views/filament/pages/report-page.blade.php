<x-filament-panels::page>
    @php
        $history = $this->getHistoryStats();
        $comparison = $this->getStatusComparison();
        $currentYear = (int) date('Y');
    @endphp

    <style>
        .simasten-report-wrapper {
            display: flex;
            flex-direction: column;
            gap: 16px;
            font-family: inherit;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
        }
        @media (min-width: 768px) {
            .simasten-report-wrapper {
                gap: 20px;
            }
        }

        /* STRICT ICON SIZING */
        .simasten-report-wrapper svg {
            display: inline-block;
            vertical-align: middle;
            flex-shrink: 0;
        }
        .simasten-icon-sm {
            width: 16px !important;
            height: 16px !important;
            min-width: 16px !important;
            max-width: 16px !important;
        }
        .simasten-icon-md {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            max-width: 20px !important;
        }
        .simasten-stat-icon svg {
            width: 20px !important;
            height: 20px !important;
            min-width: 20px !important;
            max-width: 20px !important;
        }
        .simasten-btn svg {
            width: 15px !important;
            height: 15px !important;
            min-width: 15px !important;
            max-width: 15px !important;
        }

        /* HEADER FILTER CARD */
        .simasten-header-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: stretch;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        @media (min-width: 640px) {
            .simasten-header-card {
                padding: 16px 20px;
                flex-direction: row;
                align-items: center;
                gap: 16px;
            }
        }
        .simasten-header-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 15px;
            font-weight: 700;
            color: #0f172a;
        }
        @media (min-width: 640px) {
            .simasten-header-title {
                font-size: 16px;
            }
        }
        .simasten-header-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.4;
        }
        .simasten-filter-control {
            display: flex;
            flex-direction: column;
            gap: 10px;
            width: 100%;
        }
        @media (min-width: 640px) {
            .simasten-filter-control {
                width: auto;
                flex-direction: row;
                align-items: center;
                gap: 10px;
                flex-wrap: wrap;
            }
        }
        .simasten-filter-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            width: 100%;
        }
        @media (min-width: 640px) {
            .simasten-filter-row {
                width: auto;
                justify-content: flex-start;
            }
        }
        .simasten-year-input {
            padding: 8px 12px;
            font-size: 13px;
            font-weight: 700;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            background-color: #ffffff;
            color: #0f172a;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.05);
            outline: none;
            transition: all 0.15s ease;
            width: 100px;
            text-align: center;
        }
        .simasten-year-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        /* KPI STATS GRID */
        .simasten-stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 640px) {
            .simasten-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 14px;
            }
            .simasten-stats-grid .simasten-stat-card:first-child {
                grid-column: span 2;
            }
        }
        @media (min-width: 1024px) {
            .simasten-stats-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
                gap: 16px;
            }
            .simasten-stats-grid .simasten-stat-card:first-child {
                grid-column: span 1;
            }
        }
        .simasten-stat-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 18px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        @media (min-width: 768px) {
            .simasten-stat-card {
                padding: 18px 20px;
            }
        }
        .simasten-stat-card.card-primary {
            border-top: 3px solid #2563eb;
        }
        .simasten-stat-card.card-danger {
            border-top: 3px solid #ef4444;
        }
        .simasten-stat-card.card-warning {
            border-top: 3px solid #f59e0b;
        }
        .simasten-stat-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .simasten-stat-label {
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            color: #64748b;
        }
        .simasten-stat-icon {
            width: 36px;
            height: 36px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .simasten-stat-icon.icon-primary {
            background-color: #eff6ff;
            color: #2563eb;
        }
        .simasten-stat-icon.icon-danger {
            background-color: #fef2f2;
            color: #ef4444;
        }
        .simasten-stat-icon.icon-warning {
            background-color: #fffbeb;
            color: #f59e0b;
        }
        .simasten-stat-body {
            display: flex;
            align-items: baseline;
            gap: 8px;
            margin: 10px 0 6px 0;
            flex-wrap: wrap;
        }
        .simasten-stat-number {
            font-size: clamp(26px, 4vw, 32px);
            font-weight: 800;
            line-height: 1.1;
            color: #0f172a;
            letter-spacing: -0.02em;
        }
        .simasten-stat-number.color-danger {
            color: #dc2626;
        }
        .simasten-stat-number.color-warning {
            color: #d97706;
        }
        .simasten-stat-footer {
            font-size: 12px;
            color: #64748b;
            padding-top: 8px;
            border-top: 1px solid #f1f5f9;
            line-height: 1.4;
        }

        /* RATIO PROGRESS CARD */
        .simasten-ratio-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        @media (min-width: 640px) {
            .simasten-ratio-card {
                padding: 16px 20px;
            }
        }
        .simasten-ratio-header {
            display: flex;
            flex-direction: column;
            gap: 4px;
            font-size: 12px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 10px;
        }
        @media (min-width: 640px) {
            .simasten-ratio-header {
                flex-direction: row;
                justify-content: space-between;
                align-items: center;
                gap: 8px;
            }
        }
        .simasten-ratio-track {
            height: 14px;
            width: 100%;
            background-color: #f1f5f9;
            border-radius: 9999px;
            overflow: hidden;
            display: flex;
            box-shadow: inset 0 1px 2px rgba(0, 0, 0, 0.08);
        }
        .simasten-ratio-fill-tm {
            background-color: #ef4444;
            height: 100%;
            transition: width 0.4s ease;
        }
        .simasten-ratio-fill-hd {
            background-color: #f59e0b;
            height: 100%;
            transition: width 0.4s ease;
        }
        .simasten-ratio-legend {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
            font-size: 12px;
            color: #475569;
        }
        @media (min-width: 640px) {
            .simasten-ratio-legend {
                flex-direction: row;
                flex-wrap: wrap;
                gap: 18px;
            }
        }
        .simasten-legend-item {
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .simasten-legend-dot {
            width: 10px;
            height: 10px;
            border-radius: 9999px;
            flex-shrink: 0;
        }
        .simasten-legend-dot.dot-tm {
            background-color: #ef4444;
        }
        .simasten-legend-dot.dot-hd {
            background-color: #f59e0b;
        }

        /* DATA TABLE SECTIONS */
        .simasten-table-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        .simasten-table-header {
            padding: 14px 16px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            gap: 4px;
        }
        @media (min-width: 640px) {
            .simasten-table-header {
                padding: 16px 20px;
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
                gap: 8px;
            }
        }
        .simasten-table-title {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 14px;
            font-weight: 700;
            color: #0f172a;
        }
        .simasten-table-sub {
            font-size: 12px;
            color: #64748b;
        }

        /* MOBILE SCROLL HINT */
        .simasten-table-hint {
            display: none;
            align-items: center;
            gap: 6px;
            font-size: 11.5px;
            color: #64748b;
            padding: 8px 16px;
            background-color: #f8fafc;
            border-bottom: 1px solid #e2e8f0;
        }
        @media (max-width: 767px) {
            .simasten-table-hint {
                display: flex;
            }
        }

        .simasten-table-responsive {
            width: 100%;
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
        .simasten-table {
            width: 100%;
            border-collapse: collapse;
            text-align: left;
            font-size: 13px;
            min-width: 760px;
        }
        .simasten-table thead {
            background-color: #f8fafc;
            border-bottom: 2px solid #e2e8f0;
        }
        .simasten-table th {
            padding: 10px 14px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: #475569;
            white-space: nowrap;
        }
        @media (min-width: 768px) {
            .simasten-table th {
                padding: 12px 18px;
            }
        }
        .simasten-table tbody tr {
            border-bottom: 1px solid #f1f5f9;
            transition: background-color 0.15s ease;
        }
        .simasten-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .simasten-table td {
            padding: 10px 14px;
            vertical-align: middle;
            color: #1e293b;
        }
        @media (min-width: 768px) {
            .simasten-table td {
                padding: 12px 18px;
            }
        }
        .simasten-table td.align-center,
        .simasten-table th.align-center {
            text-align: center;
        }

        /* STICKY FIRST COLUMN FOR MONTHS */
        .simasten-table th:first-child,
        .simasten-table td:first-child {
            position: sticky;
            left: 0;
            z-index: 2;
            background-color: #ffffff;
            box-shadow: 2px 0 4px -1px rgba(0, 0, 0, 0.06);
        }
        .simasten-table thead th:first-child {
            background-color: #f8fafc;
            z-index: 3;
        }
        .simasten-table tbody tr:hover td:first-child {
            background-color: #f8fafc;
        }

        /* BADGES */
        .simasten-pill-badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: 9999px;
            font-size: 11px;
            font-weight: 700;
            line-height: 1;
            white-space: nowrap;
        }
        .simasten-pill-badge.badge-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #86efac;
        }
        .simasten-pill-badge.badge-warning {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .simasten-pill-badge.badge-danger {
            background-color: #fef2f2;
            color: #b91c1c;
            border: 1px solid #fecaca;
        }
        .simasten-pill-badge.badge-info {
            background-color: #eff6ff;
            color: #1d4ed8;
            border: 1px solid #bfdbfe;
        }
        .simasten-badge-none {
            color: #94a3b8;
            font-style: italic;
            font-size: 12px;
        }

        /* MONTH NUMBER BADGE */
        .simasten-month-pill {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 24px;
            height: 24px;
            border-radius: 6px;
            background-color: #f1f5f9;
            color: #334155;
            font-family: monospace;
            font-weight: 700;
            font-size: 11px;
            margin-right: 8px;
            flex-shrink: 0;
        }

        /* ACTION BUTTONS */
        .simasten-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 8px 14px;
            border-radius: 8px;
            font-size: 12.5px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s ease;
            cursor: pointer;
            border: none;
            line-height: 1.2;
            white-space: nowrap;
        }
        .simasten-btn-primary {
            background-color: #2563eb;
            color: #ffffff;
        }
        .simasten-btn-primary:hover {
            background-color: #1d4ed8;
            color: #ffffff;
        }
        .simasten-btn-secondary {
            background-color: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
            padding: 6px 12px;
            font-size: 12px;
        }
        .simasten-btn-secondary:hover {
            background-color: #e2e8f0;
            color: #0f172a;
        }
        .simasten-btn-danger {
            background-color: #dc2626;
            color: #ffffff;
        }
        .simasten-btn-danger:hover {
            background-color: #b91c1c;
            color: #ffffff;
        }

        /* DOWNLOAD RANKING CARD */
        .simasten-download-ranking-card {
            background-color: #ffffff;
            border: 1px solid #fecaca;
            border-radius: 12px;
            padding: 16px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            align-items: stretch;
            gap: 14px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
        }
        @media (min-width: 768px) {
            .simasten-download-ranking-card {
                padding: 18px 22px;
                flex-direction: row;
                align-items: center;
                gap: 18px;
            }
        }
        .simasten-download-ranking-left {
            display: flex;
            align-items: flex-start;
            gap: 12px;
        }
        @media (min-width: 640px) {
            .simasten-download-ranking-left {
                align-items: center;
                gap: 14px;
            }
        }
        .simasten-download-ranking-icon {
            width: 44px;
            height: 44px;
            border-radius: 10px;
            background-color: #fef2f2;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .simasten-download-ranking-title {
            font-size: 14.5px;
            font-weight: 700;
            color: #0f172a;
        }
        @media (min-width: 640px) {
            .simasten-download-ranking-title {
                font-size: 15px;
            }
        }
        .simasten-download-ranking-desc {
            font-size: 12px;
            color: #64748b;
            margin-top: 3px;
            line-height: 1.4;
        }
        .simasten-download-ranking-btn-ctn {
            width: 100%;
        }
        @media (min-width: 768px) {
            .simasten-download-ranking-btn-ctn {
                width: auto;
                flex-shrink: 0;
            }
        }
        .simasten-download-ranking-btn-ctn .simasten-btn {
            width: 100%;
            padding: 10px 16px;
            font-size: 13px;
            font-weight: 700;
        }
        @media (min-width: 768px) {
            .simasten-download-ranking-btn-ctn .simasten-btn {
                width: auto;
                padding: 10px 20px;
            }
        }

        /* DARK MODE SUPPORT */
        .dark .simasten-header-card,
        .dark .simasten-stat-card,
        .dark .simasten-ratio-card,
        .dark .simasten-table-card {
            background-color: #18181b;
            border-color: #27272a;
        }
        .dark .simasten-header-title,
        .dark .simasten-table-title {
            color: #f4f4f5;
        }
        .dark .simasten-header-desc,
        .dark .simasten-stat-label,
        .dark .simasten-stat-footer,
        .dark .simasten-ratio-legend,
        .dark .simasten-table-sub {
            color: #a1a1aa;
        }
        .dark .simasten-stat-number {
            color: #f4f4f5;
        }
        .dark .simasten-stat-card.card-primary {
            border-top-color: #3b82f6;
        }
        .dark .simasten-stat-card.card-danger {
            border-top-color: #ef4444;
        }
        .dark .simasten-stat-card.card-warning {
            border-top-color: #f59e0b;
        }
        .dark .simasten-stat-icon.icon-primary {
            background-color: rgba(37, 99, 235, 0.2);
            color: #60a5fa;
        }
        .dark .simasten-stat-icon.icon-danger {
            background-color: rgba(239, 68, 68, 0.2);
            color: #f87171;
        }
        .dark .simasten-stat-icon.icon-warning {
            background-color: rgba(245, 158, 11, 0.2);
            color: #fbbf24;
        }
        .dark .simasten-year-input {
            background-color: #27272a;
            border-color: #3f3f46;
            color: #f4f4f5;
        }
        .dark .simasten-download-ranking-card {
            background-color: #18181b;
            border-color: rgba(239, 68, 68, 0.25);
        }
        .dark .simasten-download-ranking-icon {
            background-color: rgba(239, 68, 68, 0.15);
        }
        .dark .simasten-download-ranking-title {
            color: #f4f4f5;
        }
        .dark .simasten-download-ranking-desc {
            color: #a1a1aa;
        }
        .dark .simasten-ratio-header {
            color: #e4e4e7;
        }
        .dark .simasten-ratio-track {
            background-color: #27272a;
        }
        .dark .simasten-table-header {
            border-bottom-color: #27272a;
        }
        .dark .simasten-table-hint {
            background-color: #1f1f23;
            color: #a1a1aa;
            border-bottom-color: #27272a;
        }
        .dark .simasten-table thead {
            background-color: #202024;
            border-bottom-color: #27272a;
        }
        .dark .simasten-table th {
            color: #94a3b8;
        }
        .dark .simasten-table tbody tr {
            border-bottom-color: #27272a;
        }
        .dark .simasten-table tbody tr:hover {
            background-color: #202024;
        }
        .dark .simasten-table td {
            color: #e4e4e7;
        }
        .dark .simasten-table th:first-child,
        .dark .simasten-table td:first-child {
            background-color: #18181b;
            box-shadow: 2px 0 4px -1px rgba(0, 0, 0, 0.4);
        }
        .dark .simasten-table thead th:first-child {
            background-color: #202024;
        }
        .dark .simasten-table tbody tr:hover td:first-child {
            background-color: #202024;
        }
        .dark .simasten-month-pill {
            background-color: #27272a;
            color: #e4e4e7;
        }
        .dark .simasten-btn-secondary {
            background-color: #27272a;
            color: #e4e4e7;
            border-color: #3f3f46;
        }
        .dark .simasten-btn-secondary:hover {
            background-color: #3f3f46;
        }
        .dark .simasten-pill-badge.badge-success {
            background-color: rgba(22, 101, 52, 0.25);
            color: #4ade80;
            border-color: #166534;
        }
        .dark .simasten-pill-badge.badge-warning {
            background-color: rgba(180, 83, 9, 0.25);
            color: #fbbf24;
            border-color: #92400e;
        }
        .dark .simasten-pill-badge.badge-danger {
            background-color: rgba(185, 28, 28, 0.25);
            color: #f87171;
            border-color: #991b1b;
        }
        .dark .simasten-pill-badge.badge-info {
            background-color: rgba(29, 78, 216, 0.25);
            color: #60a5fa;
            border-color: #1d4ed8;
        }
    </style>

    <div class="simasten-report-wrapper">
        {{-- HEADER FILTER CARD --}}
        <div class="simasten-header-card">
            <div>
                <div class="simasten-header-title">
                    <x-heroicon-o-chart-pie class="simasten-icon-md" style="width: 20px; height: 20px; color: #2563eb;" />
                    <span>Laporan Perbaikan Presensi</span>
                </div>
            </div>
            <div class="simasten-filter-control">
                <div class="simasten-filter-row">
                    <label for="year-input" style="font-size: 13px; font-weight: 600; color: inherit;">
                        Periode (Tahun):
                    </label>
                    <input
                        type="number"
                        id="year-input"
                        wire:model.live.debounce.500ms="selectedYear"
                        min="2000"
                        max="2099"
                        class="simasten-year-input"
                        value="{{ $selectedYear }}"
                        placeholder="{{ $currentYear }}"
                    />
                </div>
                <button
                    type="button"
                    wire:click="downloadRankingPdf"
                    wire:loading.attr="disabled"
                    class="simasten-btn simasten-btn-danger"
                    title="Unduh Peringkat"
                >
                    <x-heroicon-o-arrow-down-tray class="simasten-icon-sm" />
                    <span wire:loading.remove wire:target="downloadRankingPdf">Unduh Peringkat</span>
                    <span wire:loading wire:target="downloadRankingPdf">Memproses...</span>
                </button>
            </div>
        </div>

        {{-- KPI STATS GRID --}}
        <div class="simasten-stats-grid">
            <!-- TOTAL KASUS -->
            <div class="simasten-stat-card card-primary">
                <div class="simasten-stat-header">
                    <span class="simasten-stat-label">Total Kasus Kendala</span>
                    <div class="simasten-stat-icon icon-primary">
                        <x-heroicon-o-clipboard-document-list class="simasten-icon-md" style="width: 20px; height: 20px;" />
                    </div>
                </div>
                <div class="simasten-stat-body">
                    <span class="simasten-stat-number">{{ number_format($comparison['total']) }}</span>
                    <span style="font-size: 12px; color: #64748b;">Ajuan</span>
                </div>
                <div class="simasten-stat-footer">
                    Akumulasi seluruh kendala presensi pada tahun {{ $selectedYear }}.
                </div>
            </div>

            <!-- KASUS TM -->
            <div class="simasten-stat-card card-danger">
                <div class="simasten-stat-header">
                    <span class="simasten-stat-label">Status TM (Telat Masuk)</span>
                    <div class="simasten-stat-icon icon-danger">
                        <x-heroicon-o-x-circle class="simasten-icon-md" style="width: 20px; height: 20px;" />
                    </div>
                </div>
                <div class="simasten-stat-body">
                    <span class="simasten-stat-number color-danger">{{ number_format($comparison['tm']) }}</span>
                    <span class="simasten-pill-badge badge-danger">{{ $comparison['tm_pct'] }}%</span>
                </div>
                <div class="simasten-stat-footer">
                    Hadir bekerja tapi tidak terekam datang & pulang.
                </div>
            </div>

            <!-- KASUS HD -->
            <div class="simasten-stat-card card-warning">
                <div class="simasten-stat-header">
                    <span class="simasten-stat-label">Status HD (Telat Pulang)</span>
                    <div class="simasten-stat-icon icon-warning">
                        <x-heroicon-o-clock class="simasten-icon-md" style="width: 20px; height: 20px;" />
                    </div>
                </div>
                <div class="simasten-stat-body">
                    <span class="simasten-stat-number color-warning">{{ number_format($comparison['hd']) }}</span>
                    <span class="simasten-pill-badge badge-warning">{{ $comparison['hd_pct'] }}%</span>
                </div>
                <div class="simasten-stat-footer">
                    Absen datang terekam, kepulangan tidak terekam.
                </div>
            </div>
        </div>

        {{-- PROPORTION BAR --}}
        @if ($comparison['total'] > 0)
            <div class="simasten-ratio-card">
                <div class="simasten-ratio-header">
                    <span>Rasio Jenis Kendala: TM vs HD</span>
                    <span>Total: {{ number_format($comparison['total']) }} Kasus</span>
                </div>
                <div class="simasten-ratio-track">
                    <div
                        style="width: {{ $comparison['tm_pct'] }}%"
                        class="simasten-ratio-fill-tm"
                        title="TM: {{ $comparison['tm'] }} kasus ({{ $comparison['tm_pct'] }}%)"
                    ></div>
                    <div
                        style="width: {{ $comparison['hd_pct'] }}%"
                        class="simasten-ratio-fill-hd"
                        title="HD: {{ $comparison['hd'] }} kasus ({{ $comparison['hd_pct'] }}%)"
                    ></div>
                </div>
                <div class="simasten-ratio-legend">
                    <div class="simasten-legend-item">
                        <span class="simasten-legend-dot dot-tm"></span>
                        <span><strong>TM (Telat Masuk):</strong> {{ $comparison['tm'] }} kasus ({{ $comparison['tm_pct'] }}%)</span>
                    </div>
                    <div class="simasten-legend-item">
                        <span class="simasten-legend-dot dot-hd"></span>
                        <span><strong>HD (Telat Pulang):</strong> {{ $comparison['hd'] }} kasus ({{ $comparison['hd_pct'] }}%)</span>
                    </div>
                </div>
            </div>
        @endif

        {{-- RIWAYAT PENGAJUAN PER BULAN --}}
        <div class="simasten-table-card">
            <div class="simasten-table-header">
                <div class="simasten-table-title">
                    <x-heroicon-o-calendar-days class="simasten-icon-md" style="width: 20px; height: 20px; color: #2563eb;" />
                    <span>Pengajuan Per Periode Bulan (Tahun {{ $selectedYear }})</span>
                </div>
                <div class="simasten-table-sub">
                    12 Bulan Kalender
                </div>
            </div>

            {{-- MOBILE SCROLL HINT --}}
            <div class="simasten-table-hint">
                <x-heroicon-m-arrows-right-left class="simasten-icon-sm" style="color: #64748b;" />
                <span>Geser tabel ke samping untuk melihat data bulan & aksi lengkap</span>
            </div>

            <div class="simasten-table-responsive">
                <table class="simasten-table">
                    <thead>
                        <tr>
                            <th style="min-width: 140px;">Bulan Periode</th>
                            <th class="align-center" style="min-width: 110px;">Ajuan</th>
                            <th class="align-center" style="min-width: 90px;">TM</th>
                            <th class="align-center" style="min-width: 90px;">HD</th>
                            <th style="min-width: 130px;">Status</th>
                            <th style="min-width: 180px;">Nomor Surat</th>
                            <th class="align-center" style="min-width: 90px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($history as $row)
                            <tr>
                                <td>
                                    <div style="display: flex; align-items: center;">
                                        <span class="simasten-month-pill">{{ sprintf('%02d', $row['bulan']) }}</span>
                                        <span style="font-weight: 700;">{{ $row['nama_bulan'] }}</span>
                                    </div>
                                </td>
                                <td class="align-center">
                                    @if ($row['total'] > 0)
                                        <span class="simasten-pill-badge badge-info">
                                            {{ $row['total'] }} Ajuan
                                        </span>
                                    @else
                                        <span style="color: #94a3b8;">-</span>
                                    @endif
                                </td>
                                <td class="align-center">
                                    @if ($row['tm'] > 0)
                                        <span class="simasten-pill-badge badge-danger">{{ $row['tm'] }}</span>
                                    @else
                                        <span style="color: #94a3b8;">0</span>
                                    @endif
                                </td>
                                <td class="align-center">
                                    @if ($row['hd'] > 0)
                                        <span class="simasten-pill-badge badge-warning">{{ $row['hd'] }}</span>
                                    @else
                                        <span style="color: #94a3b8;">0</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($row['status'] === 'final')
                                        <span class="simasten-pill-badge badge-success">FINAL</span>
                                    @elseif ($row['status'] === 'draf')
                                        <span class="simasten-pill-badge badge-warning">DRAF</span>
                                    @else
                                        <span class="simasten-badge-none">Belum Ada</span>
                                    @endif
                                </td>
                                <td style="font-family: monospace; font-size: 12px;">
                                    {{ $row['nomor_surat'] ?: '-' }}
                                </td>
                                <td class="align-center">
                                    @if ($row['report_id'])
                                        <a
                                            href="{{ \App\Filament\Resources\AttendanceReports\AttendanceReportResource::getUrl('edit', ['record' => $row['report_id']]) }}"
                                            class="simasten-btn simasten-btn-secondary"
                                        >
                                            <x-heroicon-m-eye class="simasten-icon-sm" />
                                            <span>Buka</span>
                                        </a>
                                    @else
                                        <a
                                            href="{{ \App\Filament\Resources\AttendanceReports\AttendanceReportResource::getUrl('create') }}"
                                            class="simasten-btn simasten-btn-primary"
                                        >
                                            <x-heroicon-m-plus class="simasten-icon-sm" />
                                            <span>Buat</span>
                                        </a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-filament-panels::page>
