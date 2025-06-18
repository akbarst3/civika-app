@extends('layouts.app')

@section('title', 'Dashboard Mahasiswa')

@section('navbar-content', 'Dashboard / Home')

@section('sidebar')
    <x-sidebar-mahasiswa />
@endsection

@section('content')
    <div class="container mt-4">
        <!-- Content Box Section -->
        <div class="row justify-content-center mb-4">
            <div class="col-12">
                <div class="d-flex justify-content-center align-items-center gap-4 flex-wrap">
                    <!-- Card Kiri - Status Pengajuan -->
                    <div class="card p-4 shadow-sm bg-gray-400" style="border-radius: 12px; width: 300px; height: 150px;">
                        <div class="d-flex flex-column justify-content-center align-items-center h-100 gap-2">
                            <p class="text-center text-blue-800 font-poppins mb-2 fs-6 fw-normal">
                                @if ($suratCount > 0)
                                    Sudah ada {{ $suratCount }} surat yang anda ajukan
                                @else
                                    Belum ada surat yang diajukan
                                @endif
                            </p>
                            <a href="{{ route('daftar-pengajuan-surat') }}"
                                class="rounded-pill px-4 py-2 fw-semibold bg-blue-800 text-white hover:bg-blue-900"
                                style="font-size: 0.875rem; text-decoration: none;">
                                @if ($suratCount > 0)
                                    Lihat Detail
                                @else
                                    Ajukan Sekarang
                                @endif
                            </a>
                        </div>
                    </div>

                    <!-- Card Kanan - Tabel Surat -->
                    <div class="card p-3 shadow-sm bg-gray-400"
                        style="border-radius: 12px; width: 600px; min-height: 150px;">
                        @if ($surats->isEmpty())
                            <h1 class="text-blue-800 font-poppins fw-bold mb-0 text-center mt-4" style="font-size: 2.5rem;">
                                Pengajuan
                                Surat</h1>
                        @else
                            <p class="text-center text-blue-800 font-poppins mb-2 fs-6 fw-normal">
                                Daftar Surat yang Anda Ajukan
                            </p>
                            <div class="table-responsive" style="border-radius: 8px; overflow: hidden;">
                                <table class="table table-sm table-borderless bg-gray-400 m-0">
                                    <thead>
                                        <tr class="bg-gray-500 text-blue-800">
                                            <th class="py-2 px-3 font-poppins fw-semibold" style="font-size: 0.875rem;">Kode
                                                Surat</th>
                                            <th class="py-2 px-3 font-poppins fw-semibold" style="font-size: 0.875rem;">
                                                Jenis Surat</th>
                                            <th class="py-2 px-3 font-poppins fw-semibold" style="font-size: 0.875rem;">
                                                Status</th>
                                            <th class="py-2 px-3 font-poppins fw-semibold" style="font-size: 0.875rem;">
                                                Tanggal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($surats as $surat)
                                            <tr class="text-blue-800 hover:bg-gray-300 transition-colors">
                                                <td class="py-2 px-3 font-poppins" style="font-size: 0.875rem;">
                                                    {{ $surat->kode_surat ?? '-' }}</td>
                                                <td class="py-2 px-3 font-poppins" style="font-size: 0.875rem;">
                                                    {{ $surat->jenis_surat ?? '-' }}</td>
                                                <td class="py-2 px-3 font-poppins" style="font-size: 0.875rem;">
                                                    {{ $surat->status_surat ?? '-' }}</td>
                                                <td class="py-2 px-3 font-poppins" style="font-size: 0.875rem;">
                                                    {{ $surat->created_at->format('d-m-Y') }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            <!-- Paginasi -->
                            <div class="d-flex justify-content-center mt-2">
                                {{ $surats->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="row justify-content-center">
            <div class="col-12">
                <button type="button"
                    class="btn text-white text-center fw-bold d-flex align-items-center justify-content-center shadow-sm hover:shadow-md transition-shadow"
                    style="width: 90%; max-width: 1200px; height: 2.5rem; background-color: #f59e0b; border-radius: 0.5rem; font-size: 1.25rem; margin: 0 auto;"
                    data-bs-toggle="modal" data-bs-target="#suratModal">
                    Lakukan Pengajuan Surat
                </button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="suratModal" tabindex="-1" aria-labelledby="suratModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content" style="border-radius: 0.5rem;">
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title fw-semibold" id="suratModalLabel">Jenis Surat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <div class="mb-4">
                        <select name="jenis_surat" id="jenis_surat" class="form-select p-2 border rounded">
                            <option value="">Pilih Jenis Surat</option>
                            <option value="suratBeasiswa">Surat Beasiswa</option>
                            <option value="suratOrmawa">Surat Ormawa</option>
                        </select>
                    </div>
                    <div class="d-flex justify-content-between gap-2">
                        <button type="button" class="btn btn-success px-4 py-2 fw-semibold" onclick="redirectToRoute()">
                            Lakukan Pengajuan
                        </button>
                        <button type="button" class="btn btn-danger px-4 py-2 fw-semibold" data-bs-dismiss="modal">
                            Batal
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        window.redirectToRoute = function() {
            const jenisSurat = document.getElementById('jenis_surat').value;
            let route;

            if (jenisSurat === 'suratBeasiswa' || jenisSurat === 'suratOrmawa') {
                route = "{{ route('pengajuan-surat') }}";
            } else {
                const alertDiv = document.createElement('div');
                alertDiv.className = 'alert alert-warning alert-dismissible fade show position-fixed';
                alertDiv.style.cssText = 'top: 20px; right: 20px; z-index: 9999; max-width: 300px;';
                alertDiv.innerHTML = `
                    Pilih jenis surat terlebih dahulu.
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                `;
                document.body.appendChild(alertDiv);
                setTimeout(() => {
                    if (alertDiv.parentNode) {
                        alertDiv.remove();
                    }
                }, 3000);
                return;
            }

            // Tutup modal sebelum redirect
            const modalElement = document.getElementById('suratModal');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }

            window.location.href = route + '?jenis_surat=' + encodeURIComponent(jenisSurat);
        };

        window.showPopup = function() {
            const modal = new bootstrap.Modal(document.getElementById('suratModal'));
            modal.show();
        };
    </script>
    <x-whatsapp-floating />
@endsection

@push('styles')
    <link href="https://fonts.googleapis.com/css2?family=Inter+Tight:wght@400;700&family=Poppins:wght@800&display=swap"
        rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .btn-primary {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
        }

        .btn-primary:hover {
            background-color: #1e40af;
            border-color: #1e40af;
        }

        .card:hover {
            transform: translateY(-2px);
            transition: all 0.2s ease;
        }

        .pagination .page-link {
            color: #1e3a8a;
            background-color: #e5e7eb;
            border-color: #d1d5db;
        }

        .pagination .page-item.active .page-link {
            background-color: #1e3a8a;
            border-color: #1e3a8a;
            color: white;
        }

        .pagination .page-link:hover {
            background-color: #d1d5db;
        }
    </style>
@endpush
