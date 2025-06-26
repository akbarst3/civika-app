@extends('layouts.app-dosen')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>History Buku Besar - Kelas {{ $kelas->nama_kelas }} {{ $kelas->angkatan }}</h2>
            <div class="dropdown" style="margin-top: -3px;">
                <button class="btn btn-secondary dropdown-toggle rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(90deg, #666666, #999999); color: white; width: 30px; height: 30px; padding: 0; border: none; display: flex; align-items: center; justify-content: center;">
                    <span class="three-dots" style="font-size: 18px; line-height: 1;">⋮</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border-radius: 8px; padding: 0;">
                    {{-- Opsi Download PDF Langsung --}}
                    <li><a class="dropdown-item" href="#" id="downloadPdfTrigger" style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">Download PDF</a></li>
                    {{-- Opsi Ganti Role ke Dosen --}}
                    <li><a class="dropdown-item" href="{{ route('buku-besar-dosen') }}" style="padding: 10px 15px;">Ganti Akses ke Dosen</a></li>
                </ul>
            </div>
        </div>
        <form method="GET" action="{{ route('buku-besar-wali-mahasiswa') }}">
            <div class="row mb-3">
                <div class="col-12 col-sm-6 col-md-2 mb-2">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select custom-dropdown w-100" id="semester" name="semester" onchange="this.form.submit()">
                        @for ($i = 1; $i <= $totalSemesters; $i++)
                            <option value="{{ $i }}" {{ $i == request('semester', $semester) ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                </div>
            </div>
        </form>


        @if ($mataKuliahs->isEmpty())
            <div class="alert alert-info mt-4" role="alert">
                Tidak ada data buku besar yang ditemukan untuk filter yang dipilih.
                Silakan minta tolong kepada Staff Tata Usaha untuk terlebih dahulu mengunggah buku besar semester yang dipilih.
            </div>
        @else

            <div class="table-responsive mt-4">
                <table class="table table-bordered text-center align-middle small">
                    <thead>
                        <tr>
                            <th rowspan="4">NO</th>
                            <th rowspan="4">NIM</th>
                            <th rowspan="4">NAMA</th>
                            <th colspan="{{ $mataKuliahs->count() }}">MATA KULIAH</th>
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
                                <th>{{ $mk->kode_matkul }}</th>
                            @endforeach
                        </tr>

                        <tr>
                            @foreach ($mataKuliahs as $mk)
                                <th>{{ $mk->nama_matkul }}</th>
                            @endforeach
                            @for ($i = 1; $i <= 8; $i++)
                                <th class="semester-{{ $i }}">
                                    @if ($i == $semester)
                                        {{ $totalSks[$i] ?? "" }}
                                    @else
                                        {{ "" }}
                                    @endif
                                </th>
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
                                        @if ($i == $semester)
                                            {{ $mhs['jumlah_d'] ?? "" }}
                                        @else
                                            {{ "" }}
                                        @endif
                                    </td>
                                @endfor
                                <td>{{ $mhs['sks_d'] }}</td>
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
                                $nilaiDetails = [];
                                foreach ($data as $mhs) {
                                    $nilai = $mhs['nilai_per_matkul']->where('kode_matkul', $mk->kode_matkul)->first();
                                    if ($nilai && !in_array($nilai['nama_dosen'], $nilaiDetails)) {
                                        $nilaiDetails[] = $nilai['nama_dosen'];
                                    }
                                    $nilaiIndex = $nilai['indeks_nilai'] ?? '-';
                                    if (array_key_exists($nilaiIndex, $nilaiCounts)) {
                                        $nilaiCounts[$nilaiIndex]++;
                                    }
                                }
                            @endphp
                            @foreach ($nilaiDetails as $dosen)
                                <tr>
                                    <td>{{ $loop->parent->index + 1 }}</td>
                                    <td>{{ $mk->kode_matkul }}</td>
                                    <td>{{ $mk->nama_matkul }}</td>
                                    <td>{{ $dosen }}</td>
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
        </div>

        <form action="{{ route('laporan.mahasiswa.generate') }}" method="POST" id="hiddenLaporanForm" style="display: none;">
            @csrf
            <input type="hidden" name="mahasiswa_nim" id="form_mahasiswa_nim">
            <input type="hidden" name="format" id="form_format" value="pdf"> {{-- Set default ke pdf --}}
            <input type="hidden" name="sertakan_nilai_matkul" id="form_sertakan_nilai_matkul">
            <input type="hidden" name="semester_nilai" id="form_semester_nilai">
            <input type="hidden" name="sertakan_ip_kelas" id="form_sertakan_ip_kelas">
            <input type="hidden" name="sertakan_ipk_kelas" id="form_sertakan_ipk_kelas">
        </form>

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
                            <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextToOptionsBtn">Next</button> {{-- Langsung ke opsi --}}
                        </div>
                    </div>
                </div>
            </div>

            {{-- Modal for File Type Selection (Dihilangkan/Disembunyikan) --}}
            {{-- <div class="modal fade" id="fileTypeModal" tabindex="-1" aria-labelledby="fileTypeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                        <div class="modal-header">
                            <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <p style="font-weight: bold;">Jenis File</p>
                            <div class="form-check w-50 mx-auto text-start">
                                <input class="form-check-input" type="radio" name="fileTypeOptions" id="fileTypePdf" value="pdf" checked>
                                <label class="form-check-label" for="fileTypePdf">PDF</label>
                            </div>
                            <div class="form-check w-50 mx-auto text-start">
                                <input class="form-check-input" type="radio" name="fileTypeOptions" id="fileTypeExcel" value="excel">
                                <label class="form-check-label" for="fileTypeExcel">Excel</label>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn" style="background: linear-gradient(90deg, #6c757d, #999999); color: white; border-radius: 8px;" id="backToStudentBtn">Back</button>
                            <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextToOptionsBtn">Next</button>
                        </div>
                    </div>
                </div>
            </div> --}}

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
                            <button type="button" class="btn" style="background: linear-gradient(90deg, #6c757d, #999999); color: white; border-radius: 8px;" id="backToStudentBtnOptions">Back</button> {{-- Kembali ke pilihan mahasiswa --}}
                            <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="generateReportBtn">Buat Laporan</button>
                        </div>
                    </div>
                </div>
            </div>

        @endif

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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function () {
        console.log("Skrip Halaman Dimuat.");

        if (typeof bootstrap === 'undefined') {
            console.error("KRITIS: Pustaka JavaScript Bootstrap tidak ditemukan.");
            return;
        }

        const studentModalEl = document.getElementById('studentSelectionModal');
        // const fileTypeModalEl = document.getElementById('fileTypeModal'); // Tidak perlu lagi
        const optionsModalEl = document.getElementById('reportOptionsModal');
        const hiddenForm = document.getElementById('hiddenLaporanForm');
        const downloadPdfTrigger = document.getElementById('downloadPdfTrigger'); // Mengganti downloadTrigger

        const studentModal = new bootstrap.Modal(studentModalEl);
        // const fileTypeModal = new bootstrap.Modal(fileTypeModalEl); // Tidak perlu lagi
        const optionsModal = new bootstrap.Modal(optionsModalEl);

        // Listener untuk membuka alur modal (langsung ke pilihan mahasiswa)
        downloadPdfTrigger.addEventListener('click', function(e) {
            e.preventDefault();
            document.getElementById('studentDropdown').value = "";
            studentModal.show();
        });

        // Listener untuk tombol Next (dari pilihan mahasiswa langsung ke opsi laporan)
        document.getElementById('nextToOptionsBtn').addEventListener('click', function() {
            const nim = document.getElementById('studentDropdown').value;
            if(nim) {
                hiddenForm.querySelector('#form_mahasiswa_nim').value = nim;
                // hiddenForm.querySelector('#form_format').value = 'pdf'; // Sudah diatur di HTML hidden input
                studentModal.hide();
                optionsModal.show(); // Langsung tampilkan modal opsi
            } else {
                Swal.fire('Peringatan', 'Harap pilih mahasiswa.', 'warning');
            }
        });

        // Listener untuk tombol Back dari Report Options (kembali ke pilihan mahasiswa)
        document.getElementById('backToStudentBtnOptions').addEventListener('click', () => {
            optionsModal.hide();
            studentModal.show();
        });


        // Listener untuk checkbox di modal terakhir
        document.getElementById('includeCourseGrade').addEventListener('change', function() {
            const container = document.getElementById('finalSemesterOptionsContainer');
            const checkbox = document.getElementById('includeClassIP');
            container.style.display = this.checked ? 'block' : 'none';
            checkbox.disabled = !this.checked;
            if (!this.checked) checkbox.checked = false;
        });

        // Listener untuk tombol GENERATE (YANG SUDAH DIPERBAIKI)
        document.getElementById('generateReportBtn').addEventListener('click', function() {
            // 1. Kumpulkan data
            const includeGrade = document.getElementById('includeCourseGrade').checked;
            hiddenForm.querySelector('#form_sertakan_nilai_matkul').value = includeGrade;
            hiddenForm.querySelector('#form_sertakan_ip_kelas').value = document.getElementById('includeClassIP').checked;
            hiddenForm.querySelector('#form_sertakan_ipk_kelas').value = document.getElementById('includeClassIPK').checked;
            hiddenForm.querySelector('#form_semester_nilai').value = includeGrade ? document.getElementById('finalSemesterDropdown').value : '';

            // 2. Tampilkan notifikasi loading yang akan hilang otomatis
            Swal.fire({
                title: 'Membuat Laporan...',
                text: 'Mohon tunggu, file Anda sedang disiapkan.',
                imageUrl: 'https://media.tenor.com/wpSo-8CrXqUAAAAi/loading-loading-forever.gif',
                imageWidth: 100,
                allowOutsideClick: false,
                showConfirmButton: false,
                timer: 15000, // Waktu maksimal loading 15 detik
                timerProgressBar: true,
            });

            // 3. Kirim form
            hiddenForm.submit();

            // 4. Tutup modal terakhir
            optionsModal.hide();

            // 5. Ganti notifikasi setelah jeda untuk memberi waktu download dimulai
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
            }, 2500); // Tunggu 2.5 detik
        });

        document.getElementById('semester').addEventListener('change', function () {
            const selectedSemester = parseInt(this.value) || 1;
            updateSemesterColumns(selectedSemester);
        });

        function updateSemesterColumns(selectedSemester) {
            for (let i = 1; i <= 8; i++) {
                const cells = document.querySelectorAll(`.semester-${i}`);
                cells.forEach(cell => cell.classList.remove('semester-active'));
            }

            const sksDHeader = document.getElementById('sks-d-header');
            sksDHeader.setAttribute('colspan', selectedSemester || 1);

            for (let i = 1; i <= selectedSemester && i <= 8; i++) {
                const cells = document.querySelectorAll(`.semester-${i}`);
                cells.forEach(cell => cell.classList.add('semester-active'));
            }

            const rows = document.querySelectorAll('tbody tr');
            rows.forEach(row => {
                const sksDCell = row.querySelector('td:nth-child(17)');
                if (selectedSemester === 0) {
                    sksDCell.textContent = '';
                } else {
                    const selectedSemesterCell = row.querySelector(`.semester-${selectedSemester}`);
                    sksDCell.textContent = selectedSemesterCell ? selectedSemesterCell.textContent : '';
                }
            });
        }

        updateSemesterColumns({{ $semester }});
    });
    </script>
    @endpush

@endsection
