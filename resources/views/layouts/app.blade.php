<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>@yield('title')</title>
    <!-- Tambahkan CDN Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar :navtitle="View::getSection('navbar-content') ?? 'Dashboard / Home'" />
    <div class="d-flex">
        @if(View::hasSection('sidebar'))
            @yield('sidebar')
        @else
            <x-sidebar />
        @endif
        <main class="flex-grow-1 p-3" style="margin-left: 15.625rem;">
            @yield('content')
        </main>
    </div>
</body>
</html>
