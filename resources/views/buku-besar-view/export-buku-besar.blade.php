<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10pt;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid black;
            padding: 4px 8px;
            text-align: left;
            vertical-align: top;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
            text-align: center;
            white-space: nowrap; /* Mencegah header kolom wrap */
        }
        .header-title {
            text-align: center;
            font-weight: bold;
            font-size: 12pt;
        }
        .text-center {
            text-align: center;
        }
        .logo-placeholder {
            text-align: center;
            vertical-align: middle;
            font-size: 8pt;
            /* Anda bisa menambahkan styling untuk gambar di sini, misal: */
            /* background-image: url('path/to/your/polban_logo.png'); */
            /* background-size: contain; */
            /* background-repeat: no-repeat; */
            /* background-position: center; */
        }
    </style>
</head>
<body>

    <table>
        <thead>
            <tr>
                <td colspan="2" rowspan="2" class="logo-placeholder">
                    <img src="{{ asset('path/to/your/polban_logo.png') }}" alt="Logo Polban" style="max-width: 80px; max-height: 80px;">
                    </td>
                <td colspan="{{ $totalKolom-2 }}" class="header-title" style="text-align: center;">POLITEKNIK NEGERI BANDUNG</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom-2 }}" class="header-title" style="text-align: center;">BUKU BESAR PRESTASI MAHASISWA</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}" style="text-align: center;">TAHUN AKADEMIK: {{ $tahun_akademik ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}" style="text-align: center;">SEMESTER: {{ $semester ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}" style="text-align: center;">PROGRAM STUDI: 
                    @php
                        $programStudiNamaLengkap = '-'; 
                        if (isset($program_studi)) {
                            if ($program_studi == 1) {
                                $programStudiNamaLengkap = 'DIPLOMA 3';
                            } elseif ($program_studi == 2) {
                                $programStudiNamaLengkap = 'SARJANA TERAPAN';
                            } else {
                                // Fallback jika program_studi bukan 1 atau 2, cari dari daftar prodi
                                if (isset($prodis)) {
                                    foreach($prodis as $p) {
                                        if ($p->kode_prodi == $program_studi) {
                                            $programStudiNamaLengkap = $p->nama_prodi;
                                            break;
                                        }
                                    }
                                }
                            }
                        }
                        echo $programStudiNamaLengkap;
                    @endphp
                </td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}" style="text-align: center;">PROGRAM STUDI: TEKNIK INFORMATIKA</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}" style="text-align: center;">KELAS/ANGKATAN: {{ $kelasTerpilih ?? '-' }}-JTK/{{ $tahun ?? '-' }}</td>
            </tr>
            <tr>
                <td colspan="{{ $totalKolom }}">&nbsp;</td>
            </tr>

            <tr>
                    {{-- Baris 1: Header Utama --}}
                    <th rowspan="4">NO</th>
                    <th rowspan="4">NIM</th>
                    <th rowspan="4">NAMA</th>
                    <th colspan="{{ count($mataKuliahs) }}">MATA KULIAH</th>
                    <th colspan="{{ $program_studi == 1 ? 6 : 8 }}" rowspan="2">JUMLAH SKS NILAI D SEMESTER</th>
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
                    {{-- Baris 2: Sub-header MATA KULIAH --}}
                    @foreach ($mataKuliahs as $mk)
                        <th>{{ $mk->kode_dosen ?? '-' }}</th>
                    @endforeach
                    {{-- Sel yang digabungkan dari baris 1 akan terus di sini --}}
                </tr>
                <tr class="highlight">
                    {{-- Baris 3: Sub-header MATA KULIAH --}}
                    @foreach ($mataKuliahs as $mk)
                        <th>{{ $mk->kode_matkul }}</th>
                    @endforeach
                    {{-- Sub-header JUMLAH SKS NILAI D SEMESTER --}}
                    @for ($i = 1; $i <= ($numSemestersSKS_D ?? 8); $i++)
                        <th class="text-center">SKS D</th>
                    @endfor
                    {{-- Sel yang digabungkan dari baris 1 akan terus di sini --}}
                </tr>
                <tr class="highlight">
                    {{-- Baris 4: Sub-header MATA KULIAH --}}
                    @foreach ($mataKuliahs as $mk)
                        <th>{{ $mk->jumlah_sks }}</th>
                    @endforeach
                    {{-- Sub-header JUMLAH SKS NILAI D SEMESTER --}}
                    @for ($s = 1; $s <= ($program_studi == 1 ? 6 : 8); $s++)
                        <th class="text-center">{{ ($s) }}</th>
                    @endfor
                    {{-- Sub-header KUMULATIF --}}
                    <th>SKS D</th>
                    <th>NxB</th>
                    {{-- Sub-header IP SEMESTER --}}
                    <th>LALU</th>
                    <th>SEKARANG</th>
                    {{-- Sel yang digabungkan dari baris 1 akan terus di sini --}}
                </tr>
            </thead>
            <tbody>
                {{-- Baris Data untuk setiap Mahasiswa --}}
                @foreach($data as $mhs)
                    <tr>
                        {{-- NO, NIM, NAMA --}}
                        <td class="text-center">{{ $mhs['no'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['nim'] ?? '-' }}</td>
                        <td>{{ $mhs['nama_mhs'] ?? '-' }}</td>

                        {{-- Data MATA KULIAH (indeks_nilai) --}}
                        @foreach ($mataKuliahs as $mk)
                            @php
                                $nilai = collect($mhs['nilai_per_matkul'])->firstWhere('kode_matkul', $mk->kode_matkul);
                            @endphp
                            <td class="text-center">{{ $nilai['indeks_nilai'] ?? '-' }}</td>
                        @endforeach

                        {{-- Data JUMLAH SKS NILAI D SEMESTER --}}
                        @for ($s = 1; $s <= ($program_studi == 1 ? 6 : 8); $s++)
                            <td class="text-center">{{ $mhs['semester_sks'][$s] ?? '-' }}</td>
                        @endfor

                        {{-- Data KUMULATIF --}}
                        <td class="text-center">{{ $mhs['sks_d'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['nilai_bobot'] ?? '-' }}</td>

                        {{-- Data IP SEMESTER --}}
                        <td class="text-center">{{ $mhs['ip_semester']['lalu'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['ip_semester']['sekarang'] ?? '-' }}</td>

                        {{-- Data IPK --}}
                        <td class="text-center">{{ $mhs['ipk'] ?? '-' }}</td>

                        {{-- Data Absensi --}}
                        <td class="text-center">{{ $mhs['jml_sakit'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['jml_izin'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['jml_alfa'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['jml'] ?? '-' }}</td>

                        {{-- Data Lainnya --}}
                        <td class="text-center">{{ $mhs['nilai_penghayatan'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['status'] ?? '-' }}</td>
                        <td class="text-center">{{ $mhs['keterangan'] ?? '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
    </table>

</body>
</html>