@extends('layouts.app')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>History Buku Besar</h2>
            <div class="dropdown" style="margin-top: -3px;">
                <button class="btn btn-secondary dropdown-toggle rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(90deg, #666666, #999999); color: white; width: 30px; height: 30px; padding: 0; border: none; display: flex; align-items: center; justify-content: center;">
                    <span class="three-dots" style="font-size: 18px; line-height: 1;">⋮</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border-radius: 8px; padding: 0;">
                    <li><a class="dropdown-item" href="#" id="downloadTrigger" style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">Download Laporan</a></li>
                </ul>
            </div>
        </div>

        <form id="filterForm" method="GET" action="{{ route('buku-besar') }}">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select custom-dropdown" id="kelas" name="kelas_id">
                        <option value="" hidden>Silakan Pilih Kelas</option>
                        {{-- Data kelas akan diisi oleh JavaScript --}}
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select custom-dropdown" id="semester" name="semester">
                        <option value="" hidden>Silakan Pilih Semester</option>
                        {{-- Data semester akan diisi oleh JavaScript --}}
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control custom-search" id="search" name="search" placeholder="Cari mahasiswa..." value="{{ request('search') }}">
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="tahun" class="form-label">Tahun (Angkatan)</label>
                    @php
                        $angkatanList = $kelasList->pluck('angkatan')->unique()->sortDesc();
                    @endphp
                    <select class="form-select custom-dropdown" id="tahun" name="tahun">
                        <option value="" hidden>Silakan Pilih Angkatan</option>
                        @foreach ($angkatanList as $angkatan)
                            <option value="{{ $angkatan }}" {{ $angkatan == request('tahun', $tahun) ? 'selected' : '' }}>
                                {{ $angkatan }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="program_studi" class="form-label">Program Studi</label>
                    <select class="form-select custom-dropdown" id="program_studi" name="program_studi">
                        <option value="" hidden>Silakan Pilih Program Studi</option>
                        @foreach ($prodis as $prodi)
                            <option value="{{ $prodi->kode_prodi }}" {{ $prodi->kode_prodi == request('program_studi', $program_studi) ? 'selected' : '' }}>
                                {{ $prodi->nama_prodi }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <a href="{{ route('buku-besar.export', request()->query()) }}" class="btn btn-success">Export Excel</a>
            </div>
        </form>
    </div>

    @if ($data->isEmpty())
        <div class="alert alert-info mt-4" role="alert">
            Tidak ada data buku besar yang ditemukan untuk filter yang dipilih. Silakan coba filter lain atau pastikan data sudah diimpor.
        </div>
    @else
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="4">NO</th>
                        <th rowspan="4">NIM</th>
                        <th rowspan="4">NAMA</th>
                        <th colspan="{{ count($mataKuliahs) }}">MATA KULIAH</th>
                        <th id="sks-d-header" colspan="8" rowspan="2">JUMLAH SKS NILAI D SEMESTER</th>
                        <th colspan="2" rowspan="3">KUMULATIF</th>
                        <th colspan="2" rowspan="3">IP SEMESTER</th>
                        <th rowspan="4">IPK</th>
                        <th rowspan="4">S</th>
                        <th rowspan="4">I</th>
                        <th rowspan="4">A</th>
                        <th rowspan="4">JML</th>
                        <th rowspan="4">N.P</th>
                        <th rowspan="4">STATUS</th>
                        <th rowspan="4">KET.</th>
                    </tr>

                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            @php
                                // Ambil nama dosen pertama dari relasi nilai yang memuat dosen
                                $dosenNama = $mk->nilai->first()?->dosen->nama_dosen ?? '-';
                            @endphp
                            <th>{{ $dosenNama }}</th>
                        @endforeach
                    </tr>

                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->nama_matkul }}</th>
                        @endforeach
                        @for ($i = 1; $i <= 8; $i++)
                            <th class="semester-{{ $i }}"></th> {{-- These will be dynamically filled via JS if needed, or left blank as per original --}}
                        @endfor
                    </tr>

                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->jumlah_sks }}</th>
                        @endforeach
                        <th class="semester-1">I</th>
                        <th class="semester-2">II</th>
                        <th class="semester-3">III</th>
                        <th class="semester-4">IV</th>
                        <th class="semester-5">V</th>
                        <th class="semester-6">VI</th>
                        <th class="semester-7">VII</th>
                        <th class="semester-8">VIII</th>
                        <th>SKS D</th>
                        <th>NxB</th>
                        <th>LALU</th>
                        <th>SEKARANG</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($data as $mhs)
                        <tr>
                            <td>{{ $mhs['no'] }}</td>
                            <td>{{ $mhs['nim'] }}</td>
                            <td>{{ $mhs['nama_mhs'] }}</td>
                            @foreach($mhs['nilai_per_matkul'] as $nilai)
                                <td>
                                    {{ $nilai['indeks_nilai'] }}
                                </td>
                            @endforeach
                            @for ($i = 1; $i <= 8; $i++)
                                <td class="semester-{{ $i }} {{ $i <= $semester ? 'semester-active' : '' }}">
                                    {{ $i <= $semester ? ($mhs['jumlah_d_per_semester'][$i] ?? 0) : '' }}
                                </td>
                            @endfor
                            <td>{{ $mhs['total_d'] }}</td>
                            <td>{{ $mhs['nilai_bobot'] }}</td>
                            <td>{{ $mhs['ip_semester']['lalu'] }}</td>
                            <td>{{ $mhs['ip_semester']['sekarang'] }}</td>
                            <td>{{ $mhs['ipk'] }}</td>
                            <td>{{ $mhs['jml_sakit'] }}</td>
                            <td>{{ $mhs['jml_izin'] }}</td>
                            <td>{{ $mhs['jml_alfa'] }}</td>
                            <td>{{ $mhs['jml'] }}</td>
                            <td>{{ $mhs['nilai_penghayatan'] }}</td>
                            <td>{{ $mhs['status'] }}</td>
                            <td>{{ $mhs['keterangan'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="2">NO</th>
                        <th rowspan="2">KODE MATA KULIAH</th>
                        <th rowspan="2">NAMA MATA KULIAH</th>
                        <th rowspan="2">DOSEN</th>
                        <th colspan="8">JUMLAH MAHASISWA YANG MENDAPAT NILAI</th>
                    </tr>
                    <tr>
                        <th>A</th>
                        <th>AB</th>
                        <th>B</th>
                        <th>BC</th>
                        <th>C</th>
                        <th>CD</th>
                        <th>D</th>
                        <th>E</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($mataKuliahs as $index => $mk)
                        @php
                            $nilaiCounts = ['A' => 0, 'AB' => 0, 'B' => 0, 'BC' => 0, 'C' => 0, 'CD' => 0, 'D' => 0, 'E' => 0];
                            $dosenForMk = []; // Store unique dosens for this matkul
                            foreach ($data as $mhs) {
                                $nilai = $mhs['nilai_per_matkul']->where('kode_matkul', $mk->kode_matkul)->first();
                                if ($nilai) {
                                    if (!in_array($nilai['nama_dosen'], $dosenForMk) && ($nilai['nama_dosen'] != '-')) {
                                        $dosenForMk[] = $nilai['nama_dosen'];
                                    }
                                    $nilaiIndex = $nilai['indeks_nilai'] ?? '-';
                                    if (array_key_exists($nilaiIndex, $nilaiCounts)) {
                                        $nilaiCounts[$nilaiIndex]++;
                                    }
                                }
                            }
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $mk->kode_matkul }}</td>
                            <td>{{ $mk->nama_matkul }}</td>
                            <td>{{ implode(', ', $dosenForMk) }}</td> {{-- Display all unique dosens for this matkul --}}
                            <td>{{ $nilaiCounts['A'] }}</td>
                            <td>{{ $nilaiCounts['AB'] }}</td>
                            <td>{{ $nilaiCounts['B'] }}</td>
                            <td>{{ $nilaiCounts['BC'] }}</td>
                            <td>{{ $nilaiCounts['C'] }}</td>
                            <td>{{ $nilaiCounts['CD'] }}</td>
                            <td>{{ $nilaiCounts['D'] }}</td>
                            <td>{{ $nilaiCounts['E'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="2">IP</th>
                        <th rowspan="2">JUMLAH MAHASISWA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ipCounts = [
                            'IP<=2.75' => 0,
                            '2.75<IP<=3.50' => 0,
                            'IP>3.50' => 0,
                        ];
                        foreach ($data as $mhs) {
                            $ip = $mhs['ip_semester']['sekarang'];
                            if ($ip <= 2.75) {
                                $ipCounts['IP<=2.75']++;
                            } elseif ($ip <= 3.50) {
                                $ipCounts['2.75<IP<=3.50']++;
                            } else {
                                $ipCounts['IP>3.50']++;
                            }
                        }
                    @endphp
                    <tr>
                        <td>IP<=2.75</td>
                        <td>{{ $ipCounts['IP<=2.75'] }}</td>
                    </tr>
                    <tr>
                        <td>2.75<IP<=3.50</td>
                        <td>{{ $ipCounts['2.75<IP<=3.50'] }}</td>
                    </tr>
                    <tr>
                        <td>IP>3.50</td>
                        <td>{{ $ipCounts['IP>3.50'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>

        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="2">IPK</th>
                        <th rowspan="2">JUMLAH MAHASISWA</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $ipkCounts = [
                            'IPK<=2.75' => 0,
                            '2.75<IPK<=3.50' => 0,
                            'IPK>3.50' => 0,
                        ];
                        foreach ($data as $mhs) {
                            $ipk = $mhs['ipk'];
                            if ($ipk <= 2.75) {
                                $ipkCounts['IPK<=2.75']++;
                            } elseif ($ipk <= 3.50) {
                                $ipkCounts['2.75<IPK<=3.50']++;
                            } else {
                                $ipkCounts['IPK>3.50']++;
                            }
                        }
                    @endphp
                    <tr>
                        <td>IPK<=2.75</td>
                        <td>{{ $ipkCounts['IPK<=2.75'] }}</td>
                    </tr>
                    <tr>
                        <td>2.75<IPK<=3.50</td>
                        <td>{{ $ipkCounts['2.75<IPK<=3.50'] }}</td>
                    </tr>
                    <tr>
                        <td>IPK>3.50</td>
                        <td>{{ $ipkCounts['IPK>3.50'] }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    @endif

    {{-- Hidden Form for Laporan --}}
    <form action="{{ route('laporan.mahasiswa.generate') }}" method="POST" id="hiddenLaporanForm" style="display: none;">
        @csrf
        <input type="hidden" name="mahasiswa_nim" id="form_mahasiswa_nim">
        <input type="hidden" name="format" id="form_format" value="excel"> {{-- Format otomatis Excel --}}
        <input type="hidden" name="sertakan_nilai_matkul" id="form_sertakan_nilai_matkul">
        <input type="hidden" name="semester_nilai" id="form_semester_nilai">
        <input type="hidden" name="sertakan_ip_kelas" id="form_sertakan_ip_kelas">
        <input type="hidden" name="sertakan_ipk_kelas" id="form_sertakan_ipk_kelas">
    </form>

    <!-- Modal for Student Selection -->
    <div class="modal fade" id="studentSelectionModal" tabindex="-1" aria-labelledby="studentSelectionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #00008B;">Pilih Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <p style="font-weight: bold;">Nama Mahasiswa</p>
                    <p style="font-size: 12px; color: #666;">*Harap pilih nama mahasiswa.</p>
                    <div class="mb-3" style="width: 70%; margin: 0 auto;">
                        <select class="form-select" id="studentDropdown">
                            <option value="" selected disabled>Pilih Nama Mahasiswa</option>
                            @foreach ($data as $mhs)
                                <option value="{{ $mhs['nim'] }}">{{ $mhs['nama_mhs'] }} ({{ $mhs['nim'] }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextToOptionsBtn">Next</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for Report Options (File Type Selection REMOVED) -->
    <div class="modal fade" id="reportOptionsModal" tabindex="-1" aria-labelledby="reportOptionsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                <div class="modal-header">
                    <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="fw-bold text-center">Pilihan Tambahan</p>
                    <div class="form-check form-switch w-75 mx-auto">
                        <input class="form-check-input" type="checkbox" role="switch" id="includeCourseGrade" value="true">
                        <label class="form-check-label" for="includeCourseGrade">Sertakan Nilai Mata Kuliah</label>
                    </div>
                    <div id="finalSemesterOptionsContainer" class="mt-2 w-75 mx-auto" style="display: none; padding-left: 2.5rem;">
                        <label for="finalSemesterDropdown" class="form-label-sm">Pilih Semester:</label>
                        <select id="finalSemesterDropdown" class="form-select form-select-sm w-50">
                            @for ($i = 1; $i <= $totalSemesters; $i++)
                                <option value="{{ $i }}" {{ $i == $semester ? 'selected' : '' }}>Semester {{ $i }}</option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-check form-switch w-75 mx-auto mt-2">
                        <input class="form-check-input" type="checkbox" role="switch" id="includeClassIP" value="true" disabled>
                        <label class="form-check-label" for="includeClassIP">Sertakan Nilai IP Kelas</label>
                    </div>
                    <div class="form-check form-switch w-75 mx-auto">
                        <input class="form-check-input" type="checkbox" role="switch" id="includeClassIPK" value="true">
                        <label class="form-check-label" for="includeClassIPK">Sertakan Nilai IPK Kelas</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn" style="background: linear-gradient(90deg, #6c757d, #999999); color: white; border-radius: 8px;" id="backToStudentSelectionBtn">Back</button>
                    <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="generateReportBtn">Buat Laporan</button>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table thead th {
            vertical-align: middle;
            padding: 10px;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table td {
            padding: 8px;
            vertical-align: middle;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .three-dots {
            font-size: 18px;
            line-height: 1;
        }
        .dropdown-item:hover {
            background: linear-gradient(90deg, #32BB35, #8BE52E);
            color: white;
        }
        .dropdown-item:active {
            background: linear-gradient(90deg, #E11818, #FF6C6C);
            color: white;
        }
        .dropdown-menu {
            border-radius: 8px;
            padding: 0;
            border-width: 2px;
            border-color: #dee2e6;
        }
        .dropdown-item {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .dropdown-item:last-child {
            border-bottom: none;
        }
        /* Gaya untuk menyamakan warna latar belakang modal */
        .modal-custom-bg {
            background-color: #ffffff !important; /* Warna latar belakang putih konsisten */
        }
        /* Menghilangkan ikon segitiga dari dropdown-toggle */
        .dropdown-toggle::after {
            display: none !important;
        }
        /* Menebalkan border modal */
        .modal-content {
            border-width: 2px;
            border-color: #dee2e6;
        }
        .table-container {
            max-height: 600px;
            overflow-y: auto;
        }
        .custom-dropdown {
            width: 100%;
            max-width: 180px;
            padding: 10px 12px;
            border: 1px solid #ced4da;
            border-radius: 8px;
            background-color: #fff;
            color: #333;
            font-size: 14px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.08);
            transition: border 0.3s ease, box-shadow 0.3s ease;
            appearance: none;
            background-image: url("data:image/svg+xml;charset=US-ASCII,%3Csvg%20xmlns='http://www.w3.org/2000/svg'%20width='16'%20height='16'%20fill='gray'%20class='bi%20bi-caret-down-fill'%20viewBox='0%200%2016%2016'%3E%3Cpath%20d='M7.247%2011.14l-4.796-5.481c-.566-.647-.106-1.659.753-1.659h9.592c.86%200%201.32%201.012.753%201.659l-4.796%205.48a1%201%200%200%201-1.506%200z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 10px center;
            background-size: 16px 16px;
        }
        .custom-dropdown:focus {
            border-color: #7c3aed;
            box-shadow: 0 0 0 0.2rem rgba(124, 58, 237, 0.25);
            outline: none;
        }
        .custom-dropdown:hover {
            border-color: #7c3aed;
        }
        .custom-search {
            width: 100%;
            max-width: 200px;
            border-radius: 20px;
            border: 1px solid #ced4da;
            padding: 8px 15px;
        }
        .custom-search::placeholder {
            color: #6c757d;
        }
        .semester-1, .semester-2, .semester-3, .semester-4, .semester-5, .semester-6, .semester-7, .semester-8 {
            display: none;
        }
        .semester-active {
            display: table-cell;
        }
        .highlight {
            font-weight: bold;
            background-color: #f8f9fa;
        }
        .highlight th {
            font-weight: bold;
            color: #333;
        }
        .table thead th {
            vertical-align: middle !important;
            padding-top: 15px;
            padding-bottom: 15px;
        }
        .table th {
            text-align: center;
        }
    </style>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @push('scripts')
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Skrip Halaman Dimuat.");

        // Cek dependensi utama
        if (typeof bootstrap === 'undefined') {
            console.error("KRITIS: Pustaka JavaScript Bootstrap tidak ditemukan.");
            return;
        }

        // Ambil semua elemen penting
        const studentModalEl = document.getElementById('studentSelectionModal');
        const optionsModalEl = document.getElementById('reportOptionsModal'); // fileTypeModal dihapus
        const hiddenForm = document.getElementById('hiddenLaporanForm');
        const downloadTrigger = document.getElementById('downloadTrigger');

        // Inisialisasi modal
        const studentModal = new bootstrap.Modal(studentModalEl);
        const optionsModal = new bootstrap.Modal(optionsModalEl);

        // Listener untuk membuka alur modal
        downloadTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('studentDropdown').value = ""; // Reset dropdown mahasiswa
            resetReportOptions(); // Reset checkbox di modal opsi
            studentModal.show();
        });

        // Listener untuk tombol Next (dari Student Selection ke Report Options)
        document.getElementById('nextToOptionsBtn').addEventListener('click', function() {
            const nim = document.getElementById('studentDropdown').value;
            if(nim) {
                hiddenForm.querySelector('#form_mahasiswa_nim').value = nim;
                hiddenForm.querySelector('#form_format').value = 'excel'; // Otomatis set format ke excel
                studentModal.hide();
                optionsModal.show();
            } else {
                Swal.fire('Peringatan', 'Harap pilih mahasiswa terlebih dahulu.', 'warning');
            }
        });

        // Listener untuk tombol Back (dari Report Options ke Student Selection)
        document.getElementById('backToStudentSelectionBtn').addEventListener('click', () => {
            optionsModal.hide();
            studentModal.show();
        });

        // Listener untuk checkbox "Sertakan Nilai Mata Kuliah"
        document.getElementById('includeCourseGrade').addEventListener('change', function() {
            const container = document.getElementById('finalSemesterOptionsContainer');
            const checkbox = document.getElementById('includeClassIP');
            container.style.display = this.checked ? 'block' : 'none';
            // Menonaktifkan/mengaktifkan checkbox "Sertakan Nilai IP Kelas" berdasarkan "Sertakan Nilai Mata Kuliah"
            checkbox.disabled = !this.checked;
            if (!this.checked) {
                checkbox.checked = false; // Uncheck jika dinonaktifkan
            }
        });

        // Listener untuk tombol GENERATE laporan
        document.getElementById('generateReportBtn').addEventListener('click', function() {
            // 1. Kumpulkan data
            const includeGrade = document.getElementById('includeCourseGrade').checked;
            hiddenForm.querySelector('#form_sertakan_nilai_matkul').value = includeGrade;
            hiddenForm.querySelector('#form_sertakan_ip_kelas').value = document.getElementById('includeClassIP').checked;
            hiddenForm.querySelector('#form_sertakan_ipk_kelas').value = document.getElementById('includeClassIPK').checked;
            hiddenForm.querySelector('#form_semester_nilai').value = includeGrade ? document.getElementById('finalSemesterDropdown').value : '';

            // 2. Tampilkan notifikasi loading
            Swal.fire({
                title: 'Membuat Laporan...',
                text: 'Mohon tunggu, file Anda sedang disiapkan.',
                imageUrl: 'https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif',
                imageWidth: 100,
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 15000, // Waktu maksimal loading 15 detik (sesuaikan jika perlu)
                timerProgressBar: true,
            });

            // 3. Kirim form
            hiddenForm.submit();

            // 4. Tutup modal terakhir setelah submit
            optionsModal.hide();

            // 5. Ganti notifikasi setelah jeda untuk memberi waktu download dimulai
            // Penting: Browser akan otomatis menangani download. SweetAlert ini hanya notifikasi
            setTimeout(() => {
                if (Swal.isVisible()) {
                    Swal.update({
                        title: 'Selesai!',
                        text: 'File laporan Anda telah berhasil dibuat dan proses download akan dimulai oleh browser.',
                        icon: 'success',
                        imageUrl: null, // Hapus gif loading
                        showConfirmButton: true,
                        confirmButtonText: 'Ok',
                        confirmButtonColor: '#32BB35'
                    });
                }
            }, 2500); // Tunggu 2.5 detik sebelum menampilkan notifikasi sukses
        });

        // Fungsi untuk reset checkbox di Report Options
        function resetReportOptions() {
            document.getElementById('includeCourseGrade').checked = false;
            document.getElementById('includeClassIP').checked = false;
            document.getElementById('includeClassIPK').checked = false;
            document.getElementById('finalSemesterOptionsContainer').style.display = 'none'; // Sembunyikan dropdown semester
            document.getElementById('includeClassIP').disabled = true; // Nonaktifkan checkbox IP Kelas
        }


        // LOGIKA FILTER DROPWDOWN UTAMA (SUDAH ADA)
        const programStudiDropdown = document.getElementById('program_studi');
        const tahunDropdown = document.getElementById('tahun');
        const kelasDropdown = document.getElementById('kelas');
        const semesterDropdown = document.getElementById('semester');
        const filterForm = document.getElementById('filterForm');

        const initialProgramStudi = "{{ request('program_studi', $program_studi) }}";
        const initialTahun = "{{ request('tahun', $tahun) }}";
        const initialKelas = "{{ request('kelas_id', $kelas) }}";
        const initialSemester = "{{ request('semester', $semester) }}";

        const allKelasData = @json($kelasList);
        const allProdisData = @json($prodis); // Tambahkan ini jika belum ada

        function populateKelasDropdown(selectedProgramStudi, selectedTahun) {
            kelasDropdown.innerHTML = '<option value="" hidden>Silakan Pilih Kelas</option>';
            const filteredKelas = allKelasData.filter(kls => {
                return (selectedProgramStudi === '' || kls.kode_prodi == selectedProgramStudi) &&
                       (selectedTahun === '' || kls.angkatan == selectedTahun);
            });

            const uniqueKelasNames = [...new Set(filteredKelas.map(kls => kls.nama_kelas))].sort();

            uniqueKelasNames.forEach(nama_kelas => {
                const kls = filteredKelas.find(k => k.nama_kelas === nama_kelas);
                if (kls) {
                    const option = document.createElement('option');
                    option.value = kls.id;
                    option.text = `${nama_kelas}`;
                    if (kls.id == initialKelas) {
                        option.selected = true;
                    }
                    kelasDropdown.appendChild(option);
                }
            });
        }

        function populateSemesterDropdown(selectedProgramStudi) {
            semesterDropdown.innerHTML = '<option value="" hidden>Silakan Pilih Semester</option>';
            let maxSemester = 8;

            const selectedProdi = allProdisData.find(prodi => prodi.kode_prodi == selectedProgramStudi);
            if (selectedProdi && selectedProdi.nama_prodi.toLowerCase().includes('d3')) {
                maxSemester = 6;
            }

            for (let i = 1; i <= maxSemester; i++) {
                const option = document.createElement('option');
                option.value = i;
                option.text = i;
                if (i == initialSemester) {
                    option.selected = true;
                }
                semesterDropdown.appendChild(option);
            }
        }

        function updateSemesterColumns(selectedSemester) {
            for (let i = 1; i <= 8; i++) {
                const cells = document.querySelectorAll(`.semester-${i}`);
                cells.forEach(cell => cell.classList.remove('semester-active'));
            }

            const sksDHeader = document.getElementById('sks-d-header');
            sksDHeader.setAttribute('colspan', selectedSemester > 0 ? selectedSemester : 1);

            for (let i = 1; i <= selectedSemester && i <= 8; i++) {
                const cells = document.querySelectorAll(`.semester-${i}`);
                cells.forEach(cell => cell.classList.add('semester-active'));
            }
        }

        // Event Listeners for filter changes
        programStudiDropdown.addEventListener('change', function() {
            populateKelasDropdown(this.value, tahunDropdown.value);
            populateSemesterDropdown(this.value);
            filterForm.submit();
        });

        tahunDropdown.addEventListener('change', function() {
            populateKelasDropdown(programStudiDropdown.value, this.value);
            filterForm.submit();
        });

        kelasDropdown.addEventListener('change', function() {
            filterForm.submit();
        });

        semesterDropdown.addEventListener('input', function () {
            const selectedSemester = this.value;
            const url = new URL(window.location.href);
            url.searchParams.set('semester', selectedSemester);
            window.location.href = url.toString();
        });

        document.getElementById('search').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault(); // Prevent default form submission
                filterForm.submit();
            }
        });

        // Initial population and update on page load
        populateKelasDropdown(initialProgramStudi, initialTahun);
        populateSemesterDropdown(initialProgramStudi);
        updateSemesterColumns(parseInt(initialSemester));

        // Set initial values for dropdowns from request or default
        // Ini perlu dipastikan dijalankan setelah populate dropdown agar opsi tersedia
        // dan `selected` dari PHP Blade juga akan menangani seleksi awal
        if (programStudiDropdown.querySelector(`option[value="${initialProgramStudi}"]`)) {
            programStudiDropdown.value = initialProgramStudi;
        }
        if (tahunDropdown.querySelector(`option[value="${initialTahun}"]`)) {
            tahunDropdown.value = initialTahun;
        }
        if (kelasDropdown.querySelector(`option[value="${initialKelas}"]`)) {
            kelasDropdown.value = initialKelas;
        }
        if (semesterDropdown.querySelector(`option[value="${initialSemester}"]`)) {
            semesterDropdown.value = initialSemester;
        }
    });
    </script>
    @endpush
@endsection
