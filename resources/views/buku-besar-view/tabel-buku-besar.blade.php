@extends('layouts.app')

@section('title', 'Dashboard')

@section('navbar-content', 'Tabel Buku Besar')

@section('content')
    <div class="container mt-5">
        <h2>History Buku Besar</h2>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="kelas" class="form-label">Kelas</label>
                <select class="form-select custom-dropdown" id="kelas">
                    <option value="2C" selected>2C</option>
                    <option value="2B">2B</option>
                    <option value="2A">2A</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="semester" class="form-label">Semester</label>
                <select class="form-select custom-dropdown" id="semester">
                    <option value="1" selected>1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                    <option value="6">6</option>
                    <option value="7">7</option>
                    <option value="8">8</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="search" class="form-label"> </label>
                <input type="text" class="form-control custom-search" id="search" placeholder="Search">
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-2">
                <label for="tahun" class="form-label">Tahun</label>
                <select class="form-select custom-dropdown" id="tahun">
                    <option value="2021" selected>2021</option>
                    <option value="2020">2020</option>
                </select>
            </div>
            <div class="col-md-2">
                <label for="program_studi" class="form-label">Program Studi</label>
                <select class="form-select custom-dropdown" id="program_studi">
                    <option value="" hidden>Silakan Pilih Program Studi</option>
                    <option value="1">D4 - Teknik Informatika</option>
                    <option value="2">D3 - Teknik Informatika</option>
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
                        <th colspan="8">MATA KULIAH</th>
                        <th id="sks-d-header" colspan="8" rowspan="3">JUMLAH SKS NILAI D SEMESTER</th>
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
                        <th>DU094N</th>
                        <th>DU141P</th>
                        <th>DU153P</th>
                        <th>IG032P</th>
                        <th>KO009N</th>
                        <th>KO016N</th>
                        <th>KO018N</th>
                        <th>KO023N</th>
                    </tr>
                    <tr>
                        <th>21KU1001</th>
                        <th>21KU1007</th>
                        <th>21KU0001</th>
                        <th>21IG1017</th>
                        <th>21IF1001</th>
                        <th>21KU1008</th>
                        <th>21IF1003</th>
                        <th>21IF1002</th>
                    </tr>
                    <tr>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>2</td>
                        <td>4</td>
                        <td>3</td>
                        <td>2</td>
                        <td>3</td>
                        <td class="semester-1">I</td>
                        <td class="semester-2">II</td>
                        <td class="semester-3">III</td>
                        <td class="semester-4">IV</td>
                        <td class="semester-5">V</td>
                        <td class="semester-6">VI</td>
                        <td class="semester-7">VII</td>
                        <td class="semester-8">VIII</td>
                        <td>SKS D</td>
                        <td>NxB</td>
                        <td>LALU</td>
                        <td>SEKARANG</td>

                    </tr>
                </thead>
                <tbody>
                    @for ($i = 1; $i <= 10; $i++)
                        <tr>
                            <td>{{ $i }}</td>
                            <td>22101{{ str_pad($i, 3, '0', STR_PAD_LEFT) }}</td>
                            <td>Nama Mahasiswa {{ $i }}</td>
                            <td>A</td>
                            <td>B</td>
                            <td>AB</td>
                            <td>AB</td>
                            <td>C</td>
                            <td>C</td>
                            <td>A</td>
                            <td>B</td>
                            <td class="semester-1"></td>
                            <td class="semester-2"></td>
                            <td class="semester-3"></td>
                            <td class="semester-4"></td>
                            <td class="semester-5"></td>
                            <td class="semester-6"></td>
                            <td class="semester-7"></td>
                            <td class="semester-8"></td>
                            <td></td>
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
                            <td>-</td>
                        </tr>
                    @endfor
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
                    <tr>
                        <td>1</td>
                        <td>AW0011004</td>
                        <td>[Nama Mata Kuliah]</td>
                        <td>[Nama Dosen]</td>
                        <td>2</td>
                        <td>1</td>
                        <td>3</td>
                        <td>2</td>
                        <td>1</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>
                    <tr>
                        <td>1</td>
                        <td>AW0011004</td>
                        <td>[Nama Mata Kuliah]</td>
                        <td>[Nama Dosen]</td>
                        <td>2</td>
                        <td>1</td>
                        <td>3</td>
                        <td>2</td>
                        <td>1</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr><tr>
                        <td>1</td>
                        <td>AW0011004</td>
                        <td>[Nama Mata Kuliah]</td>
                        <td>[Nama Dosen]</td>
                        <td>2</td>
                        <td>1</td>
                        <td>3</td>
                        <td>2</td>
                        <td>1</td>
                        <td>0</td>
                        <td>0</td>
                        <td>0</td>
                    </tr>


                    <!-- Tambahkan baris lain sesuai kebutuhan -->
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
                    <tr>
                        <td>IP<=2.75</td>
                        <td>4</td>
                    </tr>
                    <tr>
                        <td>2.75<IP>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>IP>3.50</td>
                        <td>4</td>
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
                    <tr>
                        <td>IPK<=2.75</td>
                        <td>9</td>
                    </tr>
                    <tr>
                        <td>2.75<IPK<=3.50</td>
                        <td>20</td>
                    </tr>
                    <tr>
                        <td>IPK>3.50</td>
                        <td>4</td>
                    </tr>
                </tbody>
            </table>
        </div>

    <style>
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
    </style>

   <script>
    // Fungsi untuk mengatur opsi semester berdasarkan program studi
    document.getElementById('program_studi').addEventListener('change', function () {
        const programStudi = this.value;
        const semesterDropdown = document.getElementById('semester');

        // Kosongkan opsi semester sebelumnya
        semesterDropdown.innerHTML = '';

        // Tentukan jumlah semester berdasarkan program studi
        const maxSemester = programStudi === '1' ? 8 : 6; // D4 (1) = 8 semester, D3 (2) = 6 semester

        // Tambahkan opsi semester sesuai program studi
        for (let i = 1; i <= maxSemester; i++) {
            const option = document.createElement('option');
            option.value = i;
            option.text = i;
            if (i === 1) option.selected = true; // Set default to Semester 1
            semesterDropdown.appendChild(option);
        }

        // Trigger change event untuk memperbarui kolom
        semesterDropdown.dispatchEvent(new Event('change'));
    });

    // Fungsi untuk mengatur visibilitas kolom semester dan mengisi jumlah SKS D
    document.getElementById('semester').addEventListener('change', function () {
        const selectedSemester = parseInt(this.value) || 1; // Default ke 1 jika kosong
        updateSemesterColumns(selectedSemester);
    });

    // Fungsi untuk memperbarui visibilitas kolom semester dan mengisi jumlah SKS D
    function updateSemesterColumns(selectedSemester) {
        // Sembunyikan semua kolom semester
        for (let i = 1; i <= 8; i++) {
            const cells = document.querySelectorAll(`.semester-${i}`);
            cells.forEach(cell => cell.classList.remove('semester-active'));
        }

        // Atur colspan untuk header JUMLAH SKS NILAI D SEMESTER
        const sksDHeader = document.getElementById('sks-d-header');
        sksDHeader.setAttribute('colspan', selectedSemester || 1); // Jika tidak ada semester, set colspan ke 1

        // Tampilkan kolom semester dari I hingga semester yang dipilih
        for (let i = 1; i <= selectedSemester && i <= 8; i++) {
            const cells = document.querySelectorAll(`.semester-${i}`);
            cells.forEach(cell => cell.classList.add('semester-active'));
        }

        // Ambil semua baris tabel
        const rows = document.querySelectorAll('tbody tr');

        // Update kolom JUMLAH SKS NILAI D SEMESTER
        rows.forEach(row => {
            const sksDCell = row.querySelector('td:nth-child(17)'); // Kolom JUMLAH SKS NILAI D SEMESTER
            if (selectedSemester === 0) {
                // Jika tidak ada semester yang dipilih, kosongkan atau set nilai default
                sksDCell.textContent = '0';
            } else {
                // Ambil nilai dari kolom semester yang dipilih (misalnya, semester-1, semester-2, dll.)
                const selectedSemesterCell = row.querySelector(`.semester-${selectedSemester}`);
                // Logika untuk menghitung jumlah SKS D (contoh sederhana, sesuaikan dengan data sebenarnya)
                sksDCell.textContent = selectedSemesterCell ? selectedSemesterCell.textContent : '0';
            }
        });
    }

    // Inisialisasi saat halaman dimuat
    document.getElementById('program_studi').dispatchEvent(new Event('change'));
    document.getElementById('semester').dispatchEvent(new Event('change')); // Trigger semester change
    </script>
@endsection

