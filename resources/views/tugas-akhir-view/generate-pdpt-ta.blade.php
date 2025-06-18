@extends('layouts.app')

@section('title', 'Tugas Akhir - Generate Laporan PDPT')

@section('navbar-content', 'Tugas Akhir / Generate Laporan PDPT')

@section('content')
    <div class="container mt-5">
        <h1 class="mt-4 mb-4">Generate Laporan PDPT TA</h1>
        @if (session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <div class="row">
            <div class="col-md-6">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <form action="{{ route('data-ta.generate.pdpt') }}" method="POST">
                            @csrf
                            <input type="hidden" name="jenis_laporan" value="pdpt">
                            <div class="mb-3">
                                <label for="program_studi" class="form-label">Program Studi</label>
                                <select class="form-select" id="program_studi" name="program_studi" required>
                                    <option value="" disabled {{ count($prodis) == 0 ? 'selected' : '' }}>Pilih Program Studi</option>
                                    @forelse ($prodis as $kode => $nama)
                                        <option value="{{ $kode }}">{{ $nama }}</option>
                                    @empty
                                        <option value="" disabled>Tidak ada program studi tersedia</option>
                                    @endforelse
                                </select>
                                @error('program_studi')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="angkatan" class="form-label">Angkatan</label>
                                <select class="form-select" id="angkatan" name="angkatan" required>
                                    <option value="" disabled {{ count($angkatans) == 0 ? 'selected' : '' }}>Pilih Angkatan</option>
                                    @forelse ($angkatans as $angkatan => $value)
                                        <option value="{{ $angkatan }}">{{ $value }}</option>
                                    @empty
                                        <option value="" disabled>Tidak ada angkatan tersedia</option>
                                    @endforelse
                                </select>
                                @error('angkatan')
                                <div class="text-danger mt-1">{{ $message }}</div>
                                @enderror
                            </div>
                            <button type="submit" class="btn btn-primary">Generate</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
