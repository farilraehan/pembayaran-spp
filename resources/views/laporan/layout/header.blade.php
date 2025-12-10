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
    <table width="100%" cellpadding="0" cellspacing="0" style="border-collapse:collapse;">
        <tr>
            <td style="width:0.1%; padding:0; vertical-align:middle;">
                @if (!empty($logo))
                    <img src="data:image/{{ $logo_type }};base64,{{ $logo }}" height="100"
                        style="display:block; margin:0;">
                @endif
            </td>
            <td style="padding:0; margin:0; vertical-align:middle;">
                <div style="font-weight:bold;font-size:18px;line-height:1;margin:0 0 0 -12px;">
                    SABIT<br>PEMBAYARAN SPP
                </div>
                <div style="font-size:10px;color:grey; line-height:1;margin:0 0 0 -12px;">
                    <i>alamat</i>
                </div>
            </td>
        </tr>
    </table>



    <!-- Garis di bawah -->
    <div class="footer-line"></div>
    <div style="height:20px;"></div>
</body>

</html>
