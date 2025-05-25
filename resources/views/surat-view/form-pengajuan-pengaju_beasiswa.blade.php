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
</style>

@include('components.sidebar')
<main class="main-content">
    <div class="w-full max-w-4xl">
        <h2 class="text-[#1A237E] font-bold text-lg mb-8 select-none text-center" id="formTitle">Form Pengajuan Surat Beasiswa</h2>
        
        @if (session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif
        
        <form action=" " method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            <!-- Nama, IPK, Kelas -->
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="nama">Nama</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="nama" placeholder="Masukkan Nama" name="nama" type="text" value="{{ old('nama', session('pengajuan.nama', auth()->user()->mahasiswa->nama ?? '')) }}" required>
                    @error('nama') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="ipk">IPK</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="ipk" placeholder="Masukkan IPK" name="ipk" type="text" value="{{ old('ipk', session('pengajuan.ipk', auth()->user()->mahasiswa->ipk ?? '')) }}" required>
                    @error('ipk') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="kelas">Kelas</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="kelas" placeholder="Masukkan Kelas" name="kelas" type="text" value="{{ old('kelas', session('pengajuan.kelas', auth()->user()->mahasiswa->kelas ?? '')) }}" required>
                    @error('kelas') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- NIM, Prodi, Semester -->
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="nim">NIM</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="nim" placeholder="Masukkan NIM" name="nim" type="text" value="{{ old('nim', session('pengajuan.nim', auth()->user()->mahasiswa->nim ?? '')) }}" required>
                    @error('nim') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="prodi">Prodi</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="prodi" placeholder="Masukkan Prodi" name="prodi" type="text" value="{{ old('prodi', session('pengajuan.prodi', auth()->user()->mahasiswa->prodi ?? '')) }}" required>
                    @error('prodi') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="semester">Semester</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="semester" placeholder="Masukkan Semester" name="semester" type="text" value="{{ old('semester', session('pengajuan.semester', auth()->user()->mahasiswa->semester ?? '')) }}" required>
                    @error('semester') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- SMT, Tahun -->
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="smt">SMT</label>
                    <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                            id="smt" name="smt" required>
                        <option value="Ganjil" {{ old('smt', session('pengajuan.smt')) == 'Ganjil' ? 'selected' : '' }}>Ganjil</option>
                        <option value="Genap" {{ old('smt', session('pengajuan.smt')) == 'Genap' ? 'selected' : '' }}>Genap</option>
                    </select>
                    @error('smt') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="tahun">Tahun</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="tahun" placeholder="Masukkan Tahun" name="tahun" type="text" value="{{ old('tahun', session('pengajuan.tahun', date('Y'))) }}" required>
                    @error('tahun') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Ditujukan, Nama Perusahaan, Program -->
            <div class="flex space-x-4">
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="ditujukan">Ditujukan</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="ditujukan" placeholder="Masukkan Tujuan" name="ditujukan" type="text" value="{{ old('ditujukan') }}" required>
                    @error('ditujukan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="nama_perusahaan">Nama Perusahaan</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="nama_perusahaan" placeholder="Masukkan Nama Perusahaan" name="nama_perusahaan" type="text" value="{{ old('nama_perusahaan') }}">
                    @error('nama_perusahaan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex-1">
                    <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="program">Program</label>
                    <input class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                           id="program" placeholder="Masukkan Program" name="program" type="text" value="{{ old('program') }}">
                    @error('program') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Jenis Surat -->
            <div>
                <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="jenis_surat">Jenis Surat</label>
                <select class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                        id="jenis_surat" name="jenis_surat" required>
                    <option value="Rekomendasi" {{ old('jenis_surat') == 'Rekomendasi' ? 'selected' : '' }}>Rekomendasi</option>
                    <option value="Beasiswa" {{ old('jenis_surat') == 'Beasiswa' ? 'selected' : '' }}>Beasiswa</option>
                    <option value="Pengantar" {{ old('jenis_surat') == 'Pengantar' ? 'selected' : '' }}>Pengantar</option>
                    <option value="Lainnya" {{ old('jenis_surat') == 'Lainnya' ? 'selected' : '' }}>Lainnya</option>
                </select>
                @error('jenis_surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <!-- Keperluan Surat -->
            <div>
                <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="keperluan">Keperluan Surat</label>
                <textarea class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs placeholder:text-gray-400 focus:outline-none focus:ring-2 focus:ring-[#1A237E]"
                          id="keperluan" placeholder="Masukkan Keperluan" name="keperluan" rows="3" required>{{ old('keperluan') }}</textarea>
                @error('keperluan') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
            </div>
            <!-- Surat -->
            <div>
                <label class="block text-[10px] font-bold text-[#1A237E] mb-1 select-none" for="surat">Upload Surat (PDF, max 2MB)</label>
                <div class="border border-dashed border-gray-300 rounded-md p-4">
                    <input type="file" id="surat" name="surat" class="w-full border border-gray-300 rounded-md px-3 py-2 text-xs" accept="application/pdf" required>
                    @error('surat') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
            </div>
            <!-- Buttons -->
            <div class="flex space-x-4">
                <a href="{{ url()->previous() }}" class="w-1/2 bg-gradient-to-r from-[#E11818] to-[#FF6C6C] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition text-center">Batal</a>
                <button type="submit" class="w-1/2 bg-gradient-to-r from-[#00008B] to-[#3B3BBD] text-white font-bold text-xs py-2 rounded-md hover:brightness-110 transition">Ajukan</button>
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