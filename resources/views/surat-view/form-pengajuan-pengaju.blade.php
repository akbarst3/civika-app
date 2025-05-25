@extends('layouts.app')

@section('title', 'Form Pengajuan Surat')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
    }
    .main-content {
        margin-left: 0; /* Override margin dari app.blade.php agar tidak double */
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 1.5rem;
    }
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0; /* Pastikan tidak ada margin di layar kecil */
        }
    }
</style>

    @include('components.sidebar')
    <!-- Main Content -->
    <main class="main-content">
        <div class="w-full max-w-4xl">
            <h2 class="text-[#1A237E] font-bold text-lg mb-8 select-none text-center" id="formTitle">Form Pengajuan Surat</h2>
            <form class="space-y-5">
                <!-- Nama Lengkap -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="nama">Nama</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="nama" placeholder="Masukkan Nama" name="nama" type="text" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="ipk">IPK</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="ipk" placeholder="Masukkan IPK" name="ipk" type="text" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="kelas">Kelas</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="kelas" placeholder="Masukkan Kelas" name="kelas" type="text" required>
                    </div>
                </div>
                <!-- NIM -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="nim">NIM</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="nim" placeholder="Masukkan NIM" name="nim" type="text" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="prodi">Prodi</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="prodi" placeholder="Masukkan Prodi" name="prodi" type="text" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="semester">Semester</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="semester" placeholder="Masukkan Semester" name="semester" type="text" required>
                    </div>
                </div>
                <!-- SMT dan Tahun -->
                <div class="flex space-x-4">
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="smt">SMT</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="smt" placeholder="Masukkan Ganji/Genap" name="smt" type="text" required>
                    </div>
                    <div class="flex-1">
                        <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="tahun">Tahun</label>
                        <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                               id="tahun" placeholder="Masukkan Tahun" name="tahun" type="text" required>
                    </div>
                </div>
                <!-- Ditujukan -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="ditujukan">Ditujukan</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="ditujukan" placeholder="Masukkan Tujuan" name="ditujukan" type="text" required>
                </div>
                <!-- Keperluan Surat -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="keperluan">Keperluan Surat</label>
                    <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                              id="keperluan" placeholder="Masukkan Email" name="keperluan" rows="3" required></textarea>
                </div>
                <!-- Berkas Pendukung -->
                <div>
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="berkas">Berkas Pendukung</label>
                    <div class="border border-dashed border-gray-300 rounded-md p-4 text-center">
                        <input type="file" id="berkas" name="berkas" class="hidden">
                        <label for="berkas" class="cursor-pointer">
                            <i class="fas fa-cloud-upload-alt text-gray-400 text-2xl mb-2"></i>
                            <p class="text-xs text-gray-400">Select a file or drag and drop</p>
                            <p class="text-xs text-gray-400">JPG, PNG, or PDF, File size no more than 10MB</p>
                            <button type="button" class="mt-2 bg-[#1A237E] text-white font-bold text-xs py-1 px-4 rounded-md hover:brightness-110 transition">Select File</button>
                        </label>
                    </div>
                </div>
                <!-- Buttons -->
                <div class="flex space-x-4">
                    <button type="button" class="w-1/2 bg-gradient-to-r from-[#E11818] to-[#FF6C6C] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition" data-bs-toggle="modal" data-bs-target="#confirmationModal">Batal</button>
                    <button type="button" class="w-1/2 bg-gradient-to-r from-[#00008B] to-[#3B3BBD] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition" data-bs-toggle="modal" data-bs-target="#confirmationModal">Ajukan</button>
                </div>
            </form>
        </div>
    </main>
@endsection

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
@endsection

@section('scripts')
<script src="https://cdn.tailwindcss.com"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    const selectedSurat = "Pengajuan Surat";
    document.getElementById('formTitle').textContent = selectedSurat;
</script>
@endsection