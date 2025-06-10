@extends('layouts.app-dosen')

@section('title', 'Buku Besar')

@section('navbar-content', 'Tabel Buku Besar Dosen')

@section('content')
    <div class="container mt-5">
        <h2>History Buku Besar Dosen</h2>
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="4">No</th>
                        <th rowspan="4">NIM</th>
                        <th rowspan="4">Nama</th>
                        <th colspan="{{ $mataKuliahs->count() }}">MATA KULIAH</th>
                    </tr>
                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->kode_matkul }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->nama_matkul }}</th>
                        @endforeach
                    </tr>
                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->jumlah_sks }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $mhs)
                        <tr>
                            <td>{{ $mhs['no'] }}</td>
                            <td>{{ $mhs['nim'] }}</td>
                            <td>{{ $mhs['nama_mhs'] }}</td>
                            @foreach ($mataKuliahs as $mk)
                                <td>
                                    @php
                                        $nilai = collect($mhs['nilai_per_matkul'])->firstWhere('kode_matkul', $mk->kode_matkul);
                                    @endphp
                                    {{ $nilai['indeks_nilai'] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($data->isEmpty())
            <div class="alert alert-info mt-3">
                Tidak ada data nilai untuk semester ini.
            </div>
        @endif
    </div>

    <style>
        .table thead th {
            vertical-align: middle;
            padding: 10px;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table td {
            padding: 8px;
            vertical-align: middle;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
    </style>
@endsection
