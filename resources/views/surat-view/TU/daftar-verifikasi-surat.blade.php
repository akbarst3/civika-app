@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Surat Mahasiswa')

@section('content')
    <h3>Riwayat Pengajuan Surat Mahasiswa</h3>
    <div class="mt-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="font-size: 0.9em;">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Kode Surat</th>
                        <th>Jenis Surat</th>
                        <th>Ditujukan</th>
                        <th>Keperluan Surat</th>
                        <th>Status Verifikasi</th>
                        <th>Tahap Verifikasi</th>
                        <th>Waktu Verifikasi</th>
                        <th>Tahap Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($surats as $index => $surat)
                        <tr>
                            <td class="text-center">{{ $index + 1 }}</td>
                            <td class="text-center">{{ $surat->kode_surat }}</td>
                            <td class="text-center">{{ $surat->jenis_surat }}</td>
                            <td class="text-center">{{ $surat->ditujukan }}</td>
                            <td class="text-center">{{ $surat->keperluan }}</td>
                            <td class="text-center">{{ $surat->status_surat }}</td>
                            <td class="text-center">{{ $surat->tahap_verifikasi }}</td>
                            <td class="text-center">{{ $surat->created_at }}</td>
                            <td class="text-center">{{ $surat->updated_at }}</td>
                            <td class="text-center">
                                <a href="{{ route('detail-pengajuan-surat' , $surat->kode_surat) }}" class="btn btn-sm btn-outline-primary">👁️</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <nav aria-label="Page navigation" class="mt-3">
            <ul class="pagination justify-content-center">
                <li class="page-item"><a class="page-link" href="#">Prev</a></li>
                @for ($i = 1; $i <= 10; $i++)
                    <li class="page-item {{ $i == 1 ? 'active' : '' }}"><a class="page-link" href="#">{{ $i }}</a></li>
                @endfor
                <li class="page-item"><a class="page-link" href="#">Next</a></li>
            </ul>
        </nav>
    </div>
@endsection