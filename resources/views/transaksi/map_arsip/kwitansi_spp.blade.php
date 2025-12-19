@php
    use App\Utils\Tanggal;
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 14px; }
        .struk-container {
            width: 650px;
            margin: 20px auto;
            padding: 15px;
            border: 1px solid #000;
        }
        .header { text-align: center; margin-bottom: 10px; }
        .line { border-top: 1px dashed #000; margin: 10px 0; }
        table { width: 100%; }
        td { padding: 4px 0; }
        .total { text-align: right; font-weight: bold; }
        .page-break { page-break-after: always; }
    </style>
</head>
<body>

@foreach ($transaksis as $transaksi)
<div class="struk-container">
    <div class="header">
        <h3>SDN Contoh 01</h3>
        <p>Kwitansi Pembayaran SPP</p>
    </div>

    <table>
        <tr>
            <td>Nama Siswa</td>
            <td>: {{ $transaksi->siswa->nama }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>: {{ $transaksi->kelas }}</td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:{{ Tanggal::tglIndo($transaksi->tanggal) }}</td>
        </tr>
    </table>

    <div class="line"></div>

    <table>
        @php $total = 0; @endphp
        @foreach ($transaksi->spps as $spp)
            @php $total += $spp->nominal; @endphp
            <tr>
                <td>{{ $spp->bulan }}</td>
                <td style="text-align:right">
                    Rp {{ number_format($spp->nominal,0,',','.') }}
                </td>
            </tr>
        @endforeach
    </table>

    <div class="line"></div>

    <div class="total">
        Total: Rp {{ number_format($total,0,',','.') }}
    </div>

    <div style="text-align:center;margin-top:10px">
        Terima kasih atas pembayaran Anda
    </div>
</div>

@if (!$loop->last)
    <div class="page-break"></div>
@endif
@endforeach

</body>
</html>
