@extends('layouts.app')

@section('title', 'Riwayat Pengajuan Surat')

@section('content')
    <h3>Riwayat Pengajuan Surat yang Telah Disetujui</h3>
    <div class="mt-4">
        <div class="table-responsive">
            <table class="table table-bordered table-striped" style="font-size: 0.9em;">
                <thead>
                    <tr class="text-center">
                        <th>No</th>
                        <th>Jenis Surat</th>
                        <th>Keperluan Surat</th>
                        <th>Tanggal</th>
                        <th>Status Verifikasi</th>
                        <th>Waktu Verifikasi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 12; $i++)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">Lorem ipsum.</td>
                            <td class="text-center">Lorem ipsum.</td>
                            <td class="text-center">{{ \Carbon\Carbon::create(2025, 2, 13, 10, 13, 07)->format('d F Y H:i:s') }}</td>
                            <td class="text-center"><span class="badge bg-success">Telah Disetujui</span></td>
                            <td class="text-center">{{ \Carbon\Carbon::create(2025, 2, 18, 15, 59, 18)->format('d F Y H:i:s') }}</td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">⬇</a>
                            </td>
                        </tr>
                    @endfor
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