@extends('layouts.app')

@section('title', 'Daftar Verifikasi Surat')

@section('sidebar')
    <x-sidebar-kajur />
@endsection

@section('content')
    <div class="container mt-4">
        <h3 class="mb-4 text-2xl font-bold">Daftar Verifikasi Surat</h3>

        <!-- Filter Dropdown -->
        <div class="mb-4">
            <form action="{{ route('daftar-verifikasi-surat') }}" method="GET" class="d-flex align-items-center gap-3">
                <label for="status" class="form-label font-semibold">Filter Status:</label>
                <select name="status" id="status" class="form-select w-auto rounded-lg shadow-sm" onchange="this.form.submit()">
                    <option value="all" {{ $stadtus == 'all' ? 'selected' : '' }}>Semua</option>
                    <option value="draft" {{ $status == 'draft' ? 'selected' : '' }}>Draft</option>
                    <option value="disetujui" {{ $status == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
                    <option value="ditolak" {{ $status == 'ditolak' ? 'selected' : '' }}>Ditolak</option>
                    <option value="diproses" {{ $status == 'diproses' ? 'selected' : '' }}>Proses</option>
                </select>
            </form>
        </div>

        <div class="table-responsive shadow-sm rounded-lg">
            <table class="table table-bordered table-striped table-hover" style="font-size: 0.9em; border-radius: 8px; overflow: hidden;">
                <thead class="bg-light">
                    <tr class="text-center">
                        <th class="p-3">No</th>
                        <th class="p-3">Kode Surat</th>
                        <th class="p-3">Jenis Surat</th>
                        <th class="p-3">Ditujukan</th>
                        <th class="p-3">Keperluan</th>
                        <th class="p-3">Status Verifikasi</th>
                        <th class="p-3">Tahap Verifikasi</th>
                        <th class="p-3">Waktu Verifikasi</th>
                        <th class="p-3">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($surats as $index => $surat)
                        <tr>
                            <td class="text-center p-3">{{ $index + 1 + ($surats->currentPage() - 1) * $surats->perPage() }}</td>
                            <td class="text-center p-3">{{ $surat->kode_surat }}</td>
                            <td class="text-center p-3">{{ $surat->jenis_surat }}</td>
                            <td class="text-center p-3">{{ $surat->ditujukan }}</td>
                            <td class="text-center p-3">{{ $surat->keperluan }}</td>
                            <td class="text-center p-3">
                                <span class="badge {{ $surat->status_surat == 'Disetujui' ? 'bg-success' : ($surat->status_surat == 'Ditolak' ? 'bg-danger' : ($surat->status_surat == 'Proses' ? 'bg-warning' : 'bg-secondary')) }}">
                                    {{ $surat->status_surat }}
                                </span>
                            </td>
                            <td class="text-center p-3">
                                @if($surat->tahap_verifikasi == 'tu')
                                    Tata Usaha
                                @else
                                    {{ ucfirst($surat->tahap_verifikasi) }}
                                @endif
                            </td>
                            <td class="text-center p-3">{{ $surat->updated_at->format('d-m-Y H:i') }}</td>
                            <td class="text-center p-3">
                                <a href="{{ route('detail-pengajuan-surat', $surat->kode_surat) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-eye"></i> Detail
                                </a>
                                @if ($surat->status_surat === 'disetujui')
                                <a href="{{ route('pengajuan-surat-create', $surat->kode_surat) }}" class="btn btn-sm btn-outline-primary rounded-pill">
                                    <i class="fas fa-eye"></i> Download
                                </a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center p-3">Tidak ada data pengajuan surat.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="mt-4">
            {{ $surats->appends(['status' => $status])->links() }}
        </div>
    </div>
@endsection