@extends('layouts.app')

@section('title', 'Form Pengajuan Surat')

@section('content')
<style>
    @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap');
    body {
        font-family: 'Poppins', sans-serif;
    }
    .main-content {
        margin-left: 0; 
        flex-grow: 1;
        display: flex;
        justify-content: center;
        align-items: flex-start;
        padding: 1.5rem;
    }
    @media (max-width: 768px) {
        .main-content {
            margin-left: 0; 
        }
    }

    .overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        display: none;
        z-index: 50;
    }

    .popup {
        position: fixed;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%);
        background: white;
        padding: 2rem;
        border-radius: 8px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
        display: none;
        z-index: 100;
        width: 90%;
        max-width: 400px;
    }
</style>

    @include('components.sidebar')
    <main class="main-content">
        <div class="w-full max-w-4xl">
            <h2 class="text-[#1A237E] font-bold text-lg mb-8 select-none text-center" id="formTitle">
                Detail Pengajuan Surat Beasiswa 
            </h2>
            
        @if ($user->role === "tata_usaha")
            {{-- FORM PERTAMA: Update Data Pengajuan --}}
            <form action="{{ route('pengajuan-surat-update', $surat->kode_surat) }}" method="POST" enctype="multipart/form-data" class="space-y-5 mb-10">
                @csrf
                @method('PUT')
                <input type="hidden" name="jenisSurat" value="{{ $surat->jenis_surat }}">

                <div class="border border-[#1A237E] shadow-md rounded-xl p-6 space-y-4 bg-white">
                    <h2 class="text-[#1A237E] font-bold text-lg mb-8 select-none text-center" id="formTitle">
                        Apabila perlu ada perubahan, isi form di bawah sesuai kebutuhan
                    </h2>

                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="nama" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Nama</label>
                            <input name="nama" id="nama" type="text" placeholder="Masukkan Nama Lengkap"
                                value="{{ $pdfData['nama'] ?? '' }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                        <div class="flex-1">
                            <label for="ipk" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">IPK</label>
                            <input name="ipk" id="ipk" type="text" placeholder="Masukkan IPK, contoh: 3,99"
                            value="{{ $pdfData['ipk'] ?? '' }}"    
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                        <div class="flex-1">
                            <label for="kelas" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Kelas</label>
                            <input name="kelas" id="kelas" type="text" placeholder="Contoh: 1B/ D-III"
                            value="{{ $pdfData['kelas'] ?? '' }}"    
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="nim" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">NIM</label>
                            <input name="nim" id="nim" type="text" readonly value="{{ old('nim', $data->nim ?? '') }}"
                                placeholder="Masukkan NIM" class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                        <div class="flex-1">
                            <label for="prodi" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Prodi</label>
                            <input name="prodi" id="prodi" type="text" placeholder="contoh: D3 Teknik Informatika"
                                value="{{ $pdfData['prodi'] ?? '' }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                        <div class="flex-1">
                            <label for="semester" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Semester</label>
                            <input name="semester" id="semester" type="text" placeholder="Masukkan Semester"
                                value="{{ $pdfData['semester'] ?? '' }}"    
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                    </div>

                    <div class="flex space-x-4">
                        <div class="flex-1">
                            <label for="smt" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">SMT</label>
                            <input name="smt" id="smt" type="text" placeholder="Ganjil / Genap"
                                value="{{ $pdfData['smt'] ?? '' }}"    
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                        <div class="flex-1">
                            <label for="tahun" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Tahun</label>
                            <input name="tahun" id="tahun" type="text" placeholder="2024/2025"
                                value="{{ $pdfData['tahun'] ?? '' }}"    
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                    </div>

                    @if ($surat->jenis_surat === 'suratBeasiswa')
                        <div>
                            <label for="ditujukan" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Ditujukan</label>
                            <input name="ditujukan" id="ditujukan" type="text" placeholder="Masukkan Tujuan"
                                value="{{ $pdfData['ditujukan'] ?? '' }}"
                                class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">
                        </div>
                    @endif

                    <div>
                        <label for="keperluan" class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none">Keperluan Surat</label>
                        <textarea name="keperluan" id="keperluan" rows="3" placeholder="Masukkan Keperluan"
                            class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]">{{ $pdfData['keperluan'] ?? '' }}</textarea>
                    </div>

                    <div class="flex space-x-4">
                        <button type="submit" class="w-1/2 bg-gradient-to-r from-[#00008B] to-[#3B3BBD] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">
                            Update
                        </button>
                    </div>
                    @if(session('success'))
                        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative text-sm" role="alert">
                            {{ session('success') }}
                        </div>
                    @endif
                </div>
            </form>
        @endif
            {{-- TAMPILAN FILE --}}
        @if ($user->role === "tata_usaha")
            <div class="mb-10">
                <h3 class="text-center text-xl font-bold text-[#344767] py-2">Berkas Pendukung</h3>
                 @php
                    $filePath = public_path('laraview/' . $data->nim . '/' . $surat->kode_surat . '/' . $surat->kode_surat . '-berkas-pendukung.pdf');
                @endphp
                @if (file_exists($filePath))
                    <iframe src="{{ asset('laraview/' . $data->nim . '/' . $surat->kode_surat . '/' . $surat->kode_surat . '-berkas-pendukung.pdf') }}">
                @else
                    <h4>*Pengaju tidak melapirkan berkas</h4>
                @endif
            </div>
        @endif

            <div class="mb-10">
                <h3 class="text-center text-xl font-bold text-[#344767] py-2">Preview Surat</h3>
                <iframe src="{{ asset('laraview/' . $data->nim . '/' . $surat->kode_surat . '/' . $surat->kode_surat . '-preview-surat.pdf') }}"
                    width="1000px" height="600px"></iframe>
            </div>

            {{-- FORM KEDUA: Terima/Tolak Surat --}}
            <form action="{{ route('detail-pengajuan-surat-update', $surat->kode_surat) }}" method="POST">
                @csrf
                @method('PUT')
                @if ($user->role === "tata_usaha")
                    <div class="flex space-x-4">
                        <button type="submit" name="status" value="tolak"
                            class="w-1/2 bg-gradient-to-r from-[#E11818] to-[#FF6C6C] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">
                            Tolak
                        </button>
                        <button type="button" onclick="showPopup()"
                            class="submit-button w-1/2 bg-gradient-to-r from-[#00008B] to-[#3B3BBD] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">
                            Teruskan Pengajuan
                        </button>
                    </div>

                    <div class="overlay" id="overlay"></div>
                    <div class="popup" id="popup">
                        <h3 class="text-lg mb-2">Teruskan Surat ke Siapa?</h3>
                        <div class="space-y-5">
                            <select name="tahap_verifikasi" id="tahap_verifikasi" class="w-full p-2 mb-2 border rounded">
                                <option value="">Pilih Penerima</option>
                                <option value="Kaprodi">kaprodi</option>
                                <option value="Kajur">kajur</option>
                                <option value="Wali Dosen">wali dosen</option>
                            </select>
                            <div class="flex justify-between">
                                <button type="submit" 
                                    class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">Teruskan Pengajuan</button>
                                <button type="button" onclick="closePopup()"
                                    class="bg-red-500 text-white px-4 py-2 rounded hover:bg-red-600">Batal</button>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="flex space-x-4">
                        <button type="submit" name="status" value="tolak"
                            class="w-1/2 bg-gradient-to-r from-[#E11818] to-[#FF6C6C] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">
                            Tolak
                        </button>
                        <button type="submit"
                            class="submit-button w-1/2 bg-gradient-to-r from-[#00008B] to-[#3B3BBD] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">
                            Terima Pengajuan
                        </button>
                    </div>
                @endif
            </form>
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
            const verifikator = document.getElementById('tahap_verifikasi').value;
            let route;
            if (verifikator === 'Kaprodi') {
                route = "{{ route('daftar-verifikasi-surat') }}";
            } else if (verifikator === 'Kajur') {
                route = "{{ route('daftar-verifikasi-surat') }}";
            } else if (verifikator === 'wali dosen') {
                route = "{{ route('daftar-verifikasi-surat') }}";
            } else {
                alert('Pilih dahulu, surat akan diteruskan ke siapa? dahulu.');
                return;
            }
            window.location.href = route;
        }
    </script>
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