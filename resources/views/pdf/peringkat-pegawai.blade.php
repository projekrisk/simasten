<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peringkat Pegawai Pengajuan Perbaikan Presensi - Tahun {{ $year }}</title>
    <style>
        @page {
            size: A4 portrait;
            margin: 2cm;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: normal;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .kop-table {
            width: 100%;
            border-collapse: collapse;
            padding-bottom: 4px;
            margin-bottom: 0;
        }

        .kop-separator {
            width: 100%;
            margin-top: 3px;
            margin-bottom: 14px;
        }

        .kop-line-thin {
            border-top: 1px solid #000000;
            height: 0;
            line-height: 0;
            margin-bottom: 2px;
        }

        .kop-line-thick {
            border-top: 3px solid #000000;
            height: 0;
            line-height: 0;
        }

        .kop-logo {
            width: 95px;
            text-align: center;
            vertical-align: middle;
        }

        .kop-logo img {
            width: 95px;
            height: auto;
        }

        .kop-text {
            text-align: center;
            vertical-align: middle;
        }

        .kop-instansi-1 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-instansi-2 {
            font-size: 14pt;
            font-weight: bold;
            text-transform: uppercase;
        }

        .kop-sekolah {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 22pt;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 1px;
        }

        .kop-npsn {
            font-size: 11pt;
            font-weight: bold;
            margin-top: 1px;
        }

        .kop-alamat {
            font-size: 10pt;
            margin-top: 1px;
        }

        .kop-kontak {
            font-size: 10pt;
            color: #1f2937;
            text-align: center;
            padding-top: 3px;
        }

        .report-header {
            text-align: center;
            margin-top: 6px;
            margin-bottom: 16px;
        }

        .report-title {
            font-size: 12pt;
            font-weight: bold;
            text-transform: uppercase;
            text-decoration: underline;
            color: #000000;
            letter-spacing: 0.5px;
        }

        .report-subtitle {
            font-size: 10pt;
            color: #374151;
            margin-top: 4px;
        }

        .data-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-top: 8px;
            margin-bottom: 20px;
        }

        .data-table th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            border: 1px solid #374151;
            padding: 7px 6px;
            text-align: center;
            vertical-align: middle;
            font-size: 10pt;
        }

        .data-table td {
            border: 1px solid #4b5563;
            padding: 6px 8px;
            vertical-align: middle;
            font-size: 10pt;
        }

        .text-center {
            text-align: center;
        }

        .text-right {
            text-align: right;
        }

        .meta-desc {
            font-size: 9pt;
            color: #4b5563;
            margin-top: 2px;
            font-family: Arial, Helvetica, sans-serif;
        }

        .ttd-wrapper {
            margin-top: 25px;
            width: 100%;
        }

        .ttd-box {
            float: right;
            width: 280px;
            text-align: left;
            position: relative;
            font-size: 11pt;
        }

        .ttd-canvas {
            position: relative;
            height: 105px;
            width: 100%;
        }

        .img-stempel {
            position: absolute;
            left: -15px;
            top: 5px;
            width: 110px;
            height: 110px;
            opacity: 0.85;
            z-index: 1;
        }

        .img-ttd {
            position: absolute;
            left: 35px;
            top: 15px;
            width: 135px;
            height: auto;
            z-index: 2;
        }

        .ttd-manual-space {
            height: 90px;
        }

        .ttd-tte-space {
            padding-top: 54px;
            padding-bottom: 48px;
            text-align: left;
            width: 100%;
        }

        .ttd-tte-text {
            display: block;
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            color: #111827;
            font-weight: bold;
            line-height: normal;
            background: none;
            border: none;
            padding: 0;
            margin: 0;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
        }

        .ttd-nip {
            font-size: 11pt;
            color: #111827;
            font-family: Arial, Helvetica, sans-serif;
        }
    </style>
</head>
<body>

    <table class="kop-table">
        <tr>
            <td class="kop-logo">
                @if(file_exists(public_path('uploads/logo/kiri.png')))
                    <img src="{{ public_path('uploads/logo/kiri.png') }}" alt="Logo Kiri" />
                @elseif(file_exists(public_path('images/logo_banten.png')))
                    <img src="{{ public_path('images/logo_banten.png') }}" alt="Logo Banten" />
                @endif
            </td>
            <td class="kop-text">
                <div class="kop-instansi-1">PEMERINTAH PROVINSI BANTEN</div>
                <div class="kop-instansi-2">DINAS PENDIDIKAN DAN KEBUDAYAAN</div>
                <div class="kop-sekolah">SMAN 1 MALINGPING</div>
                <div class="kop-npsn">NPSN: 20601875 - AKREDITASI: A (96)</div>
                <div class="kop-alamat">Jl. Raya Bayah KM. 4 No. 39 Malingping – Lebak, 42391</div>
            </td>
            <td class="kop-logo">
                @if(file_exists(public_path('uploads/logo/kanan.png')))
                    <img src="{{ public_path('uploads/logo/kanan.png') }}" alt="Logo Kanan" />
                @elseif(file_exists(public_path('images/logo_tutwuri.png')))
                    <img src="{{ public_path('images/logo_tutwuri.png') }}" alt="Logo Tutwuri" />
                @endif
            </td>
        </tr>
        <tr>
            <td colspan="3" class="kop-kontak">
                Website: https://sman1malingping.sch.id – Email: info@sman1malingping.sch.id
            </td>
        </tr>
    </table>

    <div class="kop-separator">
        <div class="kop-line-thin"></div>
        <div class="kop-line-thick"></div>
    </div>

    <div class="report-header">
        <div class="report-title">DAFTAR PERINGKAT PEGAWAI PALING SERING<br>MENGAJUKAN PERBAIKAN PRESENSI</div>
        <div class="report-subtitle">Periode Rekapitulasi: Tahun Kalender {{ $year }}</div>
    </div>

    @php
        $totalTmAll = collect($employees)->sum('tm_count');
        $totalHdAll = collect($employees)->sum('hd_count');
        $totalAjuanAll = collect($employees)->sum('corrections_count');
    @endphp

    <table class="data-table">
        <thead>
            <tr>
                <th style="width: 40px;">No</th>
                <th>Nama Pegawai &amp; NIP</th>
                <th style="width: 85px;">Kasus TM</th>
                <th style="width: 85px;">Kasus HD</th>
                <th style="width: 105px;">Total Ajuan</th>
            </tr>
        </thead>
        <tbody>
            @forelse($employees as $index => $emp)
                <tr>
                    <td class="text-center font-bold">
                        {{ $index + 1 }}
                    </td>
                    <td>
                        <strong>{{ $emp['nama'] }}</strong>
                        <div class="meta-desc">
                            {{ !empty($emp['nip']) ? 'NIP. ' . $emp['nip'] : 'Non-PNS / Tenaga Honorer' }}
                        </div>
                    </td>
                    <td class="text-center font-medium">
                        {{ $emp['tm_count'] }}
                    </td>
                    <td class="text-center font-medium">
                        {{ $emp['hd_count'] }}
                    </td>
                    <td class="text-center" style="font-weight: bold;">
                        {{ $emp['corrections_count'] }} Kali
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center" style="padding: 24px; color: #64748b; font-style: italic;">
                        Tidak ada catatan pengajuan perbaikan presensi pegawai pada tahun {{ $year }}.
                    </td>
                </tr>
            @endforelse
        </tbody>
        @if(!empty($employees) && count($employees) > 0)
            <tfoot>
                <tr>
                    <th colspan="2" style="text-align: right; padding-right: 12px; background-color: #f3f4f6;">
                        TOTAL KESELURUHAN:
                    </th>
                    <th class="text-center" style="background-color: #f3f4f6;">
                        {{ $totalTmAll }}
                    </th>
                    <th class="text-center" style="background-color: #f3f4f6;">
                        {{ $totalHdAll }}
                    </th>
                    <th class="text-center" style="background-color: #f3f4f6;">
                        {{ $totalAjuanAll }} Kali
                    </th>
                </tr>
            </tfoot>
        @endif
    </table>

    <div class="ttd-wrapper">
        <div class="ttd-box">
            <div>Malingping, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
            <div style="margin-top: 4px;">Kepala SMA Negeri 1 Malingping,</div>

            @if(!empty($setting->gunakan_tte) && !empty($setting->teks_tte))
                <div class="ttd-tte-space">
                    <span class="ttd-tte-text">{{ $setting->teks_tte }}</span>
                </div>
            @elseif(!empty($setting->gunakan_spesimen) && !empty($setting->spesimen_ttd) && file_exists(public_path('uploads/' . $setting->spesimen_ttd)))
                <div class="ttd-canvas">
                    @if(!empty($setting->spesimen_stempel) && file_exists(public_path('uploads/' . $setting->spesimen_stempel)))
                        <img src="{{ public_path('uploads/' . $setting->spesimen_stempel) }}" class="img-stempel" alt="Stempel Dinas" />
                    @endif
                    <img src="{{ public_path('uploads/' . $setting->spesimen_ttd) }}" class="img-ttd" alt="Tanda Tangan" />
                </div>
            @else
                <div class="ttd-manual-space"></div>
            @endif

            <div class="ttd-nama">{{ $setting->nama_kepala_sekolah ?? 'Drs. H. Ahmad Sudrajat, M.Pd.' }}</div>
            <div class="ttd-nip">NIP. {{ $setting->nip_kepala_sekolah ?? '196805121994031005' }}</div>
        </div>
        <div style="clear: both;"></div>
    </div>

</body>
</html>
