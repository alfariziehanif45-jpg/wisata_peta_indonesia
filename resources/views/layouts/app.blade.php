
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Jelajah Wisata Indonesia')</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <script src="https://unpkg.com/lucide@latest"></script>
</head>
<body class="bg-gray-950 text-white">

    @include('components.navbar')

    <main>
        @yield('content')
    </main>

    <script>
        lucide.createIcons();
    </script>
</body>
</html>
