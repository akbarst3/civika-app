@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <div>
                <h2 class="mb-0" style="font-weight: 700;">Import Buku Besar</h2>
                <p class="description-text mt-2">
                    Upload buku besar dengan format excel. Sesuikan format excel dengan format excel sesuai prodi.
                </p>
            </div>
            <!-- Search box dihapus -->
        </div>

        <!-- Tombol Lihat Format Excel -->
        <div class="switch-and-button-container">
            <a href="#" class="format-button" data-bs-toggle="modal" data-bs-target="#formatModal">Lihat Format Excel</a>
        </div>

        <!-- Area Upload File -->
        <div class="upload-container">
            <div class="upload-box">
                <i class="fas fa-cloud-upload-alt"></i>
                <input type="file" accept=".xlsx, .xls" id="excelFile" />
                <p>Upload file Excel untuk import data buku besar<br>(Format: .xlsx atau .xls)</p>
            </div>
            <!-- Tombol Import di bawah upload -->
            <div class="import-button-container">
                <button class="import-button" onclick="handleImport()">Import</button>
            </div>
        </div>

        <!-- Modal untuk Menampilkan Link Format Excel -->
        <div class="modal fade" id="formatModal" tabindex="-1" aria-labelledby="formatModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="formatModalLabel">Format Excel untuk Import Buku Besar</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <p class="format-warning">
                            Perhatikan format excel yang akan diimport, sesuaikan dengan format yang berada di link excel sesuai dengan prodi. Perhatikan peletakan kolomnya.
                        </p>
                        <div class="format-links">
                            <p><strong>Format Excel D3:</strong> <a href="https://polbanacid-my.sharepoint.com/:x:/g/personal/isyana_putri_tif23_polban_ac_id/EUvMnMP1jxNKrmso_tEZBjkBssDeRySDKkVEDBJrTzp6Mg?e=ePKntd" target="_blank">Lihat Format D3</a></p>
                            <p><strong>Format Excel D4:</strong> <a href="https://polbanacid-my.sharepoint.com/:x:/g/personal/isyana_putri_tif23_polban_ac_id/EZWIgOnT6rtIkbrnfCzo0K8B_LFHBI3FR5sBdz28dRtR3g?e=u3buDX" target="_blank">Lihat Format D4</a></p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Menambahkan SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        function handleImport() {
            const fileInput = document.getElementById('excelFile');

            // Periksa apakah file sudah diupload
            if (!fileInput.files || fileInput.files.length === 0) {
                Swal.fire({
                    title: 'File Tidak Ditemukan',
                    text: 'Silakan upload file Excel sebelum mengimport.',
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
                return;
            }

            const file = fileInput.files[0];
            const fileName = file.name;

            // Simulasi hasil dari BE: secara acak menentukan apakah format salah (50% peluang)
            const isFormatValid = Math.random() > 0.5;

            if (isFormatValid) {
                // Jika format sesuai, tampilkan pop-up sukses
                Swal.fire({
                    title: 'Succeed',
                    text: 'File berhasil diimport',
                    icon: 'success',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Opsional: Tambahkan logika setelah tombol OK ditekan
                    }
                });
            } else {
                // Jika format tidak sesuai, tampilkan pop-up warning
                Swal.fire({
                    title: 'Format Salah',
                    html: `File <strong>${fileName}</strong> tidak mengikuti format Excel yang sesuai.`,
                    icon: 'warning',
                    confirmButtonText: 'OK',
                    customClass: {
                        confirmButton: 'btn btn-primary'
                    },
                    buttonsStyling: false
                });
            }
        }
    </script>
@endsection
