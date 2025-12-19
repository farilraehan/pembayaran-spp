@php
use App\Utils\Tanggal;
@endphp
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 20px;
        }

        h2,
        h3 {
            text-align: center;
            margin: 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th,
        td {
            padding: 6px 8px;
            text-align: left;
        }

        th {
            background-color: #f0f0f0;
        }

        .right {
            text-align: right;
        }

        .center {
            text-align: center;
        }

        .no-border {
            border: none;
        }

    </style>
</head>

<body>

    <h2>{{ $nama_lembaga }}</h2>
    <h3>Kwitansi Pembayaran</h3>

    <table class="no-border">
        <tr class="no-border">
            <td class="no-border" width="15%"><strong>Siswa</strong></td>
            <td class="no-border" width="85%">: {{ $header->siswa->nama }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border" width="15%"><strong>Kelas</strong></td>
            <td class="no-border" width="85%">: {{ $header->siswa->kode_kelas }}</td>
        </tr>
        <tr class="no-border">
            <td class="no-border" width="15%"><strong>Tanggal Cetak</strong></td>
            <td class="no-border" width="85%">: {{ Tanggal::tglIndo(now()) }}</td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Keterangan</th>
                <th>Nominal (Rp)</th>
            </tr>
        </thead>
        <tbody>
            @php $total = 0; @endphp
            @foreach($transaksis as $index => $transaksi)
            <tr>
                <td class="center">{{ $index + 1 }}</td>
                <td>{{ Tanggal::tglIndo($transaksi->tanggal) }}</td>
                <td>{{ $transaksi->keterangan ?? '-' }}</td>
                <td class="right">{{ number_format($transaksi->jumlah, 0, ',', '.') }}</td>
            </tr>
            @php $total += $transaksi->jumlah; @endphp
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <th colspan="3" class="left">TOTAL</th>
                <th class="right">{{ number_format($total, 0, ',', '.') }}</th>
            </tr>
        </tfoot>
    </table>

    <p class="center" style="margin-top: 30px;">Terima kasih</p>

</body>

</html>
