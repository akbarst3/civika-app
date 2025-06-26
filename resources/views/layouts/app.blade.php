<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>
    @vite('resources/css/generate-report.css')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-sweet-alert />
    <x-navbar :navtitle="View::getSection('navbar-content') ?? 'Dashboard / Home'" />
    <div class="d-flex">
        <x-sidebar />
        <main class="flex-grow-1 p-3 mt-5" style="margin-left: 15.625rem;">
            @yield('content')
        </main>
    </div>
</body>
</html>
