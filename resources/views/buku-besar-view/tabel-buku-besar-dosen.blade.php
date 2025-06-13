@extends('layouts.app-dosen')

@section('title', 'Buku Besar')

@section('navbar-content', 'Tabel Buku Besar Dosen')

@section('content')
    <div class="container mt-5">
        <div class="d-flex justify-content-between align-items-center">
            <h2>History Buku Besar Dosen</h2>
            <div class="dropdown" style="margin-top: -3px;">
                <button class="btn btn-secondary dropdown-toggle rounded-circle" type="button" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false" style="background: linear-gradient(90deg, #666666, #999999); color: white; width: 30px; height: 30px; padding: 0; border: none; display: flex; align-items: center; justify-content: center;">
                    <span class="three-dots" style="font-size: 18px; line-height: 1;">⋮</span>
                </button>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton" style="border-radius: 8px; padding: 0; border-width: 2px; border-color: #dee2e6;">
                    <li><a class="dropdown-item" href="#" id="downloadTrigger" style="padding: 10px 15px; border-bottom: 1px solid #dee2e6;">Download</a></li>
                    <li><a class="dropdown-item" href="{{ route('buku-besar-wali-mahasiswa') }}" style="padding: 10px 15px;">Ganti Akses ke Wali Dosen</a></li>
                </ul>
            </div>
        </div>
        <div class="table-responsive mt-4">
            <table class="table table-bordered text-center align-middle small">
                <thead>
                    <tr>
                        <th rowspan="4">No</th>
                        <th rowspan="4">NIM</th>
                        <th rowspan="4">Nama</th>
                        <th colspan="{{ $mataKuliahs->count() }}">MATA KULIAH</th>
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
                    </tr>
                    <tr>
                        @foreach ($mataKuliahs as $mk)
                            <th>{{ $mk->jumlah_sks }}</th>
                        @endforeach
                    </tr>
                </thead>
                <tbody>
                    @foreach($data as $mhs)
                        <tr>
                            <td>{{ $mhs['no'] }}</td>
                            <td>{{ $mhs['nim'] }}</td>
                            <td>{{ $mhs['nama_mhs'] }}</td>
                            @foreach ($mataKuliahs as $mk)
                                <td>
                                    @php
                                        $nilai = collect($mhs['nilai_per_matkul'])->firstWhere('kode_matkul', $mk->kode_matkul);
                                    @endphp
                                    {{ $nilai['indeks_nilai'] ?? '-' }}
                                </td>
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if ($data->isEmpty())
            <div class="alert alert-info mt-3">
                Tidak ada data nilai untuk semester ini.
            </div>
        @endif

        <!-- Modal for Student Selection -->
        <div class="modal fade" id="studentSelectionModal" tabindex="-1" aria-labelledby="studentSelectionModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #00008B;">Pilih Mahasiswa</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-weight: bold;">Nama Mahasiswa</p>
                        <p style="font-size: 12px; color: #666;">*Harap pilih nama mahasiswa.</p>
                        <div class="mb-3" style="width: 70%; margin: 0 auto;">
                            <select class="form-select" id="studentDropdown">
                                <option value="" selected disabled>Pilih Nama Mahasiswa</option>
                                <option value="1">Andi Santoso</option>
                                <option value="2">Budi Wijaya</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextToFileTypeBtn">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for File Type Selection -->
        <div class="modal fade" id="fileTypeModal" tabindex="-1" aria-labelledby="fileTypeModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-weight: bold;">Jenis File</p>
                        <p style="font-size: 12px; color: #666;">*Harap pilih salah satu jenis file untuk diunduh.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3" style="width: 77%;">
                            <label style="margin-left: 100px;">PDF</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="radio" name="fileType" id="pdf" value="pdf" checked>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 77%;">
                            <label style="margin-left: 100px;">Excel</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="radio" name="fileType" id="excel" value="excel">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" id="backToStudentBtn" data-bs-toggle="modal" data-bs-target="#studentSelectionModal">Back</button>
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="nextModalBtn">Next</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal for Report Options -->
        <div class="modal fade" id="reportOptionsModal" tabindex="-1" aria-labelledby="reportOptionsModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content modal-custom-bg" style="border-radius: 10px; border-width: 2px; border-color: #dee2e6;">
                    <div class="modal-header">
                        <h5 class="modal-title" style="color: #00008B;">Buat Laporan</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <p style="font-weight: bold;">Pilihan Tambahan</p>
                        <p style="font-size: 12px; color: #666;">*Anda dapat memilih beberapa pilihan ataupun tidak memilih sama sekali.</p>
                        <div class="d-flex justify-content-between align-items-center mt-3" style="width: 70%;">
                            <label>Sertakan Nilai Mata Kuliah</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeCourseGrade">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 70%;">
                            <label>Sertakan Nilai IP Kelas</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeClassIP">
                            </div>
                        </div>
                        <div class="d-flex justify-content-between align-items-center mt-2" style="width: 70%;">
                            <label>Sertakan Nilai IPK Kelas</label>
                            <div class="form-check" style="margin-left: 50px;">
                                <input class="form-check-input" type="checkbox" id="includeClassIPK">
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #E11818, #FF6C6C); color: white; border-radius: 8px;" id="backButton" data-bs-dismiss="modal" data-bs-toggle="modal" data-bs-target="#fileTypeModal">Back</button>
                        <button type="button" class="btn" style="background: linear-gradient(90deg, #32BB35, #8BE52E); color: white; border-radius: 8px;" id="generateReportBtn">Buat Laporan</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .table thead th {
            vertical-align: middle;
            padding: 10px;
            background-color: #f8f9fa;
            font-weight: bold;
            color: #333;
        }
        .table td {
            padding: 8px;
            vertical-align: middle;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #dee2e6;
        }
        .three-dots {
            font-size: 18px;
            line-height: 1;
        }
        .dropdown-item:hover {
            background: linear-gradient(90deg, #32BB35, #8BE52E);
            color: white;
        }
        .dropdown-item:active {
            background: linear-gradient(90deg, #E11818, #FF6C6C);
            color: white;
        }
        .dropdown-menu {
            border-radius: 8px;
            padding: 0;
            border-width: 2px;
            border-color: #dee2e6;
        }
        .dropdown-item {
            padding: 10px 15px;
            border-bottom: 1px solid #dee2e6;
        }
        .dropdown-item:last-child {
            border-bottom: none;
        }
        /* Gaya untuk menyamakan warna latar belakang modal */
        .modal-custom-bg {
            background-color: #ffffff !important; /* Warna latar belakang putih konsisten */
        }
        /* Menghilangkan ikon segitiga dari dropdown-toggle */
        .dropdown-toggle::after {
            display: none !important;
        }
        /* Menebalkan border modal */
        .modal-content {
            border-width: 2px;
            border-color: #dee2e6;
        }
    </style>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Trigger untuk membuka modal Student Selection dari dropdown
            document.getElementById('downloadTrigger').addEventListener('click', function() {
                resetAndShowModal('#studentSelectionModal');
                resetReportOptions(); // Reset checkbox saat membuka alur baru
            });

            // Lanjut ke modal File Type setelah memilih mahasiswa
            document.getElementById('nextToFileTypeBtn').addEventListener('click', function() {
                const selectedStudent = document.getElementById('studentDropdown').value;
                if (selectedStudent) {
                    $('#studentSelectionModal').modal('hide');
                    $('#fileTypeModal').modal('show');
                } else {
                    alert('Harap pilih nama mahasiswa terlebih dahulu.');
                }
            });

            // Kembali ke modal Student Selection dari File Type
            document.getElementById('backToStudentBtn').addEventListener('click', function() {
                $('#fileTypeModal').modal('hide');
                $('#studentSelectionModal').modal('show');
            });

            document.getElementById('nextModalBtn').addEventListener('click', function() {
                $('#fileTypeModal').modal('hide');
                $('#reportOptionsModal').modal('show');
            });

            document.getElementById('generateReportBtn').addEventListener('click', function() {
                $('#reportOptionsModal').modal('hide');
                Swal.fire({
                    icon: 'success',
                    title: 'Succeed',
                    text: 'Laporan berhasil disimpan.',
                    confirmButtonText: 'OK',
                    confirmButtonColor: '#32BB35',
                    customClass: {
                        confirmButton: 'btn',
                        popup: 'swal2-custom'
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Reset state modal dan backdrop
                        $('.modal-backdrop').remove();
                        $('body').removeClass('modal-open');
                        $('body').css('overflow', 'auto');
                        // Reinisialisasi semua modal
                        $('#studentSelectionModal').modal('dispose');
                        $('#fileTypeModal').modal('dispose');
                        $('#reportOptionsModal').modal('dispose');
                        $('#studentSelectionModal').modal({ show: false });
                        $('#fileTypeModal').modal({ show: false });
                        $('#reportOptionsModal').modal({ show: false });
                        resetReportOptions(); // Reset checkbox setelah laporan dibuat
                    }
                });
            });

            // Pastikan tombol Back berfungsi
            const backButton = document.getElementById('backButton');
            if (backButton) {
                backButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    $('#reportOptionsModal').modal('hide').then(() => {
                        resetAndShowModal('#fileTypeModal');
                        resetReportOptions(); // Reset checkbox saat kembali ke File Type
                    });
                    console.log('Tombol Back diklik, mencoba menampilkan #fileTypeModal');
                });
            } else {
                console.log('Tombol Back tidak ditemukan!');
            }
        });

        // Fungsi untuk reset dan menampilkan modal
        function resetAndShowModal(modalId) {
            $(modalId).modal('dispose'); // Hapus instance modal
            $(modalId).modal({ show: false }); // Reinisialisasi modal
            $(modalId).modal('show'); // Tampilkan modal
        }

        // Fungsi untuk reset checkbox di Report Options
        function resetReportOptions() {
            document.getElementById('includeCourseGrade').checked = false;
            document.getElementById('includeClassIP').checked = false;
            document.getElementById('includeClassIPK').checked = false;
        }

        // Tambahkan gaya CSS untuk gradasi hijau pada tombol OK SweetAlert
        const style = document.createElement('style');
        style.innerHTML = `
            .swal2-custom .swal2-confirm {
                background: linear-gradient(90deg, #32BB35, #8BE52E) !important;
                border-radius: 8px !important;
                color: white !important;
                padding: 10px 20px !important;
            }
            .swal2-custom .swal2-confirm:hover {
                background: linear-gradient(90deg, #2A9D34, #76C517) !important;
            }
        `;
        document.head.appendChild(style);
    </script>
@endsection
