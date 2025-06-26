@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mt-4 mb-4">Generate Laporan Honor PKL</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    <form id="reportForm" method="GET" action="">
                        @csrf
                        <input type="hidden" name="jenis_laporan" value="honorKpPkl">
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
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn custom-button" onclick="setFormAction('{{ route('data-kp-pkl.generate-honor-download') }}')">Generate</button>
                        <button type="submit" class="btn custom-button" onclick="setFormAction('{{ route('data-kp-pkl.laporan-honor-display') }}')">Lihat Laporan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function setFormAction(action) {
        console.log('Setting form action to:', action); // Debugging
        document.getElementById('reportForm').action = action;
    }
</script>
@endsection
