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
        let totalSheets = 0;
        let processedSheets = 0;
        let successfulSheets = 0;
        let failedSheets = 0;

        const fileInput = document.getElementById('excel_files');
        const fileCountDisplay = document.getElementById('fileCount');
        const progressContainer = document.getElementById('progressContainer');
        const progressBar = document.getElementById('importProgress');
        const progressText = document.getElementById('progressText');

        fileInput.addEventListener('change', () => {
            const fileCount = fileInput.files.length;
            fileCountDisplay.textContent = `${fileCount} file telah dipilih`;
        });

        function updateProgressBar() {
            const percentage = totalSheets === 0 ? 0 : (processedSheets / totalSheets) * 100;
            progressBar.style.width = `${percentage}%`;
            progressBar.setAttribute('aria-valuenow', percentage);
            progressBar.textContent = `${Math.round(percentage)}%`;
            progressText.textContent = `${processedSheets} dari ${totalSheets} sheet telah diimport`;
        }

        function showStatusPopup(message, isSuccess) {
            const statusPopup = document.getElementById('statusPopup');
            const statusMessage = document.getElementById('statusMessage');
            statusMessage.textContent = message;
            statusPopup.classList.remove('d-none', 'status-success', 'status-fail');
            statusPopup.classList.add(isSuccess ? 'status-success' : 'status-fail');
            setTimeout(() => {
                statusPopup.classList.add('d-none');
            }, 3000);
        }

        async function handleImport() {
            if (!fileInput.files || fileInput.files.length === 0) {
                showStatusPopup('Silakan upload file Excel sebelum mengimport.', false);
                return;
            }

            progressContainer.classList.remove('d-none');
            const files = Array.from(fileInput.files);
            totalSheets = 0;
            processedSheets = 0;
            successfulSheets = 0;
            failedSheets = 0;

            // Calculate total sheets (client-side estimation)
            // Ideally, this should be confirmed by the server, but for simplicity, assume sheets A, B, C
            files.forEach(() => totalSheets += 3); // Assume max 3 sheets per file
            updateProgressBar();

            const formData = new FormData();
            files.forEach(file => formData.append('excel_files[]', file));

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
                    totalSheets = data.totalSheets; // Use server-provided totalSheets
                    data.results.forEach(fileResult => {
                        fileResult.sheets.forEach(sheet => {
                            processedSheets++;
                            if (sheet.success) {
                                successfulSheets++;
                                showStatusPopup(`Sheet ${sheet.sheetName} di file ${fileResult.fileName} berhasil diimport.`, true);
                            } else {
                                failedSheets++;
                                showStatusPopup(`Sheet ${sheet.sheetName} di file ${fileResult.fileName} gagal diimport: ${sheet.message || 'Format tidak sesuai.'}`, false);
                            }
                            updateProgressBar();
                        });
                    });
                } else {
                    failedSheets += totalSheets;
                    files.forEach(file => showStatusPopup(`File ${file.name} gagal diimport: Respons tidak valid.`, false));
                    updateProgressBar();
                }
            } catch (error) {
                failedSheets += totalSheets;
                files.forEach(file => showStatusPopup(`File ${file.name} gagal diimport: Kesalahan server.`, false));
                updateProgressBar();
            }

            Swal.fire({
                title: 'Import Selesai',
                html: `Berhasil mengimport ${successfulSheets} sheet.<br>Gagal mengimport ${failedSheets} sheet.`,
                icon: 'success',
                confirmButtonText: 'OK',
                customClass: {
                    confirmButton: 'btn btn-primary'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    fileInput.value = '';
                    totalSheets = 0;
                    processedSheets = 0;
                    successfulSheets = 0;
                    failedSheets = 0;
                    updateProgressBar();
                    fileCountDisplay.textContent = '0 file telah dipilih';
                    progressContainer.classList.add('d-none');
                }
            });
        }
    </script>
@endsection
