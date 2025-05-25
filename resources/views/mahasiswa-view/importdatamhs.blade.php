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
                <p class="card-text">Upload data mahasiswa dengan format excel sebanyak 16 file, dari masing-masing kelas tiap angkatan.</p>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <button class="btn btn-outline-secondary btn-sm">Select File</button>
                    </div>
                    <div class="text-muted small">1/16</div>
                </div>
                <div class="dropzone border dashed p-5 text-center mb-3">
                    <p class="text-muted">Select a file or drag and drop here</p>
                    <div class="progress mt-2">
                        <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                    </div>
                </div>
                <button class="btn btn-primary">Import</button>
            </div>
        </div>
    </div>
@endsection