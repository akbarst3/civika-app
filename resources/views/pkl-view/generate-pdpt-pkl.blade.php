@extends('layouts.app')

@section('content')
<div class="container mt-5"> <!-- Ubah mt-4 menjadi mt-5 -->
    <h1 class="mt-4 mb-4">Generate Laporan PDPT PKL</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('laporan.download') }}" method="GET">
                        @csrf
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Program Studi</label>
                            <select class="form-select" id="prodi" name="prodi" required>
                                <option value="" disabled selected>Pilih Program Studi</option>
                                <option value="1">D-3 Teknik Informatika</option>
                                <option value="2">D-4 Teknik Informatika</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="angkatan" class="form-label">Angkatan</label>
                            <select class="form-select" id="angkatan" name="angkatan" required>
                                <option value="" disabled selected>Pilih Angkatan</option>
                                <option value="2021">2021</option>
                                <option value="2022">2022</option>
                            </select>
                        </div>
                        <div class="mb-3">
                        <label for="jenis_laporan" class="form-label">Jenis Laporan</label>
                        <select class="form-select" id="jenis_laporan" name="jenis_laporan" required>
                            <option value="" disabled selected>Pilih Laporan</option>
                            <option value="pdpt">Laporan PDPT</option>
                            <option value="honor">Laporan Honor</option>
                        </select>
                        </div>
                        <button type="submit" class="btn custom-button">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection