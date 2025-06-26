@if (session('success') || session('error') || session('warning') || session('info'))
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            Swal.fire({
                icon: '{{ session("success") ? "success" : (session("error") ? "error" : (session("warning") ? "warning" : "info")) }}',
                title: '{{ session("success") ?: (session("error") ?: (session("warning") ?: session("info"))) }}',
                showConfirmButton: true,
                timer: 3000
            });
        });
    </script>
@endif
