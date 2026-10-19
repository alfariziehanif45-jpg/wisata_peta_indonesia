<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Jelajah Wisata Indonesia</title>
    <style>
        *{box-sizing:border-box}body{margin:0;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;font-family:Arial,Helvetica,sans-serif;background:linear-gradient(135deg,#07111d,#123047 55%,#07111d);color:#17212b}
        .card{width:100%;max-width:430px;background:#fff;border-radius:18px;padding:32px;box-shadow:0 18px 55px #0005}
        h1{margin:0 0 8px;font-size:26px;color:#102536}.subtitle{color:#667785;margin:0 0 24px;line-height:1.5}
        label{display:block;font-size:14px;font-weight:700;margin:15px 0 7px}
        input{width:100%;padding:12px 13px;border:1px solid #cbd5df;border-radius:9px;font-size:15px}
        input:focus{outline:2px solid #4c9cbb;border-color:transparent}
        .submit{width:100%;border:0;border-radius:9px;background:#126b86;color:#fff;padding:13px;font-size:15px;font-weight:700;cursor:pointer;margin-top:22px}
        .submit:hover{background:#0b536a}.bottom{text-align:center;margin:20px 0 0;color:#647482;font-size:14px}.bottom a,.back a{color:#126b86;font-weight:700;text-decoration:none}
        .errors{background:#fff0f0;border:1px solid #f3baba;color:#a52626;padding:11px 14px;border-radius:8px;font-size:14px}.back{margin-top:18px;text-align:center;font-size:14px}
    </style>
</head>
<body>
    <main class="card">
        <h1>@yield('heading')</h1>
        <p class="subtitle">Jelajah Wisata Indonesia</p>
        @if ($errors->any())
            <div class="errors"><ul style="margin:0;padding-left:18px">@foreach ($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul></div>
        @endif
        @yield('content')
        <div class="back"><a href="{{ route('map.index') }}">← Kembali ke peta wisata</a></div>
    </main>
</body>
</html>
