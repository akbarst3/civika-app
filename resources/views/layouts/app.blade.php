<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <title>@yield('title')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <x-navbar :navtitle="View::getSection('navbar-content') ?? 'Dashboard / Home'" />
    <div class="d-flex">
        <x-sidebar />
        <main class="flex-grow-1 p-3" style="margin-left: 15.625rem;">
            @yield('content')
        </main>
    </div>
</body>
</html>
