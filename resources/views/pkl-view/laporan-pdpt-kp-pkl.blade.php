<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        body { 
            font-family: DejaVu Sans, sans-serif; 
            font-size: 8px; 
            margin: 20px; 
        }
        table { 
            width: 95%;
            border-collapse: collapse; 
            margin-top: 10px; 
            margin-right: 15px;
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
        th:nth-child(2), td:nth-child(2) { width: 9%; }
        th:nth-child(3), td:nth-child(3) { width: 12%; }
        th:nth-child(4), td:nth-child(4) { width: 12%; }
        th:nth-child(5), td:nth-child(5) { width: 10%; }
        th:nth-child(6), td:nth-child(6) { width: 7%; }
        th:nth-child(7), td:nth-child(7) { width: 10%; }
        th:nth-child(8), td:nth-child(8) { width: 7%; }
        th:nth-child(9), td:nth-child(9) { width: 10%; }
        th:nth-child(10), td:nth-child(10) { width: 7%; }
        th:nth-child(11), td:nth-child(11) { width: 10%; }
        th:nth-child(12), td:nth-child(12) { width: 7%; }
        
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
</body>
</html>