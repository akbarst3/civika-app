@extends('layouts.app')

@section('title', 'Dashboard Reviewer')

@section('navbar-content', 'Dashboard / Home')

@section('sidebar')
    <x-sidebar-kajur />
@endsection

@section('content')
    <div class="container mt-4">
        <!-- Full-width Button -->
        <div class="row mb-4">
            <div class="col-12">
                <a href="{{ route("daftar-verifikasi-surat") }}" class="btn text-white text-center text-2xl font-bold d-flex align-items-center justify-content-center shadow-sm hover:shadow-md transition-shadow" style="width: 100%; height: 120px; background: linear-gradient(90deg, #29B147 0%, #8BE52E 100%); border-radius: 12px;">
                    Daftar Riwayat Pengajuan Surat
                </a>
            </div>
        </div>

        <!-- Status Cards and Image -->
        <div class="row">
            <!-- Left Column: Status Cards -->
            <div class="col-md-6">
                <div class="row g-4">
                    <div class="col-12 col-md-6">
                        <div class="card p-3 shadow-sm" style="cursor: pointer; border-radius: 8px; min-height: 100px;">
                            <h3 class="card-title mb-2 fs-5 font-bold">Dalam Antrean</h3>
                            <div class="d-flex align-items-center">
                                <div class="d-flex gap-2">
                                    <div class="rounded-circle bg-warning" style="width: 1.25rem; height: 1.25rem;"></div>
                                    <div class="rounded-circle bg-warning" style="width: 1.25rem; height: 1.25rem;"></div>
                                    <div class="rounded-circle bg-warning" style="width: 1.25rem; height: 1.25rem;"></div>
                                </div>
                                <span class="ms-2 text-muted font-semibold">+10</span>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 col-md-6">
                        <div class="card p-3 shadow-sm" style="cursor: pointer; border-radius: 8px; min-height: 100px;">
                            <h3 class="card-title mb-2 fs-5 font-bold">Sudah Berhasil</h3>
                            <div class="d-flex align-items-center">
                                <div class="d-flex gap-2">
                                    <div class="rounded-circle bg-success" style="width: 1.25rem; height: 1.25rem;"></div>
                                    <div class="rounded-circle bg-success" style="width: 1.25rem; height: 1.25rem;"></div>
                                    <div class="rounded-circle bg-success" style="width: 1.25rem; height: 1.25rem;"></div>
                                </div>
                                <span class="ms-2 text-muted font-semibold">+8</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Right Column: Image -->
            <div class="col-md-6">
                <img src="{{ asset('images/Gedung-POLBAN.jpg') }}" alt="Gedung POLBAN" class="w-100 h-48 object-cover rounded" style="border-radius: 8px;">
            </div>
        </div>
    </div>
@endsection