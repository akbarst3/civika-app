@extends('layouts.app-dosen')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="container mt-5">
        <h2>History Buku Besar - Kelas {{ $kelas->nama_kelas }} {{ $kelas->angkatan }}</h2>
       <form id="filterForm" method="GET" action="{{ route('buku-besar-wali-mahasiswa') }}">
            <div class="row mb-3">
                <div class="col-md-2">
                    <label for="semester" class="form-label">Semester</label>
                    <select class="form-select custom-dropdown" id="semester" name="semester" onchange="this.form.submit()">
                        @for ($i = 1; $i <= $totalSemesters; $i++)
                            <option value="{{ $i }}" {{ $i == request('semester', $semester) ? 'selected' : '' }}>
                                {{ $i }}
                            </option>
                        @endfor
                    </select>
                <!-- Hapus elemen search dan program_studi -->
            </div>
        </form>

    </div>

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

                    {{--
                    <tr class="highlight">
                        @foreach ($data[2]['nilai_per_matkul'] as $mk)
                            <th>{{ $mk['kode_dosen'] }}</th>
                        @endforeach
                    </tr>

                    @for ($i = 1; $i <= 8; $i++)
                        <th class="semester-{{ $i }}">{{ $semester_sks[$i] ?? "" }}</th>
                    @endfor

                    <tr class="highlight">
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk['jumlah_sks'] }}</th>
                        @endforeach

                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk['totalSksAll'] }}</th>
                        @endforeach
                    </tr>
                    --}}

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
                                    {{-- {{ $mhs['semester_sks'][$i] ?? "" }} --}}
                                    @if ($i == $semester)
                                        {{ $mhs['jumlah_d'] ?? "" }}
                                    @else
                                        {{ "" }}
                                    @endif
                                </td>
                            @endfor
                            {{-- @for ($i = 1; $i <= 8; $i++)
                                <td class="semester-{{ $i }} {{ $i <= $semester ? 'semester-active' : '' }}">
                                    {{ $mhs['semester_sks'][$i] ?? 0 }}
                                </td>
                            @endfor --}}
                            {{-- <td>{{ $mhs['jumlah_d'] }}</td> --}}
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
                            {{-- <td>{{ $mhs[''] }}</td> --}}
                            {{-- <td class="semester-1"></td>
                            <td class="semester-2"></td>
                            <td class="semester-3"></td>
                            <td class="semester-4"></td>
                            <td class="semester-5"></td>
                            <td class="semester-6"></td>
                            <td class="semester-7"></td>
                            <td class="semester-8"></td> --}}
                            {{-- <td></td>
                            <td>2.95</td>
                            <td>2.95</td>
                            <td>2.95</td>
                            <td></td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td>0</td>
                            <td></td>
                            <td>LL</td>
                            <td>-</td> --}}
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

    @endif

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
        </style>

        <script>
            // Update tampilan kolom semester saat dropdown semester berubah
            document.getElementById('semester').addEventListener('change', function () {
                const selectedSemester = parseInt(this.value) || 1;
                updateSemesterColumns(selectedSemester);
            });

            function updateSemesterColumns(selectedSemester) {
                // Sembunyikan semua kolom semester
                for (let i = 1; i <= 8; i++) {
                    const cells = document.querySelectorAll(`.semester-${i}`);
                    cells.forEach(cell => cell.classList.remove('semester-active'));
                }

                // Perbarui colspan header
                const sksDHeader = document.getElementById('sks-d-header');
                sksDHeader.setAttribute('colspan', selectedSemester || 1);

                // Tampilkan kolom untuk semester yang aktif
                for (let i = 1; i <= selectedSemester && i <= 8; i++) {
                    const cells = document.querySelectorAll(`.semester-${i}`);
                    cells.forEach(cell => cell.classList.add('semester-active'));
                }

                // Perbarui nilai SKS D
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

            // Inisialisasi tampilan tabel dengan semester default saat halaman dimuat
            updateSemesterColumns({{ $semester }});
        </script>
    @endsection
