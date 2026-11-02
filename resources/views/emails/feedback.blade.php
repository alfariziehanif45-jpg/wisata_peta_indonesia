<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan / Saran Baru</title>
</head>
<body style="margin:0; padding:0; background:#f4f5fa; font-family: Arial, sans-serif; color:#0f172a;">
    <div style="max-width:620px; margin:24px auto; background:#fff; border-radius:16px; overflow:hidden; box-shadow:0 8px 24px rgba(15,23,42,.08);">

        <div style="background:linear-gradient(135deg,#e11d48,#fb923c); padding:22px 26px; color:#fff;">
            <h1 style="margin:0; font-size:20px;">📮 {{ $typeLabel }} Baru</h1>
            <p style="margin:4px 0 0; font-size:13px; opacity:.9;">Jelajah Wisata Indonesia</p>
        </div>

        <div style="padding:24px 26px;">
            <table style="width:100%; border-collapse:collapse; font-size:14px;">
                <tr>
                    <td style="padding:6px 0; color:#64748b; width:110px;">Jenis</td>
                    <td style="padding:6px 0; font-weight:700;">{{ $typeLabel }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#64748b;">Subjek</td>
                    <td style="padding:6px 0; font-weight:700;">{{ $payload['subject'] }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#64748b;">Waktu</td>
                    <td style="padding:6px 0;">{{ $payload['sent_at'] }}</td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#64748b;">Pengguna</td>
                    <td style="padding:6px 0;">
                        {{ $payload['user']['name'] }}
                        @if(!empty($payload['user']['username']))
                            &nbsp;<span style="color:#64748b;">(@ {{ $payload['user']['username'] }})</span>
                        @endif
                        @if(!empty($payload['user']['email']))
                            <br><span style="color:#64748b;">{{ $payload['user']['email'] }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <td style="padding:6px 0; color:#64748b;">IP Address</td>
                    <td style="padding:6px 0;">{{ $payload['ip'] }}</td>
                </tr>
            </table>

            <hr style="border:0; border-top:1px solid #e2e8f0; margin:18px 0;">

            <h2 style="margin:0 0 8px; font-size:15px; color:#0f172a;">Pesan</h2>
            <div style="background:#f8fafc; border-left:4px solid #e11d48; border-radius:8px; padding:14px 16px; font-size:14px; line-height:1.65; white-space:pre-wrap;">{{ $payload['message'] }}</div>

            <p style="margin:18px 0 0; font-size:11.5px; color:#94a3b8; line-height:1.5;">
                User-Agent: {{ $payload['agent'] }}
            </p>
        </div>
    </div>
</body>
</html>