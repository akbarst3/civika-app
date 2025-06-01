@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <h1 class="mt-4 mb-4">Laporan Honor Tugas Akhir</h1>
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

                    <div class="table-responsive">
                        <table class="table table-striped table-bordered">
                            <thead class="table-dark">
                                <tr>
                                    <th>No</th>
                                    <th>NIP</th>
                                    <th>Nama Dosen</th>
                                    <th>Pembimbing I</th>
                                    <th>Pembimbing II</th>
                                    <th>Penguji I</th>
                                    <th>Penguji II</th>
                                    <th>Jumlah</th>
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

                    @if($prodi && $kaprodi && $sekretaris && $tahunAkademik)
                        <div class="mt-4">
                            <p><strong>Program Studi:</strong> {{ $prodi->nama_prodi }}</p>
                            <p><strong>Tahun Akademik:</strong> {{ $tahunAkademik }}</p>
                            <p><strong>Kaprodi:</strong> {{ $kaprodi->nama_dosen }}</p>
                            <p><strong>Sekretaris:</strong> {{ $sekretaris->nama_dosen }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
