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
            background-color: #e9ecef !important;
            color: #333 !important;
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
            color: #333;
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
            color: #333;
            border-radius: 8px;
        }

        .sidebar-nav .nav-link {
            border-radius: 8px;
            transition: background-color 0.3s ease;
        }

        .sidebar-nav .nav-link:hover {
            background-color: rgba(255, 165, 0, 0.2);
        }

        .sidebar-nav .nav-link i {
            width: 20px;
            text-align: center;
        }

        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }

        .custom-search-container {
            position: relative;
            width: 200px;
        }

        .custom-search {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px 8px 35px;
            color: #333;
            width: 100%;
            font-family: "Poppins", sans-serif;
            background-color: #fff;
        }

        .custom-search::placeholder {
            color: #6c757d;
        }

        .custom-search-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        .custom-filter-container {
            position: relative;
            width: 180px;
            margin-right: 10px;
        }

        .custom-filter {
            width: 100%;
            padding: 8px 12px 8px 35px;
            border: 1px solid #ced4da;
            border-radius: 20px;
            background-color: #fff;
            color: #333;
            font-size: 14px;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='16'%20height='16'%20fill='%236c757d'%20class='bi%20bi-caret-down-fill'%20viewBox='0%200%2016%2016'%3E%3Cpath%20d='M7.247%2011.14l-4.796-5.481c-.566-.647-.106-1.659.753-1.659h9.592c.86%200%201.32%201.012.753%201.659l-4.796%205.48a1%201%200%200%201-1.506%200z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
            font-family: "Poppins", sans-serif;
        }

        .custom-filter-icon {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
            font-size: 14px;
        }

        .custom-filter:focus {
            border-color: #007bff;
            outline: none;
        }

        .custom-filter:hover {
            border-color: #007bff;
        }

        .custom-filter-button {
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px;
            color: #333;
            background-color: #fff;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-filter-button:hover {
            border-color: #007bff;
        }

        .custom-filter-button:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .custom-table th,
        .custom-table td {
            text-align: center;
            vertical-align: middle;
            padding: 10px;
        }

        .custom-header {
            font-weight: bold;
            color: #333;
        }

        .custom-pagination-button {
            border-radius: 50%;
            border: 1px solid #ced4da;
            padding: 8px;
            color: #333;
            background-color: #fff;
            transition: border 0.3s ease, box-shadow 0.3s ease;
        }

        .custom-pagination-button:hover {
            border-color: #007bff;
        }

        .custom-pagination-button:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .custom-detail-icon {
            color: #333;
            transition: color 0.3s ease;
            text-decoration: none;
        }

        .custom-detail-icon:hover {
            color: #007bff;
        }
    </style>

    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0 custom-header">Data Mahasiswa</h2>
            <div class="d-flex gap-3 flex-wrap">
                <form class="d-flex custom-search-container" role="search" method="GET" action="{{ route('data-mahasiswa.list') }}">
                    <i class="fas fa-magnifying-glass custom-search-icon"></i>
                    <input
                        type="search"
                        name="search"
                        class="form-control custom-search"
                        placeholder="Search"
                        aria-label="Search"
                        value="{{ request('search') }}"
                    />
                </form>
                <form class="custom-filter-container" method="GET" action="{{ route('data-mahasiswa.list') }}">
                    <i class="fas fa-users custom-filter-icon"></i>
                    <select class="custom-filter" name="angkatan" onchange="this.form.submit()">
                        <option value="" {{ !request('angkatan') ? 'selected' : '' }}>Any</option>
                        @foreach ($angkatanList as $angkatan)
                            <option value="{{ $angkatan }}" {{ request('angkatan') == $angkatan ? 'selected' : '' }}>
                                {{ $angkatan }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <form class="custom-filter-container" method="GET" action="{{ route('data-mahasiswa.list') }}">
                    <i class="fas fa-graduation-cap custom-filter-icon"></i>
                    <select class="custom-filter" name="prodi" onchange="this.form.submit()">
                        <option value="" {{ !request('prodi') ? 'selected' : '' }}>Any</option>
                        @foreach ($prodiList as $kode_prodi => $nama_prodi)
                            <option value="{{ $kode_prodi }}" {{ request('prodi') == $kode_prodi ? 'selected' : '' }}>
                                {{ $nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </form>
                <form class="custom-filter-container" method="GET" action="{{ route('data-mahasiswa.list') }}">
                    <i class="fas fa-chalkboard custom-filter-icon"></i>
                    <select class="custom-filter" name="kelas" onchange="this.form.submit()">
                        <option value="" {{ !request('kelas') ? 'selected' : '' }}>Any</option>
                        @foreach ($kelasList as $kelas)
                            <option value="{{ $kelas }}" {{ request('kelas') == $kelas ? 'selected' : '' }}>
                                {{ $kelas }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>
        </div>

        <div class="table-container">
            <table class="table table-bordered custom-table">
                <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama Mahasiswa</th>
                    <th>Kota Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Status<br>Mahasiswa</th>
                    <th>Telepon</th>
                    <th>Email</th>
                    <th>Detail</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($mahasiswa as $index => $mhs)
                    <tr>
                        <td>{{ $mahasiswa->firstItem() + $index }}</td>
                        <td>{{ $mhs->nim }}</td>
                        <td>{{ $mhs->nama_mhs }}</td>
                        <td>{{ $mhs->kota_lahir }}</td>
                        <td>{{ $mhs->tgl_lahir ? $mhs->tgl_lahir->format('d F Y') : '-' }}</td>
                        <td>Aktif</td>
                        <td>{{ $mhs->telepon ?? '-' }}</td>
                        <td>{{ $mhs->email ?? '-' }}</td>
                        <td>
                            <a href="{{ route('data-mahasiswa.show', $mhs->nim) }}" class="custom-detail-icon">
                                <i class="fa-solid fa-arrow-up-right-from-square"></i>
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="text-center">No data available</td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="d-flex justify-content-center align-items-center gap-3 mt-4">
            <button class="custom-pagination-button {{ $mahasiswa->onFirstPage() ? 'disabled' : '' }}"
                    @if(!$mahasiswa->onFirstPage()) onclick="window.location='{{ $mahasiswa->previousPageUrl() }}'" @endif>
                <i class="fas fa-chevron-left"></i>
            </button>
            <nav>
                <ul class="pagination mb-0">
                    @php
                        $currentPage = $mahasiswa->currentPage();
                        $lastPage = $mahasiswa->lastPage();
                        $range = 2;
                        $showEllipsis = $lastPage > 5;
                    @endphp
                    <li class="page-item {{ $currentPage == 1 ? 'active disabled' : '' }}">
                        @if($currentPage == 1)
                            <span class="page-link">1</span>
                        @else
                            <a class="page-link" href="{{ $mahasiswa->url(1) }}">1</a>
                        @endif
                    </li>
                    @if($showEllipsis && $currentPage > ($range + 2))
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    @for($i = max(2, $currentPage - $range); $i <= min($lastPage - 1, $currentPage + $range); $i++)
                        <li class="page-item {{ $currentPage == $i ? 'active' : '' }}">
                            @if($currentPage == $i)
                                <span class="page-link">{{ $i }}</span>
                            @else
                                <a class="page-link" href="{{ $mahasiswa->url($i) }}">{{ $i }}</a>
                            @endif
                        </li>
                    @endfor
                    @if($showEllipsis && $currentPage < ($lastPage - $range - 1))
                        <li class="page-item disabled">
                            <span class="page-link">...</span>
                        </li>
                    @endif
                    @if($lastPage > 1)
                        <li class="page-item {{ $currentPage == $lastPage ? 'active disabled' : '' }}">
                            @if($currentPage == $lastPage)
                                <span class="page-link">{{ $lastPage }}</span>
                            @else
                                <a class="page-link" href="{{ $mahasiswa->url($lastPage) }}">{{ $lastPage }}</a>
                            @endif
                        </li>
                    @endif
                </ul>
            </nav>
            <button class="custom-pagination-button {{ $mahasiswa->hasMorePages() ? '' : 'disabled' }}"
                    @if($mahasiswa->hasMorePages()) onclick="window.location='{{ $mahasiswa->nextPageUrl() }}'" @endif>
                <i class="fas fa-chevron-right"></i>
            </button>
        </div>
    </div>
@endsection
