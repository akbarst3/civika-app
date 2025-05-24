<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        @import url('https://fonts.cdnfonts.com/css/times-new-roman');
        body { 
            font-family: 'Times New Roman', serif; 
            font-size: 8px; 
            margin: 20px; 
            position: relative;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px; 
            margin-left: 0; 
            margin-right: 0; 
        }
        th, td { 
            border: 1px solid black; 
            padding: 4px; 
            text-align: left; 
        }
        th { 
            background-color: #9eb6dd;
            text-align: center;
        }
        td {
            background-color: rgb(175, 216, 175);
        }

        th:nth-child(1), td:nth-child(1) { width: 4%; }
        th:nth-child(2), td:nth-child(2) { width: 6%; }
        th:nth-child(3), td:nth-child(3) { width: 9%; }
        th:nth-child(4), td:nth-child(4) { width: 10%; }
        th:nth-child(5), td:nth-child(5) { width: 10%; }
        th:nth-child(6), td:nth-child(6) { width: 7%; }
        th:nth-child(7), td:nth-child(7) { width: 10%; }
        th:nth-child(8), td:nth-child(8) { width: 7%; }
        th:nth-child(9), td:nth-child(9) { width: 10%; }
        th:nth-child(10), td:nth-child(10) { width: 7%; }
        th:nth-child(11), td:nth-child(11) { width: 10%; }
        th:nth-child(12), td:nth-child(12) { width: 7%; }
        
        .footer { 
            margin-top: 20px; 
            position: absolute; 
            right: 0; 
            width: auto; 
        }
        .footer-table { 
            border-collapse: collapse; 
            border: none; 
        }
        .footer-table td { 
            border: none; 
            padding: 0; 
            text-align: left; 
            background-color: white;
        }
        .footer-table p { 
            margin: 0; 
            padding-top: 0pt; 
            padding-bottom: 6pt; 
            line-height: 1.5; 
            font-size: 8pt; 
        }
        .signature-gap { 
            height: 80px; 
        }
        
        @page { 
            size: A4; 
            margin: 15mm;
        }
        table { 
            page-break-inside: auto; 
        }
        tr { 
            page-break-inside: avoid; 
            page-break-after: auto; 
        }
    </style>
</head>
<body>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>NIM</th>
                <th>Nama Mahasiswa</th>
                <th>Nama Perusahaan</th>
                <th>Pembimbing 1</th>
                <th>NIDN</th>
                <th>Pembimbing 2</th>
                <th>NIDN</th>
                <th>Penguji 1</th>
                <th>NIDN</th>
                <th>Penguji 2</th>
                <th>NIDN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($data as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item['nim'] }}</td>
                    <td>{{ $item['nama_mhs'] }}</td>
                    <td>{{ $item['nama_perusahaan'] }}</td>
                    <td>{{ $item['pembimbing_1'] ?? '-' }}</td>
                    <td>{{ $item['nidn_pembimbing_1'] ?? '-' }}</td>
                    <td>{{ $item['pembimbing_2'] ?? '-' }}</td>
                    <td>{{ $item['nidn_pembimbing_2'] ?? '-' }}</td>
                    <td>{{ $item['penguji_1'] ?? '-' }}</td>
                    <td>{{ $item['nidn_penguji_1'] ?? '-' }}</td>
                    <td>{{ $item['penguji_2'] ?? '-' }}</td>
                    <td>{{ $item['nidn_penguji_2'] ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table">
            <tr>
                <td><p>Program Studi Teknik Informatika {{ $prodi->nama_prodi ?? '' }}</p></td>
            </tr>
            <tr>
                <td><p>Ketua,</p></td>
            </tr>
            <tr>
                <td class="signature-gap"></td>
            </tr>
            <tr>
                <td><p>{{ $kaprodi->nama_dosen ?? 'Nama Dosen' }}</p></td>
            </tr>
            <tr>
                <td><p>NIP {{ $kaprodi->nip ?? '000000000' }}</p></td>
            </tr>
        </table>
    </div>
</body>
</html>