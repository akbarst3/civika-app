<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <script src="//unpkg.com/alpinejs" defer></script>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
</head>
<body>
    <x-navbar :navtitle="View::getSection('navbar-content') ?? 'Dashboard / Home'" />
    <div class="d-flex">
        <x-sidebar />
        <main class="flex-grow-1 p-3 mt-3" style="margin-left: 15.625rem;">
            @yield('content')
        </main>
    </div>

    <!-- Loading Spinner -->
    <div id="loadingSpinner" class="position-fixed top-0 start-0 w-100 h-100 d-flex justify-content-center align-items-center" style="background-color: rgba(255,255,255,0.8); z-index: 9999; display: none !important;">
        <div class="spinner-border text-primary" role="status">
            <span class="visually-hidden">Loading...</span>
        </div>
    </div>

    <!-- Global JavaScript -->
    <script>
        // Global loading functions
        window.showLoading = function() {
            document.getElementById('loadingSpinner').style.display = 'flex';
        }

        window.hideLoading = function() {
            document.getElementById('loadingSpinner').style.display = 'none';
        }

        // Global notification function
        window.showNotification = function(message, type = 'info') {
            const notification = document.createElement('div');
            notification.className = `alert alert-${type} alert-dismissible fade show position-fixed`;
            notification.style.cssText = 'top: 20px; right: 20px; z-index: 1050; min-width: 300px;';
            notification.innerHTML = `
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            `;
            document.body.appendChild(notification);
            
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 5000);
        }
    </script>

    @stack('scripts')
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</html>
