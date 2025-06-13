@extends('layouts.app-dosen')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>History Buku Besar</h2>
            <div class="dropdown" style="margin-top: -3px;">
                <button class="btn btn-secondary dropdown-toggle rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(90deg, #666666, #999999); color: white; width: 30px; height: 30px; padding: 0; border: none; display: flex; align-items: center; justify-content: center;">
                    <span class="three-dots" style="font-size: 18px; line-height: 1;">⋮</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border-radius: 8px; padding: 0;">
                    <li><a class="dropdown-item" href="#" id="downloadTrigger" style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">Download</a></li>
                </ul>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-2 mb-2">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select custom-dropdown w-100" id="semester">
                    @for ($i = 1; $i <= $totalSemesters; $i++)
                        <option value="{{ $i }}" {{ $i == $semester ? 'selected' : '' }}>{{ $i }}</option>
                    @endfor
                </select>
            </div>
            <div class="col-12 col-sm-6 col-md-2 mb-2">
                <label for="search" class="form-label"> </label>
                <input type="text" class="form-control custom-search w-100" id="search" placeholder="Search">
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-12 col-sm-6 col-md-2 mb-2 d-none">
                <label for="program_studi" class="form-label">Program Studi</label>
                <select class="form-select custom-dropdown w-100" id="program_studi">
                    <option value="" hidden>Silakan Pilih Program Studi</option>
                    @foreach ($prodis as $prodi)
                    <option value="{{ $prodi->id }}" {{ $prodi->id == $program_studi ? 'selected' : '' }}>
                        {{ $prodi->nama_prodi }}
                    </option>
                    @endforeach
                </select>
            </div>
        </div>

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

        <!-- Modal for File Type Selection -->
        <div class="modal fade" id="fileTypeModal" tabindex="-1" aria-labelledby="fileTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-bg" style="border-radius: 10px;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-weight: bold;">Jenis File</p>
                        <p style="font-size: 12px; color: #666;">*Harap pilih salah satu jenis file untuk diunduh.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3" style="width: 77%;">
                            <label style="margin-left: 100px;">PDF</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="radio" name="fileType" id="pdf" value="pdf" checked>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 77%;">
                            <label style="margin-left: 100px;">Excel</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="radio" name="fileType" id="excel" value="excel">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" data-bs-dismiss="modal">Batal</button>
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextModalBtn">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Report Options -->
        <div class="modal fade" id="reportOptionsModal" tabindex="-1" aria-labelledby="reportOptionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-bg" style="border-radius: 10px;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-weight: bold;">Pilihan Tambahan</p>
                        <p style="font-size: 12px; color: #666;">*Anda dapat memilih beberapa pilihan ataupun tidak memilih sama sekali.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3" style="width: 70%;">
                            <label>Sertakan Nilai Mata Kuliah</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeCourseGrade">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 70%;">
                            <label>Sertakan Nilai IP Kelas</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeClassIP">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 70%;">
                            <label>Sertakan Nilai IPK Kelas</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeClassIPK">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" id="backButton" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#fileTypeModal">Back</button>
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="generateReportBtn">Buat Laporan</button>
                    </div>
                </div>
            </div>
        </div>

        <style>
            .table-container {
                max-height: 600px;
                overflow-y: auto;
            }

            .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
            }

            .custom-dropdown {
                width: 100%;
                max-width: 180px; /* ✅ Responsive: tetap sempit di desktop, tapi fleksibel di mobile */
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
        </style>

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                // Trigger untuk membuka modal File Type dari dropdown
                document.getElementById('downloadTrigger').addEventListener('click', function() {
                    resetAndShowModal('#fileTypeModal');
                    resetReportOptions(); // Reset checkbox saat membuka alur baru
                });

                document.getElementById('nextModalBtn').addEventListener('click', function() {
                    $('#fileTypeModal').modal('hide');
                    $('#reportOptionsModal').modal('show');
                });

                document.getElementById('generateReportBtn').addEventListener('click', function() {
                    $('#reportOptionsModal').modal('hide');
                    Swal.fire({
                        icon: 'success',
                        title: 'Succeed',
                        text: 'Laporan berhasil disimpan.',
                        confirmButtonText: 'OK',
                        confirmButtonColor: '#32BB35',
                        customClass: {
                            confirmButton: 'btn',
                            popup: 'swal2-custom'
                        }
                    }).then((result) => {
                        if (result.isConfirmed) {
                            // Reset state modal dan backdrop
                            $('.modal-backdrop').remove();
                            $('body').removeClass('modal-open');
                            $('body').css('overflow', 'auto');
                            // Reinisialisasi semua modal
                            $('#fileTypeModal').modal('dispose');
                            $('#reportOptionsModal').modal('dispose');
                            $('#fileTypeModal').modal({ show: false });
                            $('#reportOptionsModal').modal({ show: false });
                            resetReportOptions(); // Reset checkbox setelah laporan dibuat
                        }
                    });
                });

                // Pastikan tombol Back berfungsi
                const backButton = document.getElementById('backButton');
                if (backButton) {
                    backButton.addEventListener('click', function(e) {
                        e.preventDefault();
                        $('#reportOptionsModal').modal('hide').then(() => {
                            resetAndShowModal('#fileTypeModal');
                            resetReportOptions(); // Reset checkbox saat kembali ke File Type
                        });
                        console.log('Tombol Back diklik, mencoba menampilkan #fileTypeModal');
                    });
                } else {
                    console.log('Tombol Back tidak ditemukan!');
                }
            });

            // Fungsi untuk reset dan menampilkan modal
            function resetAndShowModal(modalId) {
                $(modalId).modal('dispose'); // Hapus instance modal
                $(modalId).modal({ show: false }); // Reinisialisasi modal
                $(modalId).modal('show'); // Tampilkan modal
            }

            // Fungsi untuk reset checkbox di Report Options
            function resetReportOptions() {
                document.getElementById('includeCourseGrade').checked = false;
                document.getElementById('includeClassIP').checked = false;
                document.getElementById('includeClassIPK').checked = false;
            }

            // Tambahkan gaya CSS untuk gradasi hijau pada tombol OK SweetAlert
            const style = document.createElement('style');
            style.innerHTML = `
                .swal2-custom .swal2-confirm {
                    background: linear-gradient(90deg, #32BB35, #8BE52E) !important;
                    border-radius: 8px !important;
                    color: white !important;
                    padding: 10px 20px !important;
                }
                .swal2-custom .swal2-confirm:hover {
                    background: linear-gradient(90deg, #2A9D34, #76C517) !important;
                }
            `;
            document.head.appendChild(style);

            document.getElementById('program_studi').addEventListener('change', function () {
                const programStudi = this.value;
                const semesterDropdown = document.getElementById('semester');

                semesterDropdown.innerHTML = '';

                const maxSemester = programStudi === '1' ? 8 : 6;

                for (let i = 1; i <= maxSemester; i++) {
                    const option = document.createElement('option');
                    option.value = i;
                    option.text = i;
                    if (i === 1) option.selected = true;
                    semesterDropdown.appendChild(option);
                }

                semesterDropdown.dispatchEvent(new Event('change'));
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
                        sksDCell.textContent = '0';
                    } else {
                        const selectedSemesterCell = row.querySelector(`.semester-${selectedSemester}`);
                        sksDCell.textContent = selectedSemesterCell ? selectedSemesterCell.textContent : '0';
                    }
                });
            }

            document.getElementById('program_studi').dispatchEvent(new Event('change'));
            document.getElementById('semester').dispatchEvent(new Event('change'));
        </script>
    @endsection
