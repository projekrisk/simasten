<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Permohonan Perbaikan Presensi - {{ $report->nomor_surat_lengkap ?? 'SIMASTEN' }}</title>
    <style>
        @if(($section ?? '') === 'lampiran')
        @page {
            size: A4 landscape;
            margin: 1.5cm;
        }
        @else
        @page {
            size: A4 portrait;
            margin: 2cm;
        }
        @endif

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 11pt;
            line-height: normal;
            color: #111827;
            margin: 0;
            padding: 0;
        }

        .page-break {
            page-break-before: always;
        }

        .landscape-page {
            page-break-before: always;
            page: landscape-sheet;
            size: A4 landscape;
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

        .tgl-surat {
            text-align: right;
            margin-bottom: 12px;
            font-size: 11pt;
        }

        .meta-table {
            width: 100%;
            margin-bottom: 12px;
            border-collapse: collapse;
            font-size: 11pt;
            line-height: normal;
        }

        .meta-table td {
            vertical-align: top;
            padding: 2px 0;
        }

        .tujuan-surat {
            margin-bottom: 14px;
            font-size: 11pt;
            line-height: normal;
        }

        .isi-surat {
            text-align: justify;
            text-indent: 32px;
            margin-bottom: 10px;
            font-size: 11pt;
            line-height: normal;
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
            text-align: left;
        }

        .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        .ttd-nip {
            font-size: 11pt;
            color: #1f2937;
        }

        .lampiran-header {
            text-align: left;
            margin-bottom: 2px;
        }

        .lampiran-title {
            font-size: 11pt;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-bottom: 2px;
        }

        .lampiran-meta-table {
            border-collapse: collapse;
            font-size: 11pt;
            line-height: 1.15;
            margin-top: 1px;
            margin-bottom: 1px;
        }

        .lampiran-meta-table td {
            padding: 0;
            vertical-align: top;
            border: none !important;
            height: auto !important;
        }

        .lampiran-header-next {
            font-size: 11pt;
            margin-bottom: 4px;
            color: #1f2937;
            text-align: left;
            font-weight: bold;
        }

        .lampiran-subtitle {
            font-size: 11pt;
            margin-top: 2px;
        }

        .lampiran-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 10pt;
            margin-top: 4px;
            margin-bottom: 4px;
        }

        .lampiran-table th {
            background-color: #f3f4f6;
            color: #111827;
            font-weight: bold;
            border: 1px solid #374151;
            padding: 3px 2px;
            text-align: center;
            font-size: 10pt;
        }

        .lampiran-table tr {
            height: 3.50cm;
        }

        .lampiran-table td {
            border: 1px solid #4b5563;
            padding: 2px 2px;
            vertical-align: middle;
            font-size: 10pt;
            height: 3.50cm;
        }

        .employee-cell {
            padding-left: 8px !important;
            padding-right: 8px !important;
            text-align: left;
        }

        .text-center {
            text-align: center;
        }

        .arrow-icon {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 10pt;
            line-height: 1;
            color: #4b5563;
        }

        .badge-status {
            display: inline-block;
            padding: 2px 5px;
            font-weight: bold;
            font-size: 9.5pt;
            border-radius: 3px;
        }

        .badge-tm {
            background-color: #fee2e2;
            color: #b91c1c;
            border: 1px solid #f87171;
        }

        .badge-hd {
            background-color: #fef3c7;
            color: #b45309;
            border: 1px solid #fcd34d;
        }

        .lampiran-footer-row {
            margin-top: 10px;
            width: 100%;
            page-break-inside: avoid;
        }

        .footer-keterangan {
            float: left;
            width: 580px;
            font-size: 11pt;
        }

        .keterangan-title {
            font-weight: bold;
            margin-bottom: 3px;
            color: #111827;
        }

        .keterangan-table {
            border-collapse: collapse;
            font-size: 11pt;
            line-height: 1.25;
        }

        .keterangan-table td {
            border: none !important;
            padding: 1px 0;
            vertical-align: middle;
            height: auto !important;
        }

        .keterangan-badge-cell {
            width: 36px;
            text-align: left;
        }

        .keterangan-desc-cell {
            color: #1f2937;
            padding-left: 4px !important;
        }

        .photo-cell {
            width: 2.8cm;
            text-align: center;
            vertical-align: middle;
            padding: 2px 2px !important;
        }

        .evidence-photo {
            width: 2.33cm;
            height: 3.10cm;
            object-fit: cover;
            border: 1px solid #9ca3af;
            border-radius: 2px;
            background-color: #f9fafb;
            display: block;
            margin: 0 auto;
        }

        .photo-placeholder {
            width: 2.33cm;
            height: 3.10cm;
            border: 1px dashed #9ca3af;
            font-size: 8.5pt;
            color: #6b7280;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            background-color: #f3f4f6;
            line-height: 1.1;
            padding: 2px;
            box-sizing: border-box;
            margin: 0 auto;
        }

        .footer-ttd {
            float: right;
            width: 280px;
            text-align: left;
            position: relative;
            font-size: 11pt;
        }

        .footer-ttd .ttd-canvas {
            position: relative;
            height: 105px;
            width: 100%;
        }

        .footer-ttd .img-stempel {
            position: absolute;
            left: -15px;
            top: 5px;
            width: 110px;
            height: 110px;
            opacity: 0.85;
            z-index: 1;
        }

        .footer-ttd .img-ttd {
            position: absolute;
            left: 35px;
            top: 15px;
            width: 135px;
            height: auto;
            z-index: 2;
        }

        .footer-ttd .ttd-manual-space {
            height: 90px;
        }

        .footer-ttd .ttd-tte-space {
            padding-top: 54px;
            padding-bottom: 48px;
            text-align: left;
            width: 100%;
        }

        .footer-ttd .ttd-tte-text {
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
            text-align: left;
        }

        .footer-ttd .ttd-nama {
            font-weight: bold;
            text-decoration: underline;
            font-size: 11pt;
        }

        .footer-ttd .ttd-nip {
            font-size: 11pt;
            color: #1f2937;
        }

        .draft-watermark {
            position: fixed;
            top: 35%;
            left: 20%;
            transform: rotate(-35deg);
            font-size: 72pt;
            font-weight: bold;
            color: rgba(220, 38, 38, 0.12);
            z-index: 999;
            pointer-events: none;
            letter-spacing: 8px;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

    @if($isDraft ?? false)
        <div class="draft-watermark">DRAF / SALINAN</div>
    @endif

    @if(!isset($section) || $section === 'surat')
    <div class="portrait-page">
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

        <div class="tgl-surat">
            Malingping, {{ $report->tanggal_surat ? \Carbon\Carbon::parse($report->tanggal_surat)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}
        </div>

        <table class="meta-table">
            <tr>
                <td style="width: 80px;">Nomor</td>
                <td style="width: 15px;">:</td>
                <td style="font-weight: bold;">
                    {{ $report->nomor_surat_lengkap ?: '800/' . ($report->nomor_surat ?: '...') . '/SMA.1-MLP/' . $report->tahun }}
                </td>
            </tr>
            <tr>
                <td>Sifat</td>
                <td>:</td>
                <td>Penting</td>
            </tr>
            <tr>
                <td>Lampiran</td>
                <td>:</td>
                <td>1 (Satu) Berkas Rekapitulasi</td>
            </tr>
            <tr>
                <td>Perihal</td>
                <td>:</td>
                <td style="font-weight: bold; text-decoration: underline;">
                    Permohonan Perbaikan Presensi SIMASTEN Periode {{ $report->nama_bulan }} {{ $report->tahun }}
                </td>
            </tr>
        </table>

        <div class="tujuan-surat">
            Kepada Yth.<br />
            <strong>Kepala Cabang Dinas Pendidikan dan Kebudayaan</strong><br />
            Wilayah Kabupaten Lebak<br />
            di -<br />
            &nbsp;&nbsp;&nbsp;&nbsp;Tempat
        </div><br />

        <div class="isi-surat">
            Dengan hormat,
        </div>
        <div class="isi-surat">
            Sehubungan dengan adanya kendala teknis pada sistem aplikasi presensi mobile SIMASTEN (antara lain berupa fluktuasi koneksi jaringan seluler, galat sinkronisasi server sistem presensi terpusat, serta kendala akurasi pembacaan titik koordinat/geotagging) yang dialami oleh beberapa Pendidik dan Tenaga Kependidikan di SMA Negeri 1 Malingping pada periode bulan <strong>{{ $report->nama_bulan }} {{ $report->tahun }}</strong>, dengan ini kami mengajukan permohonan penyesuaian dan perbaikan data presensi sebagaimana rincian terlampir.
        </div>
        <div class="isi-surat">
            Sebagai bahan pertimbangan dan bukti otentik kedisiplinan kerja, bersama ini kami lampirkan rekapitulasi daftar ajuan kendala presensi yang telah diverifikasi secara cermat oleh pihak sekolah, lengkap dengan bukti foto selfie geotagging di lokasi satuan pendidikan pada waktu kedatangan maupun kepulangan pegawai yang bersangkutan.
        </div>
        <div class="isi-surat">
            Demikian surat permohonan pengantar ini kami sampaikan dengan sebenarnya, atas perhatian, kebijaksanaan, dan perkenan Bapak/Ibu Kepala Cabang Dinas, kami haturkan terima kasih.
        </div>

        <div class="ttd-wrapper">
            <div class="ttd-box">
                <div>Kepala SMA Negeri 1 Malingping,</div>

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
    </div>
    @endif

    @if(!isset($section) || $section === 'lampiran')
    <div class="landscape-page" @if(($section ?? '') === 'lampiran') style="page-break-before: avoid;" @endif>
        @if($isDraft ?? false)
            <div class="draft-watermark">DRAF / SALINAN</div>
        @endif

        @php
            $corrections = $report->corrections->sortBy([
                ['tanggal', 'asc'],
                ['employee.nama', 'asc'],
            ])->values();
            $chunks = $corrections->chunk(4);
            $lastChunkCount = $chunks->isEmpty() ? 0 : $chunks->last()->count();
            $hasSeparateSignatureSheet = ($lastChunkCount >= 3);
            $totalSheets = $chunks->isEmpty() ? 1 : ($chunks->count() + ($hasSeparateSignatureSheet ? 1 : 0));
            $globalIndex = 0;
        @endphp

        @forelse($chunks as $chunkIndex => $chunkItems)
            @if($chunkIndex > 0)
                <div class="page-break"></div>
            @endif

            @if($chunkIndex === 0)
                <div class="lampiran-header">
                    <div class="lampiran-title">LAMPIRAN REKAPITULASI AJUAN PERBAIKAN PRESENSI PEGAWAI</div>
                    <table class="lampiran-meta-table">
                        <tr>
                            <td style="width: 95px;">Nomor Surat</td>
                            <td style="width: 15px; text-align: center;">:</td>
                            <td><strong>{{ $report->nomor_surat_lengkap ?: '800/' . ($report->nomor_surat ?: '...') . '/SMA.1-MLP/' . $report->tahun }}</strong></td>
                        </tr>
                        <tr>
                            <td>Periode</td>
                            <td style="text-align: center;">:</td>
                            <td><strong>{{ $report->nama_bulan }} {{ $report->tahun }}</strong></td>
                        </tr>
                        <tr>
                            <td>Halaman</td>
                            <td style="text-align: center;">:</td>
                            <td>Lembar 1 dari {{ $totalSheets }}</td>
                        </tr>
                    </table>
                </div>
            @else
                <div class="lampiran-header-next">
                    Lembar {{ $chunkIndex + 1 }} dari {{ $totalSheets }}
                </div>
            @endif

            <table class="lampiran-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 210px;">Nama Pegawai &amp; NIP</th>
                        <th style="width: 65px;">Tanggal</th>
                        <th style="width: 45px;">Status</th>
                        <th style="width: 80px;">Jam Datang<br><span style="font-weight: normal;">(Asli <span class="arrow-icon">&rarr;</span> Ubah)</span></th>
                        <th style="width: 80px;">Jam Pulang<br><span style="font-weight: normal;">(Asli <span class="arrow-icon">&rarr;</span> Ubah)</span></th>
                        <th style="width: 2.8cm;">Bukti Foto<br>Datang</th>
                        <th style="width: 2.8cm;">Bukti Foto<br>Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($chunkItems as $itemIndex => $item)
                        @php
                            $globalIndex++;
                        @endphp
                        <tr>
                            <td class="text-center">{{ $globalIndex }}</td>
                            <td class="employee-cell">
                                <strong>{{ $item->employee->nama ?? '-' }}</strong><br />
                                <span style="color: #374151;">
                                    NIP. {{ $item->employee->nip ?? '-' }}
                                </span>
                            </td>
                            <td class="text-center">
                                {{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d/m/Y') : '-' }}
                            </td>
                            <td class="text-center">
                                @if($item->status_presensi === 'TM')
                                    <span class="badge-status badge-tm">TM</span>
                                @else
                                    <span class="badge-status badge-hd">HD</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <span style="color: #6b7280;">{{ $item->jam_datang_asli ? substr($item->jam_datang_asli, 0, 5) : '-' }}</span>
                                <br /><span class="arrow-icon">&darr;</span><br />
                                <strong style="color: #1e40af;">{{ $item->jam_datang_ubah ? substr($item->jam_datang_ubah, 0, 5) : '-' }}</strong>
                            </td>
                            <td class="text-center">
                                <span style="color: #6b7280;">{{ $item->jam_pulang_asli ? substr($item->jam_pulang_asli, 0, 5) : '-' }}</span>
                                <br /><span class="arrow-icon">&darr;</span><br />
                                <strong style="color: #1e40af;">{{ $item->jam_pulang_ubah ? substr($item->jam_pulang_ubah, 0, 5) : '-' }}</strong>
                            </td>
                            <td class="photo-cell">
                                @if(!empty($item->foto_datang) && file_exists(public_path('uploads/' . $item->foto_datang)))
                                    <img src="{{ public_path('uploads/' . $item->foto_datang) }}" class="evidence-photo" alt="Selfie Datang" />
                                @else
                                    <div class="photo-placeholder">
                                        Foto Datang Geotag
                                    </div>
                                @endif
                            </td>
                            <td class="photo-cell">
                                @if(!empty($item->foto_pulang) && file_exists(public_path('uploads/' . $item->foto_pulang)))
                                    <img src="{{ public_path('uploads/' . $item->foto_pulang) }}" class="evidence-photo" alt="Selfie Pulang" />
                                @else
                                    <div class="photo-placeholder">
                                        Foto Pulang Geotag
                                    </div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if($loop->last)
                @if($hasSeparateSignatureSheet)
                    <div class="page-break"></div>
                    <div class="lampiran-header-next" style="margin-bottom: 15px;">
                        Lembar {{ $totalSheets }} dari {{ $totalSheets }}
                    </div>
                @endif
                <div class="lampiran-footer-row">
                    <div class="footer-keterangan">
                        <div class="keterangan-title">Keterangan:</div>
                        <table class="keterangan-table">
                            <tr>
                                <td class="keterangan-badge-cell"><span class="badge-status badge-tm">TM</span></td>
                                <td style="width: 14px; text-align: center;">:</td>
                                <td class="keterangan-desc-cell">Tidak Absen Sama Sekali (Hadir tapi lupa/gagal absen datang &amp; pulang)</td>
                            </tr>
                            <tr>
                                <td class="keterangan-badge-cell"><span class="badge-status badge-hd">HD</span></td>
                                <td style="text-align: center;">:</td>
                                <td class="keterangan-desc-cell">Hadir (Melakukan absen datang, namun tidak melakukan absen pulang)</td>
                            </tr>
                        </table>
                    </div>

                    <div class="footer-ttd">
                        <div>Malingping, {{ $report->tanggal_surat ? \Carbon\Carbon::parse($report->tanggal_surat)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                        <div>Kepala SMA Negeri 1 Malingping,</div>

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
            @endif

        @empty
            <div class="lampiran-header">
                <div class="lampiran-title">LAMPIRAN REKAPITULASI AJUAN PERBAIKAN PRESENSI PEGAWAI</div>
                <table class="lampiran-meta-table">
                    <tr>
                        <td style="width: 95px;">Nomor Surat</td>
                        <td style="width: 15px; text-align: center;">:</td>
                        <td><strong>{{ $report->nomor_surat_lengkap ?: '800/' . ($report->nomor_surat ?: '...') . '/SMA.1-MLP/' . $report->tahun }}</strong></td>
                    </tr>
                    <tr>
                        <td>Periode</td>
                        <td style="text-align: center;">:</td>
                        <td><strong>{{ $report->nama_bulan }} {{ $report->tahun }}</strong></td>
                    </tr>
                    <tr>
                        <td>Halaman</td>
                        <td style="text-align: center;">:</td>
                        <td>Lembar 1 dari 1</td>
                    </tr>
                </table>
            </div>

            <table class="lampiran-table">
                <thead>
                    <tr>
                        <th style="width: 25px;">No</th>
                        <th style="width: 210px;">Nama Pegawai &amp; NIP</th>
                        <th style="width: 65px;">Tanggal</th>
                        <th style="width: 45px;">Status</th>
                        <th style="width: 80px;">Jam Datang<br><span style="font-weight: normal;">(Asli <span class="arrow-icon">&rarr;</span> Ubah)</span></th>
                        <th style="width: 80px;">Jam Pulang<br><span style="font-weight: normal;">(Asli <span class="arrow-icon">&rarr;</span> Ubah)</span></th>
                        <th style="width: 2.8cm;">Bukti Foto<br>Datang</th>
                        <th style="width: 2.8cm;">Bukti Foto<br>Pulang</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td colspan="8" class="text-center" style="padding: 15px; color: #6b7280;">
                            Tidak ada data rincian kendala pegawai yang terdaftar pada laporan ini.
                        </td>
                    </tr>
                </tbody>
            </table>

            <div class="lampiran-footer-row">
                <div class="footer-keterangan">
                    <div class="keterangan-title">Keterangan:</div>
                    <table class="keterangan-table">
                        <tr>
                            <td class="keterangan-badge-cell"><span class="badge-status badge-tm">TM</span></td>
                            <td style="width: 14px; text-align: center;">:</td>
                            <td class="keterangan-desc-cell">Tidak Absen Sama Sekali (Hadir tapi lupa/gagal absen datang &amp; pulang)</td>
                        </tr>
                        <tr>
                            <td class="keterangan-badge-cell"><span class="badge-status badge-hd">HD</span></td>
                            <td style="text-align: center;">:</td>
                            <td class="keterangan-desc-cell">Hadir (Melakukan absen datang, namun tidak melakukan absen pulang)</td>
                        </tr>
                    </table>
                </div>

                <div class="footer-ttd">
                    <div>Malingping, {{ $report->tanggal_surat ? \Carbon\Carbon::parse($report->tanggal_surat)->translatedFormat('d F Y') : \Carbon\Carbon::now()->translatedFormat('d F Y') }}</div>
                    <div>Kepala SMA Negeri 1 Malingping,</div>

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
        @endforelse
    </div>
    @endif

</body>
</html>
