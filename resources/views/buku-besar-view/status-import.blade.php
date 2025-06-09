@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 class="mb-0" style="font-weight: 700;">Import Buku Besar</h2>
            <!-- Search box dihapus -->
        </div>

        <form method="GET" action="{{ route('import.buku-besar.status') }}">
            <div class="d-flex justify-content-start align-items-center mb-3 flex-wrap gap-3">
                <div class="dropdown-wrapper">
                    <select class="form-select" style="width: 280px;" name="tahun_akademik" onchange="this.form.submit()">
                        @foreach ($tahunAkademikFilter as $tahun)
                            <option value="{{ $tahun }} Ganjil"
                                {{ $tahunAkademikAktif === "$tahun Ganjil" ? 'selected' : '' }}>
                                {{ $tahun }} Semester Ganjil
                            </option>
                            <option value="{{ $tahun }} Genap"
                                {{ $tahunAkademikAktif === "$tahun Genap" ? 'selected' : '' }}>
                                {{ $tahun }} Semester Genap
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="flex-grow-1"></div>
                <div class="button-wrapper">
                    <a href="{{ route('import.buku-besar.form') }}" class="import-button">Import Buku Besar</a>
                </div>
            </div>
        </form>

        <div class="table-container">
            <h5 class="table-title">Status Import Buku Besar</h5>
            <table class="table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kelas</th>
                        <th>Prodi</th>
                        <th>Angkatan</th>
                        <th>Semester</th>
                        <th>Status</th>
                        <th>Buku Besar</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($importStatus as $item)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $item['tingkat'] ?? '-' }}-{{ $item['nama_kelas'] ?? '-' }}</td>
                            <td>{{ $item['nama_prodi'] ?? '-' }}</td>
                            <td>{{ $item['angkatan'] ?? '-' }}</td>
                            <td>{{ $item['semester_aktif'] ?? '-' }}</td>
                            <td>
                                @if ($item['status'] == 'imported')
                                    <span class="badge badge-success">Sudah diimport</span>
                                @else
                                    <span class="badge badge-danger">Belum diimport</span>
                                @endif
                            </td>
                            <td>
                                <a href="#" class="btn btn-link text-decoration-none">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
