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
                <input type="file" name="excel_files[]" id="excel_files" accept=".xlsx, .xls, .csv" multiple required />
                <p>Upload file Excel untuk import data buku besar<br>(Format: .xlsx, .xls, atau .csv)<br><small>Tekan Ctrl (atau Cmd) untuk memilih beberapa file.</small></p>
                <p id="fileCount" class="file-count mt-2">0 file telah dipilih</p>
            </div>
            <!-- Progress Bar -->
            <div id="progressContainer" class="progress-container mb-3 d-none">
                <div class="progress" style="height: 25px;">
                    <div id="importProgress" class="progress-bar bg-success" role="progressbar" style="width: 0%;" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100">0%</div>
                </div>
                <p id="progressText" class="mt-2">0 dari 0 file telah diimport</p>
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

        <!-- Pop-up Kecil untuk Status -->
        <div id="statusPopup" class="status-popup d-none">
            <p id="statusMessage"></p>
        </div>
    </div>

    <!-- Menambahkan SweetAlert2 CDN -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        let totalFiles = 0;
        let successfulImports = 0;
        let failedImports = 0;

        // Update jumlah file yang dipilih
        const fileInput = document.getElementById('excel_files');
        const fileCountDisplay = document.getElementById('fileCount');

        fileInput.addEventListener('change', () => {
            const fileCount = fileInput.files.length;
            fileCountDisplay.textContent = `${fileCount} file telah dipilih`;
        });

        function updateProgressBar() {
            const progressBar = document.getElementById('importProgress');
            const progressText = document.getElementById('progressText');
            const percentage = totalFiles === 0 ? 0 : (successfulImports / totalFiles) * 100;
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage);
            progressBar.textContent = `${Math.round(percentage)}%`;
            progressText.textContent = `${successfulImports} dari ${totalFiles} file telah diimport`;
        }

        function showStatusPopup(message, isSuccess) {
            const statusPopup = document.getElementById('statusPopup');
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusPopup.classList.remove('d-none');
            statusPopup.classList.remove('status-success', 'status-fail');
            statusPopup.classList.add(isSuccess ? 'status-success' : 'status-fail');
            setTimeout(() => {
                statusPopup.classList.add('d-none');
            }, 3000); // Hilang setelah 3 detik
        }

        async function handleImport() {
            const fileInput = document.getElementById('excel_files');
            const progressContainer = document.getElementById('progressContainer');

            // Periksa apakah file sudah diupload
            if (!fileInput.files || fileInput.files.length === 0) {
                showStatusPopup('Silakan upload file Excel sebelum mengimport.', false);
                return;
            }

            // Tampilkan progress bar
            progressContainer.classList.remove('d-none');

            const files = Array.from(fileInput.files);
            totalFiles = files.length;
            successfulImports = 0;
            failedImports = 0;

            console.log('Files detected:', files); // Debug: Cek apakah file terdeteksi

            if (totalFiles > 1) {
                const formData = new FormData();
                files.forEach(file => {
                    formData.append('excel_files[]', file);
                });

                try {
                    const response = await fetch('/import-excel', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success && Array.isArray(data.results)) {
                        data.results.forEach(result => {
                            if (result.success) {
                                successfulImports++;
                                showStatusPopup(`File ${result.fileName} berhasil diimport.`, true);
                            } else {
                                failedImports++;
                                showStatusPopup(`File ${result.fileName} gagal diimport: ${result.message || 'Format tidak sesuai.'}`, false);
                            }
                        });
                    } else {
                        failedImports += totalFiles; // Jika tidak ada data spesifik, anggap semua gagal
                        files.forEach(file => showStatusPopup(`File ${file.name} gagal diimport: Respons tidak valid.`, false));
                    }
                } catch (error) {
                    failedImports += totalFiles;
                    files.forEach(file => showStatusPopup(`File ${file.name} gagal diimport: Kesalahan server.`, false));
                }
            } else {
                const file = files[0];
                const formData = new FormData();
                formData.append('excel_files[]', file);

                try {
                    const response = await fetch('/import-excel', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                        },
                        body: formData
                    });

                    const data = await response.json();

                    if (data.success) {
                        successfulImports++;
                        showStatusPopup(`File ${file.name} berhasil diimport.`, true);
                    } else {
                        failedImports++;
                        showStatusPopup(`File ${file.name} gagal diimport: ${data.message || 'Format tidak sesuai.'}`, false);
                    }
                } catch (error) {
                    failedImports++;
                    showStatusPopup(`File ${file.name} gagal diimport: Kesalahan server.`, false);
                }
            }

            updateProgressBar();

            // Tampilkan pop-up sukses setelah semua file diproses
            Swal.fire({
                title: 'Import Selesai',
                html: `Berhasil mengimport ${successfulImports} file.<br>Gagal mengimport ${failedImports} file.`,
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    // Reset progress bar dan input setelah import selesai
                    fileInput.value = '';
                    totalFiles = 0;
                    successfulImports = 0;
                    failedImports = 0;
                    updateProgressBar();
                    fileCountDisplay.textContent = '0 file telah dipilih'; // Reset jumlah file
                    progressContainer.classList.add('d-none');
                }
            });
        }
    </script>
@endsection
