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
            font-family: "Courier New", monospace;
            font-size: 12px;
        }

        .struk {
            width: 650px;
            margin: 10px auto;
            padding: 15px 20px;
            border: 1px solid #000;
            box-sizing: border-box;
        }

        .center {
            text-align: center;
        }

        .right {
            text-align: right;
        }

        .line {
            border-top: 1px dashed #000;
            margin: 6px 0;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 3px 0;
        }

    </style>
</head>
<body>
    <div class="struk">
        <br>
        <div class="center">
            <strong>{{ $nama_lembaga }}</strong><br><br>
            <strong>KWITANSI</strong>
        </div>
        <br>
        <div class="line"></div>
        <div class="line"></div>
        <br>
        <table>
            <tr>
                <td>Siswa</td>
                <td>: {{ $header->siswa->nama }}</td>
            </tr>
            <tr>
                <td>Kelas</td>
                <td>: {{ $header->siswa->kode_kelas }}</td>
            </tr>
            <tr>
                <td>Tanggal</td>
                <td>: {{ Tanggal::tglIndo($header->tanggal) }}</td>
            </tr>
        </table>
        <br>
        <div class="line"></div>
        <br>
        <table>
            @php $total = 0; @endphp
            @foreach ($spps as $spp)
            @php $total += $spp->nominal; @endphp
            <tr>
                <td>{{ Tanggal::namabulan($spp->tanggal) }}</td>
                <td class="right">{{ number_format($spp->nominal,0,',','.') }}</td>
            </tr>
            @endforeach
        </table>
        <br>
        <div class="line"></div>
        <table>
            <tr>
                <td><strong>TOTAL</strong></td>
                <td class="right"><strong>{{ number_format($total,0,',','.') }}</strong></td>
            </tr>
        </table>
        <div class="line"></div>
        <div class="center">
            Terima kasih
        </div>
    </div>
</body>
</html>
