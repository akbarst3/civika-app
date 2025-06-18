<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Keterangan</title>
    <style>
        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 12pt;
            line-height: 1.5;
            margin: 2cm;
            text-align: justify;
        }

        .header {
            text-align: center;
            font-weight: bold;
            margin-bottom: 1cm;
        }

        .content {
            margin-bottom: 1cm;
        }

        .signature {
            text-align: center;
            margin-top: 2cm;
        }

        .signature p {
            margin: 0;
        }

        .underline {
            text-decoration: underline;
        }
    </style>
</head>

<body>
    <div class="header">
        <p>SURAT KETERANGAN ORMAWA</p>
        <p>Nomor: 123/KO/KM/01.00/2025</p>
    </div>

    <div class="content">
        <p>Ketua Jurusan Teknik Komputer dan Informatika, menyerangkan mahasiswa di bawah ini:</p>
        <p>Nama: {{ $pdfData['nama'] }}</p>
        <p>NIM: {{ $pdfData['nim'] }}</p>
        <p>IPK: {{ $pdfData['ipk'] }}</p>
        <p>Semester: {{ $pdfData['semester'] }} ({{ $pdfData['smt'] }})</p>
        <br>
        <p>
            Berdasarkan data yang dimiliki Program Studi {{ $pdfData['prodi'] }}, mahasiswa yang bersangkutan selama
            proses perkuliahan semester {{ $pdfData['semester'] }} ({{ $pdfData['smt'] }})
            tahun akademik {{ $pdfData['tahun'] }}, berprestasi baik dan tidak pernah mendapat Surat Peringatan (SP),
            sehingga Jurusan
            rekomendasikan untuk mendapatkan beasiswa <strong>{{ $pdfData['keperluan'] }} Tahun Akademik
                {{ $pdfData['tahun'] }}</strong>, sesuai prosedur dan ketentuan yang berlaku.
        </p>
        <br>
        <p>Demikian surat keterangan ini dibuat agar dipergunakan sebagaimana mestinya.</p>
    </div>
    @isset($src)
        <img src="{{ $src }}" width="100" alt="Logo">
    @endisset

    <div class="signature">
        <p>{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
        <br>
        <p>Ketua Jurusan Teknik Komputer dan Informatika</p>
        <br><br><br>
        <p class="underline">*Nama</p>
        <p>*NIP</p>
    </div>
</body>

</html>
