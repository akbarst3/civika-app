<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Halaman Utama</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;700&family=Poppins:wght@800&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            margin: 0;
            padding: 0;
            min-height: 100vh;
        }
        .main-content {
            margin-left: 250px;
            padding: 1.5rem;
            background-color: #e9ecef;
            display: flex;
            flex-direction: column;
            align-items: center;
            min-height: calc(100vh - 56px);
        }
        .popup {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            z-index: 1050;
            background-color: white;
            padding: 1.25rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 90%;
            max-width: 500px;
            max-height: 80vh;
            overflow-y: auto;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
        }
        .content-box {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 1.25rem;
            padding: 1.25rem;
            height: auto; /* Adjusted to auto to reduce empty space */
            margin-top: 0.5rem; /* Reduced margin-top to move containers higher */
            margin-bottom: 2rem;
        }
        .submit-button {
            margin-top: auto;
            margin-bottom: 0;
            width: 90%;
            max-width: 1200px;
            height: 2.5rem;
            background-color: #f59e0b;
            color: white;
            border-radius: 0.5rem;
            font-family: 'Inter', sans-serif;
            font-size: 1.25rem;
            display: flex;
            justify-content: center;
            align-items: center;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        .submit-button:hover {
            background-color: #d97706;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    @include('components.sidebar')

    <!-- Main Content -->
    <main class="main-content">
        <div class="content-box">
            <div class="bg-gray-400 rounded-lg flex flex-col justify-center items-center w-[300px] h-[150px] gap-2.5 p-5">
                <p class="text-center text-blue-800 font-poppins  text-base">Belum ada surat yang diajukan</p>
                <button onclick="showPopup()" class="bg-blue-900 text-white rounded-full px-8 py-2 text-sm font-inter hover:bg-blue-950 cursor-pointer">Ajukan Sekarang</button>
            </div>
            <div class="bg-gray-400 rounded-lg flex justify-center items-center w-[600px] h-[150px]">
                <p class="text-blue-800 font-poppins  text-4xl">Pengajuan Surat</p>
            </div>
        </div>
        <button onclick="showPopup()" class="submit-button">Lakukan Pengajuan Surat</button>

        <!-- Overlay dan Popup -->
        <div class="overlay" id="overlay"></div>
        <div class="popup" id="popup">
            <h3 class="text-lg  mb-2">Jenis Surat</h3>
            <div class="space-y-5">
                <select name="jenis_surat" id="jenis_surat" class="w-full p-2 mb-2 border rounded">
                    <option value="">Pilih Jenis Surat</option>
                    <option value="suratBeasiswa">Surat Beasiswa</option>
                    <option value="suratOrmawa">Surat Ormawa</option>
                </select>
                <div class="flex justify-between">
                    <button onclick="redirectToRoute()" class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Lakukan Pengajuan</button>
                    <button type="button" onclick="closePopup()" class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Batal</button>
                </div>
            </div>
        </div>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showPopup() {
            document.getElementById('popup').style.display = 'block';
            document.getElementById('overlay').style.display = 'block';
        }

        function closePopup() {
            document.getElementById('popup').style.display = 'none';
            document.getElementById('overlay').style.display = 'none';
        }

        function redirectToRoute() {
            const jenisSurat = document.getElementById('jenis_surat').value;
            let route;
            if (jenisSurat === 'suratBeasiswa') {
                route = "{{ route('pengajuan-surat') }}";
            } else if (jenisSurat === 'suratOrmawa') {
                route = "{{ route('pengajuan-surat') }}";
            } else {
                alert('Pilih jenis surat terlebih dahulu.');
                return;
            }
            window.location.href = route + '?jenis_surat=' + encodeURIComponent(jenisSurat);
        }
    </script>
</body>

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endsection

</html>