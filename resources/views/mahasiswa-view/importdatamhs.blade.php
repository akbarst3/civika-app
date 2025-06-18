@extends('layouts.app')

@section('title', 'Import Data Mahasiswa')

@section('navbar-content', 'Pencatatan Akademik / Import Data Mahasiswa')

@section('content')
    <div class="container mt-5">
        <div class="card">
            <div class="card-header">
                <h2>Import Data Mahasiswa</h2>
            </div>
            <div class="card-body">
                <p class="card-text">Upload data mahasiswa dengan format excel untuk kelas tiap angkatan.</p>

                <!-- Success Notification -->
                @if (session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <!-- Error Notification -->
                @if (session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif

                <form action="{{ route('mahasiswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label for="angkatan" class="form-label">Pilih Angkatan</label>
                        <select name="angkatan" id="angkatan" class="form-select" required>
                            <option value="" disabled selected>Pilih angkatan</option>
                            @foreach ($angkatanList as $angkatan)
                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                            @endforeach
                        </select>
                        @error('angkatan')
                        <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div>
                            <label for="file" class="btn btn-outline-secondary btn-sm">Select File</label>
                            <input type="file" name="file" id="file" accept=".xlsx,.xls" class="d-none" onchange="updateFileStatus()">
                        </div>
                        <div class="text-muted small" id="file-status">No file selected</div>
                    </div>
                    <div class="dropzone border dashed p-5 text-center mb-3" id="dropzone">
                        <p class="text-muted">Select a file or drag and drop here</p>
                        <div class="progress mt-2">
                            <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" id="progress-bar"></div>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Import</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        const dropzone = document.getElementById('dropzone');
        const fileInput = document.getElementById('file');
        const fileStatus = document.getElementById('file-status');
        const progressBar = document.getElementById('progress-bar');

        function updateFileStatus() {
            if (fileInput.files.length > 0) {
                fileStatus.textContent = fileInput.files[0].name;
                progressBar.style.width = '100%';
                progressBar.classList.add('bg-success');
            } else {
                fileStatus.textContent = 'No file selected';
                progressBar.style.width = '0%';
                progressBar.classList.remove('bg-success');
            }
        }

        dropzone.addEventListener('dragover', (e) => {
            e.preventDefault();
            dropzone.classList.add('border-primary');
        });

        dropzone.addEventListener('dragleave', () => {
            dropzone.classList.remove('border-primary');
        });

        dropzone.addEventListener('drop', (e) => {
            e.preventDefault();
            dropzone.classList.remove('border-primary');
            fileInput.files = e.dataTransfer.files;
            updateFileStatus();
        });

        dropzone.addEventListener('click', () => {
            fileInput.click();
        });
    </script>
@endsection
