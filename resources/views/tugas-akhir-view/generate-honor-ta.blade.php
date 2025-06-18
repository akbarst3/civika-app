@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mt-4 mb-4">Generate Laporan Honor TA</h1>
    <div class="row">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Form to Display Table -->
                    <form action="{{ route('data-ta.display.honor.ta') }}" method="GET" id="displayForm">
                        <div class="mb-3">
                            <label for="prodi" class="form-label">Program Studi:</label>
                            <select name="prodi" id="prodi" class="form-control" required>
                                <option value="">Pilih Prodi</option>
                                @foreach($prodis as $prodi)
                                    <option value="{{ $prodi->kode_prodi }}" {{ old('prodi') == $prodi->kode_prodi ? 'selected' : '' }}>
                                        {{ $prodi->nama_prodi }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="angkatan" class="form-label">Angkatan:</label>
                            <select name="angkatan" id="angkatan" class="form-control" required>
                                <option value="">Pilih Angkatan</option>
                                @foreach($angkatans as $angkatan)
                                    <option value="{{ $angkatan }}" {{ old('angkatan') == $angkatan ? 'selected' : '' }}>
                                        {{ $angkatan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                    </form>

                    <!-- Form to Generate PDF -->
                    <form action="{{ route('data-ta.generate.honor') }}" method="POST" style="margin-top: 10px;" id="pdfForm">
                        @csrf
                        <input type="hidden" name="prodi" id="pdfProdi">
                        <input type="hidden" name="angkatan" id="pdfAngkatan">
                        <input type="hidden" name="jenis_laporan" value="honor">
                        <button type="submit" class="btn btn-success" id="generatePdfBtn" disabled>Unduh Laporan PDF</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const displayForm = document.getElementById('displayForm');
    const prodiSelect = document.getElementById('prodi');
    const angkatanSelect = document.getElementById('angkatan');
    const pdfProdi = document.getElementById('pdfProdi');
    const pdfAngkatan = document.getElementById('pdfAngkatan');
    const generatePdfBtn = document.getElementById('generatePdfBtn');

    // Sinkronkan nilai dan aktifkan tombol PDF
    [prodiSelect, angkatanSelect].forEach(select => {
        select.addEventListener('change', function() {
            const prodiValue = prodiSelect.value;
            const angkatanValue = angkatanSelect.value;
            pdfProdi.value = prodiValue;
            pdfAngkatan.value = angkatanValue;
            generatePdfBtn.disabled = !prodiValue || !angkatanValue;
            console.log('Prodi:', prodiValue, 'Angkatan:', angkatanValue, 'Button Disabled:', generatePdfBtn.disabled);
        });
    });

    // Debugging form submission
    displayForm.addEventListener('submit', function(e) {
        const prodiValue = prodiSelect.value;
        const angkatanValue = angkatanSelect.value;
        console.log('Form submitted to:', '{{ route('data-ta.display.honor.ta') }}');
        console.log('Prodi:', prodiValue, 'Angkatan:', angkatanValue);
        if (!prodiValue || !angkatanValue) {
            e.preventDefault(); // Hentikan submit jika ada field kosong
            console.log('Submission prevented due to empty field');
        }
    });
</script>
@endsection