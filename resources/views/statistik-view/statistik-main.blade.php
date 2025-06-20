@extends('layouts.app')

@section('title', 'Data Statistik - Visualisasi Data')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <!-- Program Title (Dynamic) -->
            <div class="container-fluid" style="padding-top: 80px;">
                <div class="text-center mb-4">
                    <h3 class="text-primary fw-bold" id="programTitle">
                        Program Studi : D3 - Teknik Komputer dan Informatika
                    </h3>
                </div>
            </div>

            <!-- Enhanced Control Panel -->
            @php
                $jabatan = auth()->user()->dosen->jabatan_dosen ?? null;
            @endphp

            <div class="row mb-4">
                <div class="col-md-4">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Program Studi :</label>
                        </div>
                        <div class="col-md-8">
                            @if(in_array($jabatan, ['Kaprodi-D3', 'Kaprodi-D4']))
                                @php
                                    $programValue = $jabatan === 'Kaprodi-D3' ? 'D3' : 'D4';
                                    $programText = $jabatan === 'Kaprodi-D3' ? 'D3 - Teknik Informatika' : 'D4 - Teknik Informatika';
                                @endphp

                                <input type="hidden" name="program" id="programSelect" value="{{ $programValue }}">
                                <span class="form-control-plaintext">{{ $programText }}</span>
                            @else
                                <select class="form-select" name="program" id="programSelect">
                                    <option value="D3">D3 - Teknik Informatika</option>
                                    <option value="D4">D4 - Teknik Informatika</option>
                                </select>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="row align-items-center">
                        <div class="col-md-4">
                            <label class="form-label fw-bold">Rentang Angkatan :</label>
                        </div>
                        @php
                            $startYear = $data->isNotEmpty() ? (int) $data->first()['angkatan'] : 2023;
                            $endYear = $data->isNotEmpty() ? (int) $data->last()['angkatan'] : 2023;
                        @endphp
                        <div class="col-md-4">
                            <select class="form-select" id="startYear">
                                @for ($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}">{{ $year }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select" id="endYear">
                                @for ($year = $startYear; $year <= $endYear; $year++)
                                    <option value="{{ $year }}" {{ $year == $endYear ? 'selected' : '' }}>
                                        {{ $year }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                    </div>
                </div>
            </div>

        <div class="row mb-4">
            <div class="col-md-4">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Minimum Standar :</label>
                    </div>
                    <div class="col-md-6">
                        <input type="number" class="form-control" id="minimumStandard" 
                            value="3.0" min="0" max="4" step="0.1" 
                            placeholder="0.0 - 4.0">
                    </div>
                </div>
            </div>
        </div>

            <!-- Chart Container -->
            <div class="card shadow-sm mb-4">
                <div class="card-body">
                    <canvas id="statistikChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Detail Statistik Table -->
            <div class="card shadow-sm">
                <div class="card-header bg-white text-center py-4">
                    <h2 class="mb-0 text-primary fw-bold" style="font-size: 2.5rem;">Detail Statistik</h2>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-borderless">
                            <thead>
                                <tr class="text-center">
                                    <th class="custom-header">Angkatan</th>
                                    <th class="custom-header">IPK Tertinggi</th>
                                    <th class="custom-header">IPK Terendah</th>
                                    <th class="custom-header">Rata-Rata IPK</th>
                                </tr>
                            </thead>
                            <tbody id="detailStatistikTable">
                                <!-- Table content will be populated by JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Loading state management
    let isLoading = false;
    
    // Show loading indicator
    function showLoading() {
        if (!isLoading) {
            isLoading = true;
            const loadingDiv = document.createElement('div');
            loadingDiv.id = 'loadingOverlay';
            loadingDiv.style.cssText = `
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
            `;
            loadingDiv.innerHTML = `
                <div class="spinner-border text-light" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.body.appendChild(loadingDiv);
        }
    }

    // Hide loading indicator
    function hideLoading() {
        if (isLoading) {
            const loadingDiv = document.getElementById('loadingOverlay');
            if (loadingDiv) {
                loadingDiv.remove();
            }
            isLoading = false;
        }
    }

    // Show notification
    function showNotification(message, type) {
        const notificationDiv = document.createElement('div');
        notificationDiv.className = `alert alert-${type} alert-dismissible fade show`;
        notificationDiv.style.cssText = `
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1100;
            min-width: 300px;
        `;
        notificationDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        `;
        document.body.appendChild(notificationDiv);
        setTimeout(() => {
            notificationDiv.classList.remove('show');
            setTimeout(() => notificationDiv.remove(), 300);
        }, 3000);
    }

    // Chart configuration
    const ctx = document.getElementById('statistikChart').getContext('2d');
    let chart;

    // Data Angkatan per Prodi
    const angkatanData = @json($dataAngkatan);
    const programSelect = document.getElementById('programSelect');
    const startYearSelect = document.getElementById('startYear');
    const endYearSelect = document.getElementById('endYear');
    const minimumStandardInput = document.getElementById('minimumStandard');

    function updateYearOptions(prodi) {
        const years = angkatanData[prodi] || [];
        startYearSelect.innerHTML = '';
        endYearSelect.innerHTML = '';

        years.forEach(year => {
            const option1 = new Option(year, year);
            const option2 = new Option(year, year);
            startYearSelect.appendChild(option1);
            endYearSelect.appendChild(option2);
        });

        if (years.length > 0) {
            startYearSelect.value = years[0];
            endYearSelect.value = years[years.length - 1];
        }
    }

    // Saat halaman pertama kali dibuka
    updateYearOptions(programSelect.value);

    // Saat dropdown prodi diganti
    programSelect.addEventListener('change', function () {
        updateYearOptions(this.value);
    });

    // Data for different program studies
    const data = @json($data);
    const dataIPKD3 = [];
    const dataAngkatanD3 = [];
    const dataIPKD4 = [];
    const dataAngkatanD4 = [];

    // Raw data untuk statistik detail (simulasi data dengan IPK tertinggi dan terendah)
    const detailDataD3 = [];
    const detailDataD4 = [];

    Object.values(data).forEach(item => {
        if (item.prodi === 'D3') {
            dataIPKD3.push(item.rata_rata_ips);
            dataAngkatanD3.push(item.angkatan);
            // Simulasi data detail dengan variasi IPK
            detailDataD3.push({
                angkatan: item.angkatan,
                ipk_tertinggi: Math.min(4.0, item.rata_rata_ips + (Math.random() * 0.5)),
                ipk_terendah: Math.max(0.0, item.rata_rata_ips - (Math.random() * 0.5)),
                rata_rata_ipk: item.rata_rata_ips
            });
        } else if (item.prodi === 'D4') {
            dataIPKD4.push(item.rata_rata_ips);
            dataAngkatanD4.push(item.angkatan);
            // Simulasi data detail dengan variasi IPK
            detailDataD4.push({
                angkatan: item.angkatan,
                ipk_tertinggi: Math.min(4.0, item.rata_rata_ips + (Math.random() * 0.5)),
                ipk_terendah: Math.max(0.0, item.rata_rata_ips - (Math.random() * 0.5)),
                rata_rata_ipk: item.rata_rata_ips
            });
        }
    });

    const chartData = {
        D3: {
            title: 'D3 - Teknik Komputer dan Informatika',
            data: {
                labels: dataAngkatanD3,
                datasets: [{
                    label: 'IPK',
                    data: dataIPKD3,
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#ff6b35',
                    pointBorderColor: '#ff6b35',
                    pointRadius: 6
                }]
            },
            detailData: detailDataD3
        },
        D4: {
            title: 'D4 - Teknik Komputer dan Informatika',
            data: {
                labels: dataAngkatanD4,
                datasets: [{
                    label: 'IPK',
                    data: dataIPKD4,
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#ff6b35',
                    pointBorderColor: '#ff6b35',
                    pointRadius: 6
                }]
            },
            detailData: detailDataD4
        }
    };

    // Initialize chart
    function initChart(programType) {
        const startYear = parseInt(document.getElementById('startYear').value);
        const endYear = parseInt(document.getElementById('endYear').value);
        const minimumStandard = parseFloat(document.getElementById('minimumStandard').value);
        
        if (startYear > endYear) {
            showNotification('Tahun mulai tidak boleh lebih besar dari tahun akhir', 'warning');
            hideLoading();
            return;
        }

        const data = chartData[programType];
        const startIndex = data.data.labels.indexOf(startYear.toString());
        const endIndex = data.data.labels.indexOf(endYear.toString());
        
        if (startIndex === -1 || endIndex === -1) {
            showNotification('Rentang tahun tidak valid', 'warning');
            hideLoading();
            return;
        }

        const filteredLabels = data.data.labels.slice(startIndex, endIndex + 1);
        const filteredIPKData = data.data.datasets[0].data.slice(startIndex, endIndex + 1);
        const minimumStandardData = new Array(filteredLabels.length).fill(minimumStandard);

        const filteredData = {
            labels: filteredLabels,
            datasets: [
                {
                    ...data.data.datasets[0],
                    data: filteredIPKData
                },
                {
                    label: 'Minimum Standar',
                    data: minimumStandardData,
                    borderColor: '#007bff',
                    backgroundColor: 'rgba(0, 123, 255, 0.1)',
                    borderDash: [5, 5],
                    tension: 0,
                    pointBackgroundColor: '#007bff',
                    pointBorderColor: '#007bff',
                    pointRadius: 4
                }
            ]
        };
        
        if (chart) {
            chart.destroy();
        }

        chart = new Chart(ctx, {
            type: 'line',
            data: filteredData,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 0,
                        max: 4.0,
                        ticks: {
                            stepSize: 0.5,
                            callback: function(value) {
                                return 'IPK ' + value.toFixed(1);
                            }
                        },
                        grid: {
                            color: '#e0e0e0'
                        }
                    },
                    x: {
                        grid: {
                            color: '#e0e0e0'
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8
                    }
                }
            }
        });

        // Update title
        document.getElementById('programTitle').textContent = data.title;
        
        // Update detail statistics table
        updateDetailStatistikTable(programType, startYear, endYear);
    }

    // Update detail statistik table
    function updateDetailStatistikTable(programType, startYear, endYear) {
        const tableBody = document.getElementById('detailStatistikTable');
        const detailData = chartData[programType].detailData;
        
        // Filter data berdasarkan rentang tahun
        const filteredData = detailData.filter(item => {
            const tahun = parseInt(item.angkatan);
            return tahun >= startYear && tahun <= endYear;
        });

        let tableHTML = '';
        filteredData.forEach(item => {
            tableHTML += `
                <tr class="text-center custom-row">
                    <td class="custom-cell">${item.angkatan}</td>
                    <td class="custom-cell">${item.ipk_tertinggi.toFixed(2)}</td>
                    <td class="custom-cell">${item.ipk_terendah.toFixed(2)}</td>
                    <td class="custom-cell">${item.rata_rata_ipk.toFixed(2)}</td>
                </tr>
            `;
        });

        tableBody.innerHTML = tableHTML;
    }

    // Event listener untuk minimum standard input
    minimumStandardInput.addEventListener('input', function() {
        const value = parseFloat(this.value);
        if (value > 4) {
            this.value = 4;
            showNotification('Maksimal nilai yang dapat diinputkan adalah 4.0', 'warning');
        } else if (value < 0) {
            this.value = 0;
            showNotification('Minimal nilai yang dapat diinputkan adalah 0.0', 'warning');
        }
    });

    minimumStandardInput.addEventListener('change', function() {
        const selectedProgram = document.getElementById('programSelect').value;
        showLoading();
        setTimeout(() => {
            try {
                initChart(selectedProgram);
                showNotification('Minimum standar diperbarui', 'success');
            } catch (error) {
                showNotification(`Gagal memperbarui minimum standar: ${error.message}`, 'danger');
            } finally {
                hideLoading();
            }
        }, 500);
    });

    // Event listener for program selection
    document.getElementById('programSelect').addEventListener('change', function() {
        const selectedProgram = this.value;
        showLoading();
        setTimeout(() => {
            try {
                initChart(selectedProgram);
                showNotification(`Data ${selectedProgram} berhasil dimuat`, 'success');
            } catch (error) {
                showNotification(`Gagal memuat data ${selectedProgram}: ${error.message}`, 'danger');
            } finally {
                hideLoading();
            }
        }, 500);
    });

    // Event listeners for year selection
    document.getElementById('startYear').addEventListener('change', function() {
        const selectedProgram = document.getElementById('programSelect').value;
        showLoading();
        setTimeout(() => {
            try {
                initChart(selectedProgram);
                showNotification(`Data ${selectedProgram} diperbarui`, 'success');
            } catch (error) {
                showNotification(`Gagal memperbarui data: ${error.message}`, 'danger');
            } finally {
                hideLoading();
            }
        }, 500);
    });

    document.getElementById('endYear').addEventListener('change', function() {
        const selectedProgram = document.getElementById('programSelect').value;
        showLoading();
        setTimeout(() => {
            try {
                initChart(selectedProgram);
                showNotification(`Data ${selectedProgram} diperbarui`, 'success');
            } catch (error) {
                showNotification(`Gagal memperbarui data: ${error.message}`, 'danger');
            } finally {
                hideLoading();
            }
        }, 500);
    });

    // Initialize with D3 program
    initChart('D3');
});
</script>

<style>
.sidebar {
    min-height: 100vh;
    border-right: 1px solid #dee2e6;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.nav-link {
    border-radius: 8px;
    transition: all 0.3s ease;
    margin-bottom: 5px;
}

.nav-link:hover {
    background-color: rgba(255, 193, 7, 0.2);
    transform: translateX(5px);
}

.nav-link.active {
    background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
    color: #000 !important;
    box-shadow: 0 2px 8px rgba(255, 193, 7, 0.3);
}

#statistikChart {
    height: 400px !important;
}

.card {
    transition: all 0.3s ease;
    border: none;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
}

.card:hover {
    transform: translateY(-3px);
    box-shadow: 0 4px 20px rgba(0,0,0,0.15);
}

.form-select, .form-control {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select:focus, .form-control:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.btn {
    border-radius: 8px;
    transition: all 0.3s ease;
}

.btn:hover {
    transform: translateY(-1px);
}

/* Program Title Animation */
#programTitle {
    background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    animation: titleGlow 2s ease-in-out infinite alternate;
}

@keyframes titleGlow {
    from { text-shadow: 0 0 10px rgba(0, 123, 255, 0.3); }
    to { text-shadow: 0 0 20px rgba(0, 123, 255, 0.6); }
}

/* Card Animations */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.card {
    animation: fadeInUp 0.6s ease-out;
}

/* Custom Table Styling untuk Detail Statistik */
.custom-header {
    background-color: #fff !important;
    color: #007bff !important;
    font-size: 1.5rem !important;
    font-weight: bold !important;
    padding: 20px 15px !important;
    border: none !important;
    text-transform: none !important;
    letter-spacing: normal !important;
}

.custom-row {
    border-bottom: 1px solid #e9ecef;
}

.custom-cell {
    background-color: #fff !important;
    color: #007bff !important;
    font-size: 1.3rem !important;
    font-weight: bold !important;
    padding: 15px !important;
    vertical-align: middle !important;
    border: none !important;
}

.custom-row:hover .custom-cell {
    background-color: #f8f9fa !important;
    transform: scale(1.01);
    transition: all 0.3s ease;
}

/* Card header styling untuk Detail Statistik */
.card-header.bg-white {
    background-color: #fff !important;
    border-bottom: 1px solid #e9ecef !important;
}

/* Responsive design */
@media (max-width: 768px) {
    .sidebar {
        min-height: auto;
        margin-bottom: 20px;
    }
    
    #programTitle {
        font-size: 1.2rem;
    }
    
    .row .col-md-6 {
        margin-bottom: 15px;
    }
    
    #statistikChart {
        height: 300px !important;
    }
    
    .custom-header {
        font-size: 1.2rem !important;
        padding: 15px 10px !important;
    }
    
    .custom-cell {
        font-size: 1.1rem !important;
        padding: 12px 8px !important;
    }
    
    .card-header h2 {
        font-size: 2rem !important;
    }
}

@media (max-width: 576px) {
    .btn-group {
        flex-direction: column;
    }
    
    .btn-group .btn {
        margin-bottom: 5px;
        border-radius: 8px !important;
    }
    
    .table-responsive {
        font-size: 0.875rem;
    }
    
    .custom-header {
        font-size: 1rem !important;
        padding: 12px 8px !important;
    }
    
    .custom-cell {
        font-size: 1rem !important;
        padding: 10px 6px !important;
    }
    
    .card-header h2 {
        font-size: 1.5rem !important;
    }
}

/* Loading animation */
.spinner-border {
    width: 3rem;
    height: 3rem;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 10px;
}

::-webkit-scrollbar-thumb {
    background: linear-gradient(135deg, #007bff, #0056b3);
    border-radius: 10px;
}

::-webkit-scrollbar-thumb:hover {
    background: linear-gradient(135deg, #0056b3, #004085);
}

/* Chart container enhancement */
.chart-container {
    position: relative;
    background: linear-gradient(135deg, #ffffff 0%, #f8f9fa 100%);
    border-radius: 15px;
    padding: 20px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.1);
}

/* Notifikasi Pojok Kanan Style */
.alert {
    border-radius: 10px;
    border: none;
    box-shadow: 0 4px 15px rgba(0,0,0,0.1);
}

/* Input number styling */
input[type="number"] {
    text-align: center;
}

/* Detail Statistik Card Header Enhancement */
.card-header {
    border-radius: 10px 10px 0 0 !important;
}

/* Table styling removal of default Bootstrap classes */
.table-borderless th,
.table-borderless td {
    border: none;
}

.table-borderless {
    border: none;
}
</style>
@endsection