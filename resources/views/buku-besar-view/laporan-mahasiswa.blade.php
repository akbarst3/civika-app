{{-- File: resources/views/pdf/laporan_mahasiswa.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Akademik - {{ $mahasiswa->nama_mhs }}</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .header img { width: 60px; height: auto; position: absolute; left: 20px; top: 10px; }
        .header h3, .header p { margin: 2px 0; }
        .info-table { width: 100%; border-collapse: collapse; margin-bottom: 15px; }
        .info-table td { padding: 4px; }
        .font-b { font-weight: bold; }
        .grades-table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        .grades-table th, .grades-table td { padding: 6px; border: 1px solid #000; text-align: center; }
        .grades-table th { background-color: #f2f2f2; }
        .grades-table .text-left { text-align: left; }
        .summary-table { width: 50%; border-collapse: collapse; margin-top: 15px; }
        .summary-table td { padding: 4px; }
        .footer { text-align: right; margin-top: 50px; }
        .footer .signature-space { height: 60px; }
    </style>
</head>
<body>
    <div class="header">
        {{-- Jika path logo asset() tidak berfungsi di DomPDF, gunakan public_path() --}}
        {{-- <img src="{{ public_path('logo.png') }}" alt="Logo"> --}}
        <h3>LAPORAN AKADEMIK MAHASISWA</h3>
        <p>POLITEKNIK NEGERI BANDUNG</p>
    </div>

    <table class="info-table">
        <tr>
            <td width="120px">NIM</td>
            <td width="10px">:</td>
            <td class="font-b">{{ $mahasiswa->nim }}</td>
        </tr>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td class="font-b">{{ $mahasiswa->nama_mhs }}</td>
        </tr>
        <tr>
            <td>Program Studi</td>
            <td>:</td>
            <td class="font-b">{{ $mahasiswa->kelas->prodi->nama_prodi ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td class="font-b">{{ $mahasiswa->kelas->nama_kelas ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td>IPK (Kumulatif)</td>
            <td>:</td>
            <td class="font-b">{{ number_format($ipk, 2) }}</td>
        </tr>
    </table>

    {{-- BAGIAN OPSIONAL: NILAI SEMESTER --}}
    @if(isset($include_nilai_semester) && $include_nilai_semester)
        <hr>
        <h4>Rincian Nilai Semester {{ $semester }}</h4>

        <table class="grades-table">
            <thead>
                <tr>
                    <th width="5%">NO</th>
                    <th class="text-left">MATA KULIAH</th>
                    <th width="10%">SKS</th>
                    <th width="10%">NILAI</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($nilai_semester as $index => $item)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td class="text-left">{{ $item['nama_matkul'] }}</td>
                        <td>{{ $item['sks'] }}</td>
                        <td>{{ $item['nilai'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">Data nilai untuk semester ini tidak ditemukan.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <table class="summary-table">
            <tbody>
                <tr>
                    <td width="200px">Jumlah Satuan Kredit Semester (SKS)</td>
                    <td width="10px">:</td>
                    <td class="font-b">{{ $total_sks_semester }}</td>
                </tr>
                <tr>
                    <td>Indeks Prestasi (IP) Semester Ini</td>
                    <td>:</td>
                    <td class="font-b">{{ number_format($ip_semester, 2) }}</td>
                </tr>
                {{-- BAGIAN OPSIONAL: IP KELAS --}}
                @if(isset($include_ip_kelas) && $include_ip_kelas)
                <tr>
                    <td>IP Rata-rata Kelas</td>
                    <td>:</td>
                    <td class="font-b">{{ number_format($rata_rata_ip_kelas, 2) }}</td>
                </tr>
                @endif
                {{-- BAGIAN OPSIONAL: IPK KELAS --}}
                @if(isset($include_ipk_kelas) && $include_ipk_kelas)
                <tr>
                    <td>IPK Rata-rata Kelas</td>
                    <td>:</td>
                    <td class="font-b">{{ number_format($rata_rata_ipk_kelas, 2) }}</td>
                </tr>
                @endif
            </tbody>
        </table>
    @endif

    <div class="footer">
        Bandung, {{ $current_date }}<br>
        Dosen Wali,
        <div class="signature-space"></div>
        <div class="font-b">(___________________________)</div>
        <div>NIP.</div>
    </div>
</body>
</html>
