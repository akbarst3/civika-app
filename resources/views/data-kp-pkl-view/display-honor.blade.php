@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mt-4 mb-4">Laporan Honor KP/PKL</h1>
    <div class="row">
        <div class="col-md-12">
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

                    <div class="mb-3">
                        <a href="{{ route('data-kp-pkl.generate-honor-form') }}" class="btn btn-secondary">Back to Generate Form</a>
                    </div>

                    @if($prodi && $tahunAkademik)
                        <div class="mb-4">
                            <h4>Report Details</h4>
                            <p><strong>Program Studi:</strong> {{ $prodi->nama_prodi ?? 'N/A' }}</p>
                            <p><strong>Tahun Akademik:</strong> {{ $tahunAkademik }}</p>
                            <p><strong>Kaprodi:</strong> {{ $kaprodi->nama_dosen ?? 'No Kaprodi assigned' }}</p>
                            <p><strong>Sekretaris:</strong> {{ $sekretaris->nama_dosen ?? 'No Sekretaris assigned' }}</p>
                        </div>
                    @else
                        <div class="alert alert-warning">
                            Report details are incomplete. Please ensure Program Studi and Tahun Akademik are provided.
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th scope="col">No</th>
                                    <th scope="col">NIP</th>
                                    <th scope="col">Nama Dosen</th>
                                    <th scope="col">Pembimbing I</th>
                                    <th scope="col">Pembimbing II</th>
                                    <th scope="col">Penguji I</th>
                                    <th scope="col">Penguji II</th>
                                    <th scope="col">Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($data as $index => $dosen)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $dosen->nip }}</td>
                                        <td>{{ $dosen->nama_dosen }}</td>
                                        <td>{{ $dosen->pembimbing_1_count }}</td>
                                        <td>{{ $dosen->pembimbing_2_count }}</td>
                                        <td>{{ $dosen->penguji_1_count }}</td>
                                        <td>{{ $dosen->penguji_2_count }}</td>
                                        <td>{{ $dosen->pembimbing_1_count + $dosen->pembimbing_2_count + $dosen->penguji_1_count + $dosen->penguji_2_count }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="text-center">No data available</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
