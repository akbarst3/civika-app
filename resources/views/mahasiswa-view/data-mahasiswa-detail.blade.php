@extends('layouts.app')

@section('content')
    <style>
        @import "bootstrap/dist/css/bootstrap.min.css";
        @import "@fortawesome/fontawesome-free/css/all.min.css";

        body,
        input,
        select,
        button,
        table {
            font-family: "Poppins", sans-serif;
        }

        thead th {
            background-color: #f4f4f4e8 !important;
            color: #000000cd !important;
            font-weight: 700;
            text-align: center;
        }

        td[rowspan] {
            vertical-align: middle !important;
        }

        table {
            font-size: 14px;
        }

        .pagination .page-link {
            border: none;
            color: #000;
            background-color: transparent;
            transition: background-color 0.3s ease;
            border-radius: 0;
        }

        .pagination .page-item {
            border: none;
        }

        .pagination .page-item.active .page-link {
            background-color: #ffa500;
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }

        .pagination .page-link:hover {
            background-color: #ffd59a;
            color: #000;
            border-radius: 8px;
        }

        .sidebar-nav .nav-link {
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 158, 0, 0.5);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        .custom-header {
            font-family: "Poppins", sans-serif;
            font-weight: bold;
            color: #333;
        }

        .custom-back-button {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px;
            font-family: "Poppins", sans-serif;
            color: #333;
            background-color: #fff;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-back-button:hover {
            border-color: #7c3aed;
        }

        .custom-back-button:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }

        .custom-card {
            border: 1px solid #ced4da;
            border-radius: 8px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
        }

        .custom-section-title {
            font-family: "Poppins", sans-serif;
            font-size: 1.25rem;
            font-weight: 600;
            color: #333;
        }

        .custom-detail-label {
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            color: #333;
            font-weight: 600;
        }

        .custom-detail-value {
            font-family: "Poppins", sans-serif;
            font-size: 14px;
            color: #333;
        }
    </style>

    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0 custom-header">Detail Data Mahasiswa</h2>
            <div class="d-flex gap-3 flex-wrap">
                <a href="{{ url()->previous() }}" class="btn custom-back-button">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <div class="card custom-card">
            <div class="card-body">
                <!-- Personal Information -->
                <h4 class="mb-3 custom-section-title">Informasi Pribadi</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">NIM:</strong> <span class="custom-detail-value">{{ $mahasiswa->nim }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Mahasiswa:</strong> <span class="custom-detail-value">{{ $mahasiswa->nama_mhs ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Lahir:</strong> <span class="custom-detail-value">{{ $mahasiswa->kota_lahir ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Tanggal Lahir:</strong> <span class="custom-detail-value">{{ $mahasiswa->tgl_lahir ? $mahasiswa->tgl_lahir->format('d F Y') : '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Jenis Kelamin:</strong> <span class="custom-detail-value">{{ $mahasiswa->jenis_kelamin ? 'Perempuan' : 'Laki-laki' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Agama:</strong> <span class="custom-detail-value">{{ $mahasiswa->agama ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Golongan Darah:</strong> <span class="custom-detail-value">{{ $mahasiswa->gol_darah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">No. KTP:</strong> <span class="custom-detail-value">{{ $mahasiswa->no_ktp ?? '-' }}</span>
                    </div>
                </div>

                <!-- Contact Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Kontak</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Mahasiswa:</strong> <span class="custom-detail-value">{{ $mahasiswa->dataTinggal->alamat_tinggal ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Kabupaten:</strong> <span class="custom-detail-value">{{ $mahasiswa->dataTinggal->kab_kota ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos:</strong> <span class="custom-detail-value">{{ $mahasiswa->dataTinggal->kode_pos ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon:</strong> <span class="custom-detail-value">{{ $mahasiswa->telepon ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Email:</strong> <span class="custom-detail-value">{{ $mahasiswa->email ?? '-' }}</span>
                    </div>
                </div>

                <!-- Educational Background -->
                <h4 class="mb-3 mt-4 custom-section-title">Latar Belakang Pendidikan</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama SLTA:</strong> <span class="custom-detail-value">{{ $mahasiswa->nama_slta ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Jalur Daftar:</strong> <span class="custom-detail-value">{{ $mahasiswa->jalur_daftar }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">NEM:</strong> <span class="custom-detail-value">{{ $mahasiswa->nem ?? '0.00' }}</span>
                    </div>
                </div>

                <!-- Father's Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Ayah</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->nama_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->alamat_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->kota_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->kode_pos_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->telepon_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pendidikan Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->pendidikan_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pekerjaan Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->pekerjaan_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Penghasilan Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->penghasilan_ayah ?? '-'}}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Instansi Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->instansi_ayah ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Instansi Ayah:</strong> <span class="custom-detail-value">{{ $mahasiswa->ayah->telepon_instansi_ayah ?? '-' }}</span>
                    </div>
                </div>

                <!-- Mother's Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Ibu</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->nama_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->alamat_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->kota_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->kode_pos_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->telepon_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pendidikan Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->pendidikan_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pekerjaan Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->pekerjaan_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Penghasilan Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->penghasilan_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Instansi Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->instansi_ibu ?? '-' }}</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Instansi Ibu:</strong> <span class="custom-detail-value">{{ $mahasiswa->ibu->telepon_instansi_ibu ?? '-' }}</span>
                    </div>
                </div>

                <!-- Academic Status -->
                <h4 class="mb-3 mt-4 custom-section-title">Status Akademik</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Status Mahasiswa:</strong> <span class="custom-detail-value">Aktif</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">M Status Mahasiswa ID:</strong> <span class="custom-detail-value">AK</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
