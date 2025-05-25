@extends('layouts.app')

@section('content')
<div class="container mt-5"> <!-- Ubah mt-4 menjadi mt-5 -->
    <h1 class="mt-4 mb-4">Generate Laporan PDPT PKL</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form action="{{ route('generate.pdpt.pkl') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="program_studi" class="form-label">Program Studi</label>
                            <select class="form-select" id="program_studi" name="program_studi" required>
                                <option value="" disabled selected>Pilih Program Studi</option>
                                <option value="D-3 Teknik Informatika">D-3 Teknik Informatika</option>
                                <option value="D-4 Teknik Informatika">D-4 Teknik Informatika</option>
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
                        <button type="submit" class="btn custom-button">Generate</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection