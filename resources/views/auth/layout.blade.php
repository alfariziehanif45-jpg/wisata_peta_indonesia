<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Jelajah Nusantara</title>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            margin: 0; padding: 0;
            background: #08100e; /* Hijau malam */
            font-family: 'Poppins', sans-serif;
            color: #fff; overflow-x: hidden;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
        }
        
        .stage-container {
            display: flex; width: 100%; max-width: 1100px; height: 700px;
            background: #11241e;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.8), inset 0 0 0 4px #d4af37;
            overflow: hidden; position: relative; margin: 20px; z-index: 1;
        }

        /* Kolom Kiri: Pemandangan Sawah Nusantara */
        .stage-left {
            flex: 1.1;
            background: 
                /* Langit Senja */
                linear-gradient(180deg, #2d4a3e 0%, #08100e 100%);
            position: relative;
            display: flex; flex-direction: column; align-items: center; justify-content: flex-end;
            padding-bottom: 40px; border-right: 4px solid #d4af37;
            overflow: hidden;
        }

        /* Siluet Gunung & Sawah Terasering */
        .stage-left::before {
            content: ''; position: absolute; bottom: 0; left: 0; right: 0; height: 60%;
            background: 
                radial-gradient(ellipse at 20% 100%, #0a1713 40%, transparent 41%),
                radial-gradient(ellipse at 80% 100%, #0a1713 50%, transparent 51%),
                linear-gradient(to top, transparent 0%, #11241e 100%);
            clip-path: polygon(0 100%, 0 60%, 15% 40%, 30% 70%, 50% 30%, 70% 60%, 85% 40%, 100% 70%, 100% 100%);
            z-index: 0;
            opacity: 0.8;
        }

        /* Kolom Kanan: Form */
        .stage-right {
            flex: 0.9; padding: 60px 50px;
            display: flex; flex-direction: column; justify-content: center;
            background: #11241e;
            background-image: radial-gradient(circle at 10px 10px, rgba(212, 175, 55, 0.05) 2px, transparent 2.5px);
            background-size: 40px 40px;
            position: relative; z-index: 2;
        }

        .stage-right h1 { 
            margin: 0 0 10px; font-family: 'Playfair Display', serif; 
            color: #d4af37; font-size: 2.2rem; text-shadow: 0 2px 4px rgba(0,0,0,0.5);
        }
        .stage-right p { margin: 0 0 30px; color: #8c9e98; font-size: 0.95rem; }
        
        label { display: block; margin-bottom: 8px; font-size: 0.85rem; color: #e0d8c3; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; }
        input[type="email"], input[type="password"], input[type="text"] {
            width: 100%; padding: 14px 16px; margin-bottom: 20px;
            border-radius: 8px; border: 1px solid #2d4a3e;
            background: rgba(8, 16, 14, 0.8); color: #fff;
            font-size: 1rem; font-family: 'Poppins', sans-serif;
            box-sizing: border-box; transition: all 0.3s ease;
        }
        input:focus { outline: none; border-color: #d4af37; box-shadow: 0 0 0 3px rgba(212, 175, 55, 0.2); }
        
        .submit {
            width: 100%; padding: 16px;
            background: linear-gradient(135deg, #d4af37 0%, #aa8c2c 100%);
            color: #08100e; border: none; border-radius: 8px;
            font-size: 1.1rem; font-weight: 700; font-family: 'Poppins', sans-serif;
            cursor: pointer; box-shadow: 0 4px 0 #6b5717;
            transition: transform 0.1s, box-shadow 0.1s; margin-top: 10px;
            text-transform: uppercase; letter-spacing: 1px;
        }
        .submit:active { transform: translateY(4px); box-shadow: 0 0 0 #6b5717; }
        .submit:disabled { opacity: 0.7; cursor: not-allowed; transform: none; box-shadow: none; }

        .bottom { text-align: center; margin-top: 20px; font-size: 0.9rem; color: #8c9e98; }
        .bottom a { color: #d4af37; text-decoration: none; font-weight: 600; }
        .bottom a:hover { text-decoration: underline; }

        @media (max-width: 900px) {
            .stage-container { flex-direction: column; height: auto; min-height: 100vh; border-radius: 0; margin: 0; border: none; }
            .stage-left { height: 400px; padding-bottom: 0; border-right: none; border-bottom: 4px solid #d4af37; }
            .stage-right { padding: 40px 20px; }
        }
    </style>
    @yield('styles')
</head>
<body>
    @yield('content')
</body>
</html>