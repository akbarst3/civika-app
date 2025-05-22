@extends('layouts.app')

@section('title', 'Verifikasi Surat TU')

@section('content')
    <h3>Daftar Surat yang Perlu diVerifikasi</h3>
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
                        <th>Tambah Catatan</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 8; $i++)
                        <tr>
                            <td class="text-center">{{ $i }}</td>
                            <td class="text-center">Lorem ipsum.</td>
                            <td class="text-center">Lorem ipsum.</td>
                            <td class="text-center">{{ \Carbon\Carbon::create(2025, 2, $i == 7 ? 12 : 13, 10, 13, 07)->format('d F Y H:i:s') }}</td>
                            <td class="text-center">
                                @if ($i == 2)
                                    <span class="badge bg-warning text-dark">Revisi Dokumen</span>
                                @else
                                    <span class="badge bg-danger">Menunggu Verifikasi</span>
                                @endif
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-secondary">✎</a>
                            </td>
                            <td class="text-center">
                                <a href="#" class="btn btn-sm btn-outline-primary">👁️</a>
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