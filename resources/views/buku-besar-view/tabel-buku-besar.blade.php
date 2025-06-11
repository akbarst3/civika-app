@extends('layouts.app')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="container mt-5">
        <h2>History Buku Besar</h2>
        {{-- Add a form here to enable filtering --}}
        <form id="filterForm" method="GET" action="{{ route('buku-besar') }}">
            <div class="row mb-3">
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
                    <label for="kelas" class="form-label">Kelas</label>
                    <select class="form-select custom-dropdown" id="kelas" name="kelas_id">
                        <option value="" hidden>Silakan Pilih Kelas</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>
            </div>

            <div class="row mb-2">
                <div class="col-md-4">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select custom-dropdown" id="semester" name="semester">
                        <option value="" hidden>Silakan Pilih Semester</option>
                        {{-- Options will be populated by JavaScript --}}
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="search" class="form-label">Pencarian</label>
                    <input type="text" class="form-control custom-search" id="search" name="search" placeholder="Cari mahasiswa..." value="{{ request('search') }}">
                </div>
            </div>
        </form>

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
                        <tr class="highlight">
                            @foreach ($mataKuliahs as $mk)
                                <th>{{ $mk->kode_dosen ?? '-' }}</th>
                            @endforeach
                        </tr>
                        <tr class="highlight">
                            @foreach ($mataKuliahs as $mk)
                                <th>{{ $mk->kode_matkul }}</th>
                            @endforeach
                                @for ($i = 1; $i <= 8; $i++)
                                    <th class="semester-{{ $i }}"></th> {{-- These will be dynamically filled via JS if needed, or left blank as per original --}}
                                @endfor
                        </tr>
                        <tr class="highlight">
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
                                @foreach ($mataKuliahs as $mk)
                                    @php
                                        $nilai = collect($mhs['nilai_per_matkul'])->firstWhere('kode_matkul', $mk->kode_matkul);
                                    @endphp
                                    <td>{{ $nilai['indeks_nilai'] ?? '-' }}</td>
                                @endforeach
                                @for ($i = 1; $i <= 8; $i++)
                                    <td class="semester-{{ $i }} {{ $i <= $semester ? 'semester-active' : '' }}">
                                        @if ($i == $semester)
                                            {{ $mhs['jumlah_d'] ?? '' }}
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
                                $dosenForMk = []; // Store unique dosens for this matkul
                                foreach ($data as $mhs) {
                                    $nilai = $mhs['nilai_per_matkul']->where('kode_matkul', $mk->kode_matkul)->first();
                                    if ($nilai) {
                                        if (!in_array($nilai['nama_dosen'], $dosenForMk) && $nilai['nama_dosen'] != '-') {
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

        <style>
            /* Your existing styles remain */
            .table-container {
                max-height: 600px;
                overflow-y: auto;
            }

            .custom-dropdown {
                width: 180px;
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

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const programStudiDropdown = document.getElementById('program_studi');
                const tahunDropdown = document.getElementById('tahun');
                const kelasDropdown = document.getElementById('kelas');
                const semesterDropdown = document.getElementById('semester');
                const filterForm = document.getElementById('filterForm');

                // Get initial values from PHP Blade, which reflect the current request or defaults
                const initialProgramStudi = "{{ request('program_studi', $program_studi) }}";
                const initialTahun = "{{ request('tahun', $tahun) }}";
                const initialKelas = "{{ request('kelas_id', $kelas) }}";
                const initialSemester = "{{ request('semester', $semester) }}";

                // Raw kelas data from PHP
                const allKelasData = @json($kelasList);

                function populateKelasDropdown(selectedProgramStudi, selectedTahun) {
                    kelasDropdown.innerHTML = '<option value="" hidden>Silakan Pilih Kelas</option>';
                    const filteredKelas = allKelasData.filter(kls => {
                        return (selectedProgramStudi === '' || kls.kode_prodi == selectedProgramStudi) &&
                               (selectedTahun === '' || kls.angkatan == selectedTahun);
                    });

                    // Add unique class names (A, B, C, etc.) to the dropdown
                    const uniqueKelasNames = [...new Set(filteredKelas.map(kls => kls.nama_kelas))].sort();

                    uniqueKelasNames.forEach(nama_kelas => {
                        // Find the first matching class to get its ID, as multiple classes might have the same name but different prodi/angkatan
                        const kls = filteredKelas.find(k => k.nama_kelas === nama_kelas);
                        if (kls) {
                            const option = document.createElement('option');
                            option.value = kls.id; // Use the actual class ID
                            option.text = `${nama_kelas}`; // Just show A, B, C
                            if (kls.id == initialKelas) {
                                option.selected = true;
                            }
                            kelasDropdown.appendChild(option);
                        }
                    });
                }

                function populateSemesterDropdown(selectedProgramStudi) {
                    semesterDropdown.innerHTML = '<option value="" hidden>Silakan Pilih Semester</option>';
                    let maxSemester = 8; // Default for D4 (assuming kode_prodi '1' is D4 based on typical setups, adjust if needed)

                    // You need to map program_studi kode to its name or directly to max semesters
                    // Assuming 'D3' has a specific kode_prodi, let's say '2' for example
                    const selectedProdi = @json($prodis).find(prodi => prodi.kode_prodi == selectedProgramStudi);
                    if (selectedProdi && selectedProdi.nama_prodi.toLowerCase().includes('D3')) {
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
                    // Adjust colspan based on the selected semester
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

                semesterDropdown.addEventListener('change', function() {
                    updateSemesterColumns(parseInt(this.value));
                    filterForm.submit();
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
                programStudiDropdown.value = initialProgramStudi;
                tahunDropdown.value = initialTahun;
                kelasDropdown.value = initialKelas;
                semesterDropdown.value = initialSemester;

            });
        </script>
    @endsection