@extends('layouts.app')

@section('title', 'Data Statistik - Visualisasi Data')

@section('content')
<div class="container-fluid">
    <div class="row">
        <!-- Main Content -->
        <div class="col-md-10 p-4">
            <!-- Header -->
            {{-- <div class="d-flex justify-content-between align-items-center mb-4">
                <h5 class="text-muted">Visualisasi Data Dengan Line Chart</h5>
                <div class="d-flex align-items-center">
                    <div class="bg-secondary rounded-pill px-3 py-1 me-3">
                        <small class="text-white">Search...</small>
                    </div>
                    <i class="fas fa-bell text-muted me-3"></i>
                    <div class="bg-danger rounded-circle" style="width: 40px; height: 40px;"></div>
                </div>
            </div> --}}

            <!-- Program Title (Dynamic) -->
            <div class="container-fluid" style="padding-top: 80px;">
                <div class="text-center mb-4">
                    <h3 class="text-primary fw-bold" id="programTitle">
                        Program Studi : D3 - Teknik Komputer dan Informatika
                    </h3>
                </div>
                <!-- Konten lainnya -->
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

            <div class="col-md-5 mb-4">
            <div class="row align-items-center">
                <div class="col-md-4">
                    <label class="form-label fw-bold">Rentang Angkatan :</label>
                </div>


                {{-- @php
                    $filtered = $data->filter(function ($item) {
                        return $item['prodi'] === 'D3'; // ganti 'D3' ke prodi yang dipilih
                    })->sortBy('angkatan')->values();

                    $startYear = $filtered->first()['angkatan'] ?? 2023;
                    $endYear = $filtered->last()['angkatan'] ?? 2023;
                @endphp

                <div class="col-md-4">
                    <label>Start Year</label>
                    <select class="form-select" id="startYear">
                        @for ($year = $startYear; $year <= $endYear; $year++)
                            <option value="{{ $year }}">{{ $year }}</option>
                        @endfor
                    </select>
                </div>
                <div class="col-md-4">
                    <label>End Year</label>
                    <select class="form-select" id="endYear">
                        @for ($year = $startYear; $year <= $endYear; $year++)
                            <option value="{{ $year }}" {{ $year == $endYear ? 'selected' : '' }}>{{ $year }}</option>
                        @endfor
                    </select>
                </div> --}}

                {{-- <div class="col-md-4">
                    <select class="form-select" id="startYear"></select>
                </div>
                <div class="col-md-4">
                    <select class="form-select" id="endYear"></select>
                </div> --}}

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

            <!-- Chart Container -->
            <div class="card shadow-sm">
                <div class="card-body">
                    <canvas id="statistikChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Data Summary Cards (Different for D3 and D4) -->
            <div class="row mt-4" id="dataSummary">
                <!-- Cards will be populated by JavaScript -->
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

    Object.values(data).forEach(item => {
        if (item.prodi === 'D3') {
            dataIPKD3.push(item.rata_rata_ips);
            dataAngkatanD3.push(item.angkatan);
        } else if (item.prodi === 'D4') {
            dataIPKD4.push(item.rata_rata_ips);
            dataAngkatanD4.push(item.angkatan);
        }
    });
    console.log(dataIPKD4);
    console.log(dataAngkatanD4);
    const chartData = {
        D3: {
            title: 'Program Studi : D3 - Teknik Komputer dan Informatika',
            data: {
                labels: dataAngkatanD3,
                datasets: [{
                    label: 'IPK',
                    data : dataIPKD3,
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#ff6b35',
                    pointBorderColor: '#ff6b35',
                    pointRadius: 6
                }]
            },
            summary: [
                { title: 'Total Mahasiswa', value: '1,250', icon: 'fa-users', color: 'primary' },
                { title: 'Mahasiswa Aktif', value: '980', icon: 'fa-user-check', color: 'success' },
                { title: 'Rata-rata IPK', value: '3.28', icon: 'fa-chart-line', color: 'warning' },
                { title: 'Tingkat Kelulusan', value: '92%', icon: 'fa-graduation-cap', color: 'info' }
            ]
        },
        D4: {
            title: 'Program Studi : D4 - Teknik Komputer dan Informatika',
            data: {     
                labels: dataAngkatanD4,
                datasets: [{
                    label: 'IPK',
                    data: dataIPKD4,
                    borderColor: '#4285f4',
                    backgroundColor: 'rgba(66, 133, 244, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#4285f4',
                    pointBorderColor: '#4285f4',
                    pointRadius: 6
                }]
            },
            summary: [
                { title: 'Total Mahasiswa', value: '850', icon: 'fa-users', color: 'primary' },
                { title: 'Mahasiswa Aktif', value: '720', icon: 'fa-user-check', color: 'success' },
                { title: 'Rata-rata IPK', value: '3.40', icon: 'fa-chart-line', color: 'warning' },
                { title: 'Tingkat Kelulusan', value: '95%', icon: 'fa-graduation-cap', color: 'info' }
            ]
        }
    };

    // Initialize chart
    function initChart(programType) {
        const startYear = parseInt(document.getElementById('startYear').value);
        const endYear = parseInt(document.getElementById('endYear').value);
        
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

        const filteredData = {
            labels: data.data.labels.slice(startIndex, endIndex + 1),
            datasets: [{
                ...data.data.datasets[0],
                data: data.data.datasets[0].data.slice(startIndex, endIndex + 1)
            }]
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
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 2.5,
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
        
        // Update summary cards
        // updateSummaryCards(data.summary);
    }

    // Update summary cards
    // function updateSummaryCards(summary) {
    //     const summaryContainer = document.getElementById('dataSummary');
    //     summaryContainer.innerHTML = '';

    //     summary.forEach(item => {
    //         const cardHtml = `
    //             <div class="col-md-3 mb-3">
    //                 <div class="card border-${item.color} h-100">
    //                     <div class="card-body text-center">
    //                         <i class="fas ${item.icon} fa-2x text-${item.color} mb-2"></i>
    //                         <h4 class="text-${item.color} fw-bold">${item.value}</h4>
    //                         <p class="text-muted mb-0">${item.title}</p>
    //                     </div>
    //                 </div>
    //             </div>
    //         `;
    //         summaryContainer.innerHTML += cardHtml;
    //     });
    // }

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

    // Export functions
    function exportChart(format) {
        showLoading();
        
        try {
            if (format === 'png') {
                const canvas = document.getElementById('statistikChart');
                const url = canvas.toDataURL('image/png');
                const link = document.createElement('a');
                link.download = `statistik-chart-${new Date().getTime()}.png`;
                link.href = url;
                link.click();
                showNotification('Chart berhasil diekspor sebagai PNG', 'success');
            }
        } catch (error) {
            showNotification(`Gagal mengekspor chart: ${error.message}`, 'danger');
        } finally {
            hideLoading();
        }
    }

    // function exportData(format) {
    //     showLoading();
        
    //     try {
    //         if (format === 'excel') {
    //             // Simulate Excel export
    //             setTimeout(() => {
    //                 showNotification('Data berhasil diekspor ke Excel', 'success');
    //                 hideLoading();
    //             }, 1000);
    //         } else if (format === 'pdf') {
    //             // Simulate PDF export
    //             setTimeout(() => {
    //                 showNotification('Data berhasil diekspor ke PDF', 'success');
    //                 hideLoading();
    //             }, 1000);
    //         }
    //     } catch (error) {
    //         showNotification(`Gagal mengekspor data: ${error.message}`, 'danger');
    //     } finally {
    //         hideLoading();
    //     }
    // }

    function applyFilters() {
        applyAdvancedFilters();
    }

    function handleSearch(event) {
        if (event.key === 'Enter') {
            performSearch();
        }
    }

    // function performSearch() {
    //     const query = document.getElementById('searchInput').value.trim();
    //     if (query) {
    //         searchStatistik(query);
    //     } else {
    //         showNotification('Masukkan kata kunci pencarian', 'warning');
    //     }
    // }

    // function showComparison() {
    //     // Scroll to comparison table
    //     const comparisonTable = document.getElementById('comparisonTable');
    //     if (comparisonTable) {
    //         comparisonTable.scrollIntoView({ behavior: 'smooth', block: 'center' });
            
    //         // Highlight the table briefly
    //         comparisonTable.classList.add('table-bordered', 'border-primary');
    //         setTimeout(() => {
    //             comparisonTable.classList.remove('border-primary');
    //         }, 2000);
            
    //         showNotification('Tabel perbandingan ditampilkan di bawah', 'info');
    //     }
    // }

    function refreshData() {
        showLoading();
        
        try {
            setTimeout(() => {
                const currentProgram = document.getElementById('programSelect').value;
                
                // Simulate data refresh
                initChart(currentProgram);
                
                // Update all components
                statistikHelper.updateDetailStats(currentProgram);
                statistikHelper.updateComparisonTable();
                
                showNotification('Data berhasil diperbarui', 'success');
                hideLoading();
            }, 1500);
        } catch (error) {
            showNotification(`Gagal memperbarui data: ${error.message}`, 'danger');
            hideLoading();
        }
    }

    // Additional utility functions
    // function toggleFullscreen() {
    //     const chartContainer = document.querySelector('.card-body');
    //     if (chartContainer) {
    //         if (!document.fullscreenElement) {
    //             chartContainer.requestFullscreen().catch(err => {
    //                 showNotification(`Gagal masuk mode fullscreen: ${err.message}`, 'danger');
    //             });
    //         } else {
    //             document.exitFullscreen();
    //         }
    //     }
    // }

    // function copyChartToClipboard() {
    //     const canvas = document.getElementById('statistikChart');
    //     canvas.toBlob(function(blob) {
    //         const item = new ClipboardItem({ 'image/png': blob });
    //         navigator.clipboard.write([item]).then(function() {
    //             showNotification('Chart berhasil disalin ke clipboard', 'success');
    //         }).catch(function(error) {
    //             showNotification(`Gagal menyalin chart ke clipboard: ${error.message}`, 'danger');
    //         });
    //     });
    // }

    // Initialize with D3 program
    initChart('D3');
});

// Placeholder for statistikHelper (to avoid undefined errors)
const statistikHelper = {
    updateDetailStats: function(program) {
        // Simulate updating detailed stats
        console.log(`Updating detailed stats for ${program}`);
    },
    updateComparisonTable: function() {
        // Simulate updating comparison table
        console.log('Updating comparison table');
    }
};
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

.form-select {
    border-radius: 8px;
    border: 2px solid #e9ecef;
    transition: all 0.3s ease;
}

.form-select:focus {
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

/* Badge animations */
.badge {
    transition: all 0.3s ease;
}

.badge:hover {
    transform: scale(1.1);
}

/* Table styling */
.table-hover tbody tr:hover {
    background-color: rgba(0, 123, 255, 0.1);
    transform: scale(1.01);
    transition: all 0.3s ease;
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

/* Spacing buat rentang angkatan sama chart */
.col-md-5 {
    margin-bottom: 2rem; /* Jarak rentang Angkatan dropdown */
}
</style>
@endsection