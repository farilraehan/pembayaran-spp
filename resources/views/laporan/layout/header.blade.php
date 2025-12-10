<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <style>
        * {
            font-family: 'Arial', sans-serif;
        }

        .footer-line {
            border-top: 1px solid #7e7c7c;
            margin-top: 15px;
            width: 100%;
        }
    </style>
</head>

<body>
    <table width="100%">
        <tr>
            <td width="70">

                @if (!empty($logo))
                    <img src="data:image/{{ $logo_type }};base64,{{ $logo }}" height="70">
                @endif
            </td>
            <td align="left">
                <div><b>PEMBAYARAN SPP</b></div>
                <div style="font-size: 10px; color: grey;"><i>alamat,</i></div>
            </td>
        </tr>
    </table>

    <!-- Garis di bawah -->
    <div class="footer-line"></div>
    <div style="height:20px;"></div>
</body>

</html>
