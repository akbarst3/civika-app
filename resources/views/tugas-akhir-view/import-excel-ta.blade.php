@extends('layouts.app')

@section('title', 'Import Data Tuga Akhir')

@section('navbar-content', 'Pencatatan Akademik / Import Data TA')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Import Data Tugas Akhir</h2>
            </div>
            <div class="card-body">
                <p class="card-text">Upload data TA dengan format excel sebanyak 16 file, dari masing-masing kelas tiap angkatan.</p>

                <form action="{{ route('data-ta.importData') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-3">
                        <label for="prodi" class="form-label">Program Studi</label>
                        <select name="prodi" id="prodi" class="form-select" required>
                            <option value="" disabled selected>Pilih Program Studi</option>
                            <option value="1" selected>D3</option>
                            <option value="2" selected>D4</option>

                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="angkatan" class="form-label">Angkatan</label>
                        <select name="angkatan" id="angkatan" class="form-select" required>
                            <option value="" disabled selected>Pilih Angkatan</option>
                            @foreach ($angkatans as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                            @endforeach
                        </select>
                        @error('angkatan')
                        <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <input type="file" name="file" accept=".xlsx,.xls,.csv" class="form-control" required>
                            @error('file')
                            <span class="text-danger">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="text-muted small">1/16</div>
                    </div>

                    <div class="dropzone border dashed p-5 text-center mb-3">
                        <p class="text-muted">Select a file or drag and drop here</p>
                        <div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0"
                                 aria-valuemin="0" aria-valuemax="100"></div>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </div>
@endsection
