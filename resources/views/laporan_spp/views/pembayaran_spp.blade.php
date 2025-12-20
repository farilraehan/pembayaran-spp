<title>{{ $title }}</title>
@extends('laporan.layout.base')

@section('content')
    <table width="100%" cellspacing="0" cellpadding="0" style="margin-bottom:15px;">
        <tr>
            <td align="center" style="padding:0;">
                <div style="font-size:20px; font-weight:bold; margin:0; padding:0; line-height:1.1;">
                    {{ $title }}
                    @if (!empty($kelas))
                        Kelas {{ $kelas->kode_kelas }}
                    @endif
                </div>

                <div style="font-size:16px; font-weight:bold; margin:2px 0 0 0; padding:0; line-height:1.1;">
                    Periode
                    {{ $periode['awal']->translatedFormat('d F Y') }}
                    s.d.
                    {{ $periode['akhir']->translatedFormat('d F Y') }}
                </div>
            </td>

        </tr>
    </table>

    <table width="100%" cellpadding="4" cellspacing="0" style="border-collapse:collapse; font-size:11px;">

        <thead>
            <tr style="text-align:center; font-weight:bold;">
                <th rowspan="2" style="border:1px solid #000; width:4%;">No</th>
                <th rowspan="2" style="border:1px solid #000; width:10%;">NISN</th>
                <th rowspan="2" style="border:1px solid #000; width:20%;">Nama Siswa</th>

                <th colspan="2" style="border:1px solid #000; width:16%;">
                    Target Pembayaran
                </th>

                <th colspan="3" style="border:1px solid #000; width:30%;">
                    Realisasi Pembayaran
                </th>

                <th rowspan="2" style="border:1px solid #000; width:20%;">Keterangan</th>
            </tr>

            <tr style="text-align:center; font-weight:bold;">
                <th style="border:1px solid #000; width:12%;">per Bulan</th>
                <th style="border:1px solid #000; width:12%;">s.d. Saat Ini</th>

                <th style="border:1px solid #000; width:12%;">s.d. Periode Lalu</th>
                <th style="border:1px solid #000; width:12%;">Periode Ini</th>
                <th style="border:1px solid #000; width:12%;">s.d. Periode Ini</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anggotaKelas as $i => $row)
                <tr>
                    <td style="border:1px solid #000; text-align:center;">{{ $i + 1 }}</td>

                    <td style="border:1px solid #000; text-align:center;">
                        {{ $row->getSiswa->nisn ?? '-' }}
                    </td>

                    <td style="border:1px solid #000;">
                        {{ $row->getSiswa->nama ?? '-' }}
                    </td>

                    {{-- Target per bulan --}}
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($row->per_bulan, 2, ',', '.') }}
                    </td>

                    {{-- Target s.d saat ini --}}
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($row->target_sd_saat_ini, 2, ',', '.') }}
                    </td>

                    {{-- Realisasi s.d periode lalu --}}
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($row->sd_periode_lalu, 2, ',', '.') }}
                    </td>

                    {{-- Realisasi periode ini --}}
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($row->periode_ini, 2, ',', '.') }}
                    </td>

                    {{-- Realisasi s.d periode ini --}}
                    <td style="border:1px solid #000; text-align:right;">
                        {{ number_format($row->sd_periode_ini, 2, ',', '.') }}
                    </td>

                    {{-- Keterangan --}}
                    <td
                        style="border:1px solid #000; text-align:center;
    color: {{ $row->sisa > 0 ? 'red' : 'black' }};">
                        @if ($row->sisa > 0)
                            ({{ number_format($row->sisa, 2, ',', '.') }})
                        @else
                            Lunas
                        @endif
                    </td>

                </tr>
            @empty
                <tr>
                    <td colspan="9" style="border:1px solid #000; text-align:center; font-style:italic;">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
@endsection
