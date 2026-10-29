<x-filament-panels::page>
    @php
        $stats = $this->stats;
        $items = $this->mediaItems;
    @endphp

    <style>
        .simasten-media-wrapper {
            display: flex;
            flex-direction: column;
            gap: 20px;
            font-family: inherit;
        }

        /* STRICT ICON SIZING (PREVENTS LARGE ICONS) */
        .simasten-media-wrapper svg {
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
        .simasten-icon-lg {
            width: 32px !important;
            height: 32px !important;
            min-width: 32px !important;
            max-width: 32px !important;
        }
        .simasten-icon-empty {
            width: 48px !important;
            height: 48px !important;
            min-width: 48px !important;
            max-width: 48px !important;
            display: block;
            margin: 0 auto 12px auto;
        }

        /* HEADER BANNER */
        .simasten-banner-card {
            background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 60%, #3b82f6 100%);
            border-radius: 14px;
            padding: 22px 24px;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(37, 99, 235, 0.2);
            position: relative;
            overflow: hidden;
        }
        .simasten-banner-card::after {
            content: '';
            position: absolute;
            top: -50%;
            right: -10%;
            width: 300px;
            height: 300px;
            background: radial-gradient(circle, rgba(255,255,255,0.15) 0%, rgba(255,255,255,0) 70%);
            border-radius: 50%;
            pointer-events: none;
        }
        .simasten-banner-title {
            font-size: 20px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 10px;
            letter-spacing: -0.01em;
        }
        .simasten-banner-desc {
            font-size: 13px;
            color: #dbeafe;
            margin-top: 6px;
            max-width: 780px;
            line-height: 1.5;
        }

        /* KPI STATS CARDS */
        .simasten-stats-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) {
            .simasten-stats-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 1024px) {
            .simasten-stats-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        .simasten-stat-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 18px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            align-items: center;
            gap: 16px;
            transition: transform 0.15s ease, box-shadow 0.15s ease;
        }
        .simasten-stat-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        }
        .dark .simasten-stat-card {
            background-color: #1e293b;
            border-color: #334155;
        }
        .simasten-stat-icon-wrap {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .simasten-stat-val {
            font-size: 22px;
            font-weight: 800;
            line-height: 1.1;
            color: #0f172a;
        }
        .dark .simasten-stat-val {
            color: #f8fafc;
        }
        .simasten-stat-lbl {
            font-size: 12px;
            font-weight: 600;
            color: #64748b;
            margin-top: 2px;
        }
        .dark .simasten-stat-lbl {
            color: #94a3b8;
        }
        .simasten-stat-sub {
            font-size: 11px;
            color: #94a3b8;
            margin-top: 2px;
        }

        /* TOOLBAR & CONTROLS */
        .simasten-toolbar {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            padding: 16px 20px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .dark .simasten-toolbar {
            background-color: #1e293b;
            border-color: #334155;
        }
        .simasten-cat-tabs {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            align-items: center;
        }
        .simasten-cat-tab {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            background-color: #f1f5f9;
            color: #475569;
        }
        .dark .simasten-cat-tab {
            background-color: #334155;
            color: #cbd5e1;
        }
        .simasten-cat-tab:hover {
            background-color: #e2e8f0;
            color: #1e293b;
        }
        .dark .simasten-cat-tab:hover {
            background-color: #475569;
            color: #f8fafc;
        }
        .simasten-cat-tab.active {
            background-color: #2563eb;
            color: #ffffff;
            box-shadow: 0 2px 6px rgba(37, 99, 235, 0.25);
        }
        .dark .simasten-cat-tab.active {
            background-color: #3b82f6;
            color: #ffffff;
        }
        .simasten-cat-badge {
            background: rgba(0, 0, 0, 0.1);
            padding: 1px 6px;
            border-radius: 10px;
            font-size: 11px;
            font-weight: 700;
        }
        .simasten-cat-tab.active .simasten-cat-badge {
            background: rgba(255, 255, 255, 0.25);
        }

        .simasten-filter-row {
            display: flex;
            flex-direction: column;
            gap: 12px;
            justify-content: space-between;
            align-items: stretch;
        }
        @media (min-width: 640px) {
            .simasten-filter-row {
                flex-direction: row;
                align-items: center;
            }
        }
        .simasten-search-box {
            position: relative;
            flex: 1;
            max-width: 450px;
        }
        .simasten-search-box input {
            width: 100%;
            padding: 8px 14px 8px 36px;
            border-radius: 8px;
            border: 1px solid #cbd5e1;
            background-color: #ffffff;
            font-size: 13px;
            color: #0f172a;
            outline: none;
            transition: all 0.15s ease;
        }
        .dark .simasten-search-box input {
            background-color: #0f172a;
            border-color: #334155;
            color: #f8fafc;
        }
        .simasten-search-box input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }
        .simasten-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            pointer-events: none;
        }

        /* BULK ACTIONS BAR */
        .simasten-bulk-bar {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }
        .simasten-btn {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 7px 14px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.15s ease;
            border: 1px solid transparent;
            text-decoration: none;
        }
        .simasten-btn-danger {
            background-color: #ef4444;
            color: #ffffff;
        }
        .simasten-btn-danger:hover {
            background-color: #dc2626;
        }
        .simasten-btn-secondary {
            background-color: #f1f5f9;
            color: #475569;
            border-color: #cbd5e1;
        }
        .dark .simasten-btn-secondary {
            background-color: #334155;
            color: #cbd5e1;
            border-color: #475569;
        }
        .simasten-btn-secondary:hover {
            background-color: #e2e8f0;
        }

        /* MEDIA CARDS GRID */
        .simasten-media-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 16px;
        }
        @media (min-width: 640px) {
            .simasten-media-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }
        @media (min-width: 860px) {
            .simasten-media-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }
        @media (min-width: 1200px) {
            .simasten-media-grid {
                grid-template-columns: repeat(4, minmax(0, 1fr));
            }
        }
        @media (min-width: 1536px) {
            .simasten-media-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }

        .simasten-media-card {
            background-color: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.04);
            display: flex;
            flex-direction: column;
            position: relative;
            transition: transform 0.15s ease, box-shadow 0.15s ease, border-color 0.15s ease;
        }
        .dark .simasten-media-card {
            background-color: #1e293b;
            border-color: #334155;
        }
        .simasten-media-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 16px rgba(0, 0, 0, 0.07);
            border-color: #cbd5e1;
        }
        .dark .simasten-media-card:hover {
            border-color: #475569;
        }
        .simasten-media-card.selected {
            border-color: #2563eb;
            box-shadow: 0 0 0 2px rgba(37, 99, 235, 0.35);
        }

        /* CARD THUMBNAIL */
        .simasten-thumb-box {
            position: relative;
            width: 100%;
            height: 180px;
            background-color: #f8fafc;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid #f1f5f9;
        }
        .dark .simasten-thumb-box {
            background-color: #0f172a;
            border-color: #334155;
        }
        .simasten-thumb-img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            cursor: pointer;
            transition: transform 0.25s ease;
        }
        .simasten-media-card:hover .simasten-thumb-img {
            transform: scale(1.04);
        }
        .simasten-thumb-pdf {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #ef4444;
            cursor: pointer;
        }
        .simasten-thumb-other {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 8px;
            color: #64748b;
        }

        /* CARD TOP BADGES & CHECKBOX */
        .simasten-card-check {
            position: absolute;
            top: 10px;
            left: 10px;
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            padding: 4px;
            border-radius: 6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.15);
            line-height: 1;
        }
        .dark .simasten-card-check {
            background: rgba(15, 23, 42, 0.85);
        }
        .simasten-card-cat-badge {
            position: absolute;
            top: 10px;
            right: 10px;
            z-index: 10;
            background: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            color: #ffffff;
            font-size: 10px;
            font-weight: 700;
            padding: 3px 8px;
            border-radius: 6px;
            letter-spacing: 0.02em;
        }

        /* CARD CONTENT */
        .simasten-card-body {
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
            gap: 8px;
        }
        .simasten-card-name {
            font-size: 13px;
            font-weight: 700;
            color: #0f172a;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            line-height: 1.3;
        }
        .dark .simasten-card-name {
            color: #f8fafc;
        }
        .simasten-card-meta {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 11px;
            color: #64748b;
        }
        .dark .simasten-card-meta {
            color: #94a3b8;
        }

        /* RELATION STATUS PILL */
        .simasten-ref-pill {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: 600;
            line-height: 1.3;
            margin-top: 2px;
        }
        .simasten-pill-info {
            background-color: #e0f2fe;
            color: #0369a1;
            border: 1px solid #bae6fd;
        }
        .dark .simasten-pill-info {
            background-color: rgba(3, 105, 161, 0.2);
            color: #7dd3fc;
            border-color: rgba(3, 105, 161, 0.4);
        }
        .simasten-pill-warning {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fde68a;
        }
        .dark .simasten-pill-warning {
            background-color: rgba(180, 83, 9, 0.2);
            color: #fcd34d;
            border-color: rgba(180, 83, 9, 0.4);
        }
        .simasten-pill-success {
            background-color: #dcfce7;
            color: #15803d;
            border: 1px solid #bbf7d0;
        }
        .dark .simasten-pill-success {
            background-color: rgba(21, 128, 61, 0.2);
            color: #86efac;
            border-color: rgba(21, 128, 61, 0.4);
        }
        .simasten-pill-primary {
            background-color: #e0e7ff;
            color: #4338ca;
            border: 1px solid #c7d2fe;
        }
        .dark .simasten-pill-primary {
            background-color: rgba(67, 56, 202, 0.2);
            color: #a5b4fc;
            border-color: rgba(67, 56, 202, 0.4);
        }
        .simasten-pill-purple {
            background-color: #f3e8ff;
            color: #7e22ce;
            border: 1px solid #e9d5ff;
        }
        .dark .simasten-pill-purple {
            background-color: rgba(126, 34, 206, 0.2);
            color: #d8b4fe;
            border-color: rgba(126, 34, 206, 0.4);
        }
        .simasten-pill-gray {
            background-color: #f1f5f9;
            color: #64748b;
            border: 1px solid #e2e8f0;
        }
        .dark .simasten-pill-gray {
            background-color: #334155;
            color: #94a3b8;
            border-color: #475569;
        }

        /* CARD FOOTER ACTIONS */
        .simasten-card-actions {
            display: grid;
            grid-template-columns: 1fr 1fr 1.2fr;
            border-top: 1px solid #f1f5f9;
            background-color: #fafafa;
        }
        .dark .simasten-card-actions {
            border-color: #334155;
            background-color: #172033;
        }
        .simasten-action-btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 4px;
            padding: 8px 6px;
            font-size: 11px;
            font-weight: 600;
            color: #475569;
            text-decoration: none;
            background: transparent;
            border: none;
            border-right: 1px solid #f1f5f9;
            cursor: pointer;
            transition: background 0.15s ease, color 0.15s ease;
        }
        .dark .simasten-action-btn {
            color: #94a3b8;
            border-right-color: #334155;
        }
        .simasten-action-btn:last-child {
            border-right: none;
        }
        .simasten-action-btn:hover {
            background-color: #f1f5f9;
            color: #0f172a;
        }
        .dark .simasten-action-btn:hover {
            background-color: #1e293b;
            color: #f8fafc;
        }
        .simasten-action-btn.btn-delete {
            color: #ef4444;
        }
        .simasten-action-btn.btn-delete:hover {
            background-color: #fee2e2;
            color: #b91c1c;
        }
        .dark .simasten-action-btn.btn-delete:hover {
            background-color: rgba(239, 68, 68, 0.2);
            color: #fca5a5;
        }

        /* EMPTY STATE */
        .simasten-empty-card {
            background-color: #ffffff;
            border: 2px dashed #cbd5e1;
            border-radius: 12px;
            padding: 48px 24px;
            text-align: center;
            color: #64748b;
        }
        .dark .simasten-empty-card {
            background-color: #1e293b;
            border-color: #334155;
            color: #94a3b8;
        }

        /* MODAL PREVIEW */
        .simasten-modal-backdrop {
            position: fixed;
            inset: 0;
            z-index: 9999;
            background-color: rgba(15, 23, 42, 0.75);
            backdrop-filter: blur(4px);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .simasten-modal-card {
            background-color: #ffffff;
            border-radius: 14px;
            max-width: 900px;
            width: 100%;
            max-height: 90vh;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.3);
        }
        .dark .simasten-modal-card {
            background-color: #1e293b;
        }
        .simasten-modal-header {
            padding: 14px 20px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .dark .simasten-modal-header {
            border-color: #334155;
        }
        .simasten-modal-body {
            padding: 16px;
            overflow-y: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #0f172a;
            max-height: 75vh;
        }
        .simasten-modal-body img {
            max-width: 100%;
            max-height: 70vh;
            object-fit: contain;
            border-radius: 6px;
        }
        .simasten-modal-body iframe {
            width: 100%;
            height: 70vh;
            border: none;
            border-radius: 6px;
            background: #ffffff;
        }
        .simasten-modal-footer {
            padding: 12px 20px;
            border-top: 1px solid #e2e8f0;
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 10px;
        }
        .dark .simasten-modal-footer {
            border-color: #334155;
        }
    </style>

    <div class="simasten-media-wrapper">

        <!-- 2. KPI STATS CARDS -->
        <div class="simasten-stats-grid">
            <div class="simasten-stat-card">
                <div class="simasten-stat-icon-wrap" style="background-color: #eff6ff; color: #2563eb;">
                    <svg class="simasten-icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path>
                    </svg>
                </div>
                <div>
                    <div class="simasten-stat-val">{{ $stats['total_count'] }}</div>
                    <div class="simasten-stat-sub">Ukuran: {{ $stats['total_size'] }}</div>
                </div>
            </div>

            <div class="simasten-stat-card">
                <div class="simasten-stat-icon-wrap" style="background-color: #f0fdf4; color: #16a34a;">
                    <svg class="simasten-icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <div class="simasten-stat-val">{{ $stats['presensi_count'] }}</div>
                    <div class="simasten-stat-sub">Ukuran: {{ $stats['presensi_size'] }}</div>
                </div>
            </div>

            <div class="simasten-stat-card">
                <div class="simasten-stat-icon-wrap" style="background-color: #faf5ff; color: #9333ea;">
                    <svg class="simasten-icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path>
                    </svg>
                </div>
                <div>
                    <div class="simasten-stat-val">{{ $stats['spesimen_count'] }}</div>
                    <div class="simasten-stat-sub">Ukuran: {{ $stats['spesimen_size'] }}</div>
                </div>
            </div>

            <div class="simasten-stat-card">
                <div class="simasten-stat-icon-wrap" style="background-color: #f8fafc; color: #64748b;">
                    <svg class="simasten-icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <div class="simasten-stat-val">{{ $stats['other_count'] }}</div>
                    <div class="simasten-stat-lbl">Media Lainnya</div>
                    <div class="simasten-stat-sub">Ukuran: {{ $stats['other_size'] }}</div>
                </div>
            </div>
        </div>

        <!-- 3. TOOLBAR: CATEGORIES, SEARCH, & BULK ACTIONS -->
        <div class="simasten-toolbar">
            <!-- Category Tabs -->
            <div class="simasten-cat-tabs">
                <button type="button"
                    wire:click="setCategory('all')"
                    class="simasten-cat-tab {{ $category === 'all' ? 'active' : '' }}">
                    Semua Foto &amp; Media
                    <span class="simasten-cat-badge">{{ $stats['total_count'] }}</span>
                </button>

                <button type="button"
                    wire:click="setCategory('presensi')"
                    class="simasten-cat-tab {{ $category === 'presensi' ? 'active' : '' }}">
                    Foto Presensi Geotag
                    <span class="simasten-cat-badge">{{ $stats['presensi_count'] }}</span>
                </button>

                <button type="button"
                    wire:click="setCategory('spesimen')"
                    class="simasten-cat-tab {{ $category === 'spesimen' ? 'active' : '' }}">
                    Spesimen &amp; Stempel
                    <span class="simasten-cat-badge">{{ $stats['spesimen_count'] }}</span>
                </button>

                <button type="button"
                    wire:click="setCategory('other')"
                    class="simasten-cat-tab {{ $category === 'other' ? 'active' : '' }}">
                    Media Lainnya
                    <span class="simasten-cat-badge">{{ $stats['other_count'] }}</span>
                </button>
            </div>

            <!-- Filter & Bulk Actions Bar -->
            <div class="simasten-filter-row">
                <!-- Search Input -->
                <div class="simasten-search-box">
                    <svg class="simasten-search-icon simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text"
                        wire:model.live.debounce.300ms="search"
                        placeholder="Cari berdasarkan nama file, nama pegawai, status, atau nomor surat..." />
                </div>

                <!-- Bulk Selection & Action -->
                <div class="simasten-bulk-bar">
                    <label class="inline-flex items-center gap-2 cursor-pointer text-xs font-semibold text-gray-700 dark:text-gray-300">
                        <input type="checkbox"
                            wire:model.live="selectAll"
                            class="rounded border-gray-300 text-primary-600 shadow-sm focus:ring-primary-500" />
                        Pilih Semua ({{ count($items) }})
                    </label>

                    @if(!empty($selectedFiles))
                        <span class="text-xs font-bold text-primary-600 dark:text-primary-400 bg-primary-50 dark:bg-primary-950/40 px-2.5 py-1 rounded-md border border-primary-200 dark:border-primary-800">
                            {{ count($selectedFiles) }} berkas dipilih
                        </span>

                        <button type="button"
                            wire:click="mountAction('deleteSelected')"
                            class="simasten-btn simasten-btn-danger">
                            <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                            Hapus Berkas Terpilih
                        </button>
                    @endif
                </div>
            </div>
        </div>

        <!-- 4. MEDIA ITEMS GRID -->
        @if(count($items) > 0)
            <div class="simasten-media-grid">
                @foreach($items as $item)
                    @php
                        $isSelected = in_array($item['relative_path'], $selectedFiles);
                    @endphp
                    <div class="simasten-media-card {{ $isSelected ? 'selected' : '' }}">
                        <!-- Top Floating Selection Checkbox -->
                        <div class="simasten-card-check">
                            <input type="checkbox"
                                wire:model.live="selectedFiles"
                                value="{{ $item['relative_path'] }}"
                                class="rounded border-gray-300 text-primary-600 focus:ring-primary-500" />
                        </div>

                        <!-- Top Floating Category Badge -->
                        <div class="simasten-card-cat-badge">
                            {{ $item['category_label'] }}
                        </div>

                        <!-- Thumbnail Preview Area -->
                        <div class="simasten-thumb-box">
                            <img src="{{ $item['url'] }}"
                                alt="{{ $item['filename'] }}"
                                class="simasten-thumb-img"
                                loading="lazy"
                                wire:click="openPreview('{{ $item['url'] }}', 'image', '{{ $item['filename'] }}')" />
                        </div>

                        <!-- Card Body Details -->
                        <div class="simasten-card-body">
                            <div class="simasten-card-name" title="{{ $item['filename'] }}">
                                {{ $item['filename'] }}
                            </div>

                            <div class="simasten-card-meta">
                                <span>{{ $item['size_human'] }} • {{ $item['extension'] }}</span>
                                <span>{{ $item['modified_at'] }}</span>
                            </div>

                            <!-- Cross-reference Status Badge -->
                            <div>
                                <span class="simasten-ref-pill simasten-pill-{{ $item['badge_color'] }}" title="{{ $item['ref_text'] }}">
                                    @if($item['badge_color'] === 'info' || $item['badge_color'] === 'warning')
                                        <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                    @elseif($item['badge_color'] === 'success' || $item['badge_color'] === 'primary')
                                        <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @elseif($item['badge_color'] === 'purple')
                                        <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                        </svg>
                                    @else
                                        <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path>
                                        </svg>
                                    @endif
                                    <span class="truncate max-w-[190px]">{{ $item['ref_text'] }}</span>
                                </span>
                            </div>
                        </div>

                        <!-- Card Footer Action Buttons -->
                        <div class="simasten-card-actions">
                            <!-- Preview Button -->
                            <button type="button"
                                class="simasten-action-btn"
                                wire:click="openPreview('{{ $item['url'] }}', 'image', '{{ $item['filename'] }}')"
                                title="Lihat Pratinjau">
                                <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Lihat
                            </button>

                            <!-- Download Button -->
                            <a href="{{ $item['url'] }}"
                                download="{{ $item['filename'] }}"
                                target="_blank"
                                class="simasten-action-btn"
                                title="Unduh Berkas">
                                <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                                </svg>
                                Unduh
                            </a>

                            <!-- Delete Button (Safe deletion with Filament confirmation modal) -->
                            <button type="button"
                                class="simasten-action-btn btn-delete"
                                wire:click="mountAction('deleteMedia', { path: '{{ $item['relative_path'] }}' })"
                                title="Hapus Berkas Aman">
                                <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                                Hapus
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <!-- Empty State -->
            <div class="simasten-empty-card">
                <svg class="simasten-icon-empty" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
                <div style="font-size: 16px; font-weight: 700; color: #0f172a;" class="dark:text-white">Tidak Ada Berkas Media Ditemukan</div>
                <div style="font-size: 13px; margin-top: 6px;">
                    @if(!empty($search))
                        Tidak ditemukan media yang cocok dengan kata kunci "<strong>{{ $search }}</strong>". Silakan coba kata kunci lain.
                    @else
                        Belum ada berkas media tersimpan pada kategori yang dipilih.
                    @endif
                </div>
            </div>
        @endif

        <!-- 5. PREVIEW MODAL LIGHTBOX -->
        @if($previewUrl)
            <div class="simasten-modal-backdrop" wire:click.self="closePreview">
                <div class="simasten-modal-card">
                    <div class="simasten-modal-header">
                        <div class="flex items-center gap-2 font-bold text-sm text-gray-900 dark:text-white truncate">
                            <svg class="simasten-icon-md text-primary-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                            <span class="truncate">{{ $previewTitle }}</span>
                        </div>
                        <button type="button"
                            wire:click="closePreview"
                            class="text-gray-400 hover:text-gray-600 dark:hover:text-white">
                            <svg class="simasten-icon-md" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <div class="simasten-modal-body">
                        @if($previewType === 'image')
                            <img src="{{ $previewUrl }}" alt="{{ $previewTitle }}" />
                        @else
                            <div class="text-white text-center py-8">
                                <p class="mb-4">Pratinjau langsung tidak tersedia untuk format berkas ini.</p>
                                <a href="{{ $previewUrl }}" download class="simasten-btn simasten-btn-primary">
                                    Unduh Berkas
                                </a>
                            </div>
                        @endif
                    </div>

                    <div class="simasten-modal-footer">
                        <a href="{{ $previewUrl }}"
                            download
                            target="_blank"
                            class="simasten-btn simasten-btn-secondary">
                            <svg class="simasten-icon-sm" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Unduh Berkas Asli
                        </a>

                        <button type="button"
                            wire:click="closePreview"
                            class="simasten-btn simasten-btn-secondary">
                            Tutup
                        </button>
                    </div>
                </div>
            </div>
        @endif

    </div>
</x-filament-panels::page>
