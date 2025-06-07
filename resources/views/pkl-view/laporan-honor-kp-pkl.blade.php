<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <style>
        @import url('https://fonts.cdnfonts.com/css/times-new-roman');

        body {
            font-family: 'Times New Roman', serif;
            font-size: 12pt;
            margin: 20px;
            line-height: 1.5;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h3,
        .header h4 {
            margin: 0;
            padding-top: 0pt;
            padding-bottom: 6pt;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 10pt;
            line-height: 1.5;
        }

        th,
        td {
            border: 1px solid black;
            padding: 6px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        .footer {
            margin-top: 20px;
            width: 100%;
        }

        .footer-table-container {
            width: 100%;
            border-collapse: collapse;
            border: none;
            padding-right: 20px;
        }

        .footer-table-container td {
            border: none;
            padding: 0;
            vertical-align: top;
        }

        .footer-left {
            width: 100%;
            text-align: left;
        }

        .footer-left p {
            margin: 0;
            padding-top: 0pt;
            padding-bottom: 6pt;
            line-height: 1.5;
        }

        .footer-table {
            width: 100%;
            border-collapse: collapse;
            border: none;
            text-align: left;
            margin-left: 40px;
        }

        .footer-table td {
            border: none;
            padding: 0;
            text-align: left;
        }

        .footer-table p {
            margin: 0;
            padding-top: 0pt;
            padding-bottom: 6pt;
            line-height: 1.5;
        }

        .signature-gap {
            height: 80px;
        }

        @page {
            size: A4;
            margin: 15mm;
        }
    </style>
</head>

<body>
    <div class="header">
        <h3>REKAPITULASI BIMBINGAN DAN PENGUJI SIDANG KP/PKL</h3>
        <h4>
            PROGRAM STUDI TEKNIK INFORMATIKA
            {{ $prodiType }}
            (ANGKATAN {{ request()->angkatan }})
        </h4>
        <h4>DEPARTEMEN TEKNIK KOMPUTER DAN INFORMATIKA</h4>
        <h4>TAHUN AKADEMIK {{ $tahunAkademik }}</h4>
    </div>

    <table>
        <thead>
            <tr>
                <th rowspan="2" style="width: 5%;">No</th>
                <th rowspan="2" style="width: 15%;">NIP</th>
                <th rowspan="2" style="width: 20%;">Nama Dosen</th>
                <th colspan="2" style="width: 20%;">Pembimbing</th>
                <th rowspan="2" style="width: 10%;">JML</th>
                <th colspan="2" style="width: 20%;">Penguji</th>
                <th rowspan="2" style="width: 10%;">JML</th>
            </tr>
            <tr>
                <th style="width: 10%;">1</th>
                <th style="width: 10%;">2</th>
                <th style="width: 10%;">1</th>
                <th style="width: 10%;">2</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $index => $item)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->nip ?? '-' }}</td>
                    <td>{{ $item->nama_dosen }}</td>
                    <td>{{ $item->pembimbing_1_count }}</td>
                    <td>{{ $item->pembimbing_2_count }}</td>
                    <td>{{ $item->pembimbing_1_count + $item->pembimbing_2_count }}</td>
                    <td>{{ $item->penguji_1_count }}</td>
                    <td>{{ $item->penguji_2_count }}</td>
                    <td>{{ $item->penguji_1_count + $item->penguji_2_count }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <table class="footer-table-container">
            <tr>
                <td style="width: 50%;">
                    <div class="footer-left">
                        <p>Departemen Teknik Komputer dan Informatika</p>
                        <p>Ketua,</p>
                        <p>Sekretaris II,</p>
                        <p class="signature-gap"></p>
                        <p>{{ $sekretaris->nama_dosen ?? 'Nama Dosen' }}</p>
                        <p>NIP {{ $sekretaris->nip ?? '000000000' }}</p>
                    </div>
                </td>
                <td style="width: 30%;">
                    <table class="footer-table">
                        <tr>
                            <td>
                                <p>Program Studi Teknik Informatika {{ $prodi->nama_prodi ?? '' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>Ketua,</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p class="signature-gap"> </p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>{{ $kaprodi->nama_dosen ?? 'Nama Dosen' }}</p>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <p>NIP {{ $kaprodi->nip ?? '000000000' }}</p>
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
