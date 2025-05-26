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

        /* Font tabel diperkecil agar rapi */
        table {
            font-size: 14px;
        }

        /* Style pagination: hilangkan border pada page-link */
        .pagination .page-link {
            border: none;
            color: #000;
            background-color: transparent;
            transition: background-color 0.3s ease;
            border-radius: 0;
        }

        /* Hilangkan border pada pagination li */
        .pagination .page-item {
            border: none;
        }

        /* Warna oranye pagination jadi lebih terang */
        .pagination .page-item.active .page-link {
            background-color: #ffa500; /* oranye terang */
            color: #fff;
            border-radius: 8px;
            font-weight: 600;
            border: none;
        }

        /* Hover efek agar lebih halus */
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
            background-color: rgba(255, 158, 0, 0.5); /* Warna oranye transparan */
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
        <!-- Header Section -->
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0 custom-header">Detail Data Mahasiswa</h2>
            <div class="d-flex gap-3 flex-wrap">
                <!-- Back Button -->
                <a href="{{ url()->previous() }}" class="btn custom-back-button">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Detail Mahasiswa Section -->
        <div class="card custom-card">
            <div class="card-body">
                <!-- Personal Information -->
                <h4 class="mb-3 custom-section-title">Informasi Pribadi</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">No:</strong> <span class="custom-detail-value">1</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">NIM:</strong> <span class="custom-detail-value">231511070</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Mahasiswa:</strong> <span class="custom-detail-value">Aulia Putri Ramadhani</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Lahir:</strong> <span class="custom-detail-value">Kab. Garut</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Tanggal Lahir:</strong> <span class="custom-detail-value">6 November 2006</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Jenis Kelamin:</strong> <span class="custom-detail-value">Perempuan</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Agama:</strong> <span class="custom-detail-value">Islam</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Golongan Darah:</strong> <span class="custom-detail-value">A</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">No. KTP:</strong> <span class="custom-detail-value">3275090611060001</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Anak Ke:</strong> <span class="custom-detail-value">2</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Jumlah Saudara:</strong> <span class="custom-detail-value">3</span>
                    </div>
                </div>

                <!-- Contact Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Kontak</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Mahasiswa:</strong> <span class="custom-detail-value">Jalan Ciwuruga No. 20</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Kabupaten:</strong> <span class="custom-detail-value">Kab. Bandung Barat</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos:</strong> <span class="custom-detail-value">44151</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon:</strong> <span class="custom-detail-value">081234567890</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Email:</strong> <span class="custom-detail-value">aulia@gmail.com</span>
                    </div>
                </div>

                <!-- Educational Background -->
                <h4 class="mb-3 mt-4 custom-section-title">Latar Belakang Pendidikan</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama SLTA:</strong> <span class="custom-detail-value">SMA Negeri 1 Garut</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Jalur Daftar:</strong> <span class="custom-detail-value">SNBP</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">NEM:</strong> <span class="custom-detail-value">0.000</span>
                    </div>
                </div>

                <!-- Father's Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Ayah</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Ayah:</strong> <span class="custom-detail-value">Budi Santoso</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Ayah:</strong> <span class="custom-detail-value">Jalan Ciwuruga No. 20</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Ayah:</strong> <span class="custom-detail-value">Kab. Bandung Barat</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos Ayah:</strong> <span class="custom-detail-value">44151</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Ayah:</strong> <span class="custom-detail-value">081234567891</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pendidikan Ayah:</strong> <span class="custom-detail-value">S1</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pekerjaan Ayah:</strong> <span class="custom-detail-value">Pegawai Negeri Sipil</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Penghasilan Ayah:</strong> <span class="custom-detail-value">Rp 7.000.000</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Instansi Ayah:</strong> <span class="custom-detail-value">Dinas Pendidikan</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Instansi Ayah:</strong> <span class="custom-detail-value">0221234567</span>
                    </div>
                </div>

                <!-- Mother's Information -->
                <h4 class="mb-3 mt-4 custom-section-title">Informasi Ibu</h4>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Nama Ibu:</strong> <span class="custom-detail-value">Rina Wulandari</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Alamat Ibu:</strong> <span class="custom-detail-value">Jalan Ciwuruga No. 20</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kota Ibu:</strong> <span class="custom-detail-value">Kab. Bandung Barat</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Kode Pos Ibu:</strong> <span class="custom-detail-value">44151</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Ibu:</strong> <span class="custom-detail-value">081234567892</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pendidikan Ibu:</strong> <span class="custom-detail-value">SMA</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Pekerjaan Ibu:</strong> <span class="custom-detail-value">Ibu Rumah Tangga</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Penghasilan Ibu:</strong> <span class="custom-detail-value">-</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Instansi Ibu:</strong> <span class="custom-detail-value">-</span>
                    </div>
                    <div class="col-md-6 mb-3">
                        <strong class="custom-detail-label">Telepon Instansi Ibu:</strong> <span class="custom-detail-value">-</span>
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