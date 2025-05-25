@extends('layouts.app')

@section('title', 'Dashboard Reviewer 1')

@section('navbar-content', 'Dashboard / Home')

@section('content')
    <div class="container mt-4">
        <div class="row">
            <!-- Left Column: Riwayat Pengajuan Surat and Status Cards -->
            <div class="col-md-6">
                <!-- Buttons Section -->
                <div class="mb-4">
                    <a href="/riwayat-pengajuan" class="btn text-white text-center text-2xl font-bold d-flex align-items-center justify-content-center" style="width: 600px; height: 130px; background: linear-gradient(90deg, #29B147 0%, #8BE52E 100%);">
                        Riwayat Pengajuan Surat
                    </a>
                </div>
                <!-- Status Cards Section -->
                <div class="row g-4">
                    <div class="col-8">
                        <div class="card p-2 shadow-sm h-24" onclick="window.location.href='/daftar-verifikasi'" style="cursor: pointer;">
                            <h3 class="card-title mb-1 fs-5">Dalam Antrean</h3>
                            <div class="d-flex align-items-center">
                                <div class="d-flex gap-1">
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                </div>
                                <span class="ms-2 text-muted">+10</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-8">
                        <div class="card p-2 shadow-sm h-24" onclick="window.location.href='/daftar-disetujui'" style="cursor: pointer;">
                            <h3 class="card-title mb-1 fs-5">Sudah Berhasil</h3>
                            <div class="d-flex align-items-center">
                                <div class="d-flex gap-1">
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                    <div class="rounded-circle bg-secondary" style="width: 1.5rem; height: 1.5rem;"></div>
                                </div>
                                <span class="ms-2 text-muted">+8</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column: Verifikasi Pengajuan Surat and Image -->
            <div class="col-md-6">
                <!-- Buttons Section -->
                <div class="mb-4">
                    <a href="/verifikasi-pengajuan" class="btn text-white text-center text-2xl font-bold d-flex align-items-center justify-content-center" style="width: 600px; height: 130px; background: linear-gradient(90deg, #F24E1E 0%, #FF9A36 100%);">
                        Verifikasi Pengajuan Surat
                    </a>
                </div>
                <!-- Image -->
                <img src="{{ asset('images/Gedung-POLBAN.jpg') }}" alt="Gedung POLBAN" class="w-100 h-48 object-cover rounded">
            </div>
        </div>
    </div>
@endsection