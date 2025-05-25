@extends('layouts.app')

@section('navbar-content', 'Import Buku Besar')

@section('content')
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0" style="font-weight: 700;">Import Buku Besar</h2>
            <!-- Search box dihapus -->
        </div>

        <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-3">
            <div class="dropdown-wrapper">
                <select class="form-select" style="width: 280px;">
                    <option value="" selected disabled>Tahun Akademik</option>
                    <option value="2024/2025_semester_genap">2024/2025 Semester Genap</option>
                    <!-- Opsi lain akan diisi dari BE nanti -->
                </select>
            </div>
            <div class="flex-grow-1"></div>
            <div class="button-wrapper">
                <a href="{{ route('import.buku-besar.form') }}" class="import-button">Import Buku Besar</a>
            </div>
        </div>

        <div class="table-container">
            <h5 class="table-title">Status Import Buku Besar</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                        <th>Status</th>
                        <th>Buku Besar</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $importStatus = [
                            ['nama_kelas' => 'D', 'angkatan' => '2023', 'nama_prodi' => 'D3', 'status' => 'imported'],
                            ['nama_kelas' => '1B', 'angkatan' => '2023', 'nama_prodi' => 'D3', 'status' => 'not_imported'],
                            ['nama_kelas' => '1C', 'angkatan' => '2023', 'nama_prodi' => 'D3', 'status' => 'imported'],
                        ];
                    @endphp
                    @foreach ($importStatus as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['nama_kelas'] . $item['angkatan'] }}</td>
                            <td>{{ $item['nama_prodi'] }}</td>
                            <td>{{ $item['angkatan'] }}</td>
                            <td>
                                @if ($item['status'] == 'imported')
                                    <span class="badge badge-success">Sudah diimport</span>
                                @else
                                    <span class="badge badge-danger">Belum diimport</span>
                                @endif
                            </td>
                            <td>
                                <button class="btn btn-link text-decoration-none">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
