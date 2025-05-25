<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload File Excel Akademik</title>
    <style>
        body { font-family: sans-serif; margin: 20px; }
        .container { max-width: 600px; margin: auto; padding: 20px; border: 1px solid #ddd; border-radius: 5px; }
        .alert { padding: 15px; margin-bottom: 20px; border: 1px solid transparent; border-radius: 4px; }
        .alert-success { color: #155724; background-color: #d4edda; border-color: #c3e6cb; }
        .alert-danger { color: #721c24; background-color: #f8d7da; border-color: #f5c6cb; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; }
        input[type="file"] { display: block; }
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        button:hover { background-color: #0056b3; }
        ul { list-style-type: none; padding-left: 0; }
        ul li { color: #721c24; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Upload Data Akademik dari Excel</h1>

        @if (session('success_messages') && is_array(session('success_messages')) && count(session('success_messages')) > 0)
            <div class="alert alert-success">
                <strong>Laporan Impor Berhasil:</strong>
                <ul>
                    @foreach (session('success_messages') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('error_messages') && is_array(session('error_messages')) && count(session('error_messages')) > 0)
            <div class="alert alert-danger">
                 <strong>Laporan Kegagalan Impor:</strong>
                <ul>
                    @foreach (session('error_messages') as $message)
                        <li>{{ $message }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
        
        @if (session('error')) {{-- Untuk error umum --}}
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif


        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Terjadi kesalahan validasi input:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('excel.import') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            <div class="form-group">
                <label for="excel_files">Pilih File Excel (.xlsx, .xls, .csv) - Bisa lebih dari satu:</label>
                {{-- Tambahkan '[]' pada name dan atribut 'multiple' --}}
                <input type="file" name="excel_files[]" id="excel_files" accept=".xlsx, .xls, .csv" required multiple>
            </div>

            <button type="submit">Upload dan Proses File</button>
        </form>
    </div>
</body>
</html>