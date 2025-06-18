@extends('layouts.app')

@section('title', 'Import Data PKL')

@section('navbar-content', 'Pencatatan Akademik / Import Data PKL')

@section('content')
<form action="{{ route('data-kp-pkl.import-data') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Import Data PKL</h2>
            </div>
            <div class="card-body">
                <p class="card-text">Upload data PKL dengan format excel sebanyak 16 file, dari masing-masing kelas tiap angkatan.</p>
                <div class="mb-3">
                    <label for="angkatan" class="form-label">Angkatan</label>
                    <select name="angkatan" id="angkatan" class="form-select" required>
                        <option value="" disabled selected>Pilih Angkatan</option>
                        @foreach($angkatans as $angkatan)
                            <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                        @endforeach
                    </select>
                    @error('angkatan')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="prodi" class="form-label">Program Studi</label>
                    <select name="prodi" id="prodi" class="form-select" required>
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <option value="" disabled selected>Pilih Program Studi</option>
                        <option value="1">D-3 Teknik Informatika</option>
                        <option value="2">D-4 Teknik Informatika</option>
                    </select>
                    @error('prodi')
                    <span class="text-danger">{{ $message }}</span>
                    @enderror
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <label for="file">File Excel</label>
                        <input type="file" name="file" accept=".xlsx, .xls">
                        @error('file')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="text-muted small">1/16</div>
                </div>
                <div class="dropzone border dashed p-5 text-center mb-3">
                    <p class="text-muted">Select a file or drag and drop here</p>
                    <div class="progress mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <button class="btn btn-primary" type="submit">Import</button>
            </div>
        </div>
    </div>
</form>
@endsection
