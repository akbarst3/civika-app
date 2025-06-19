import './bootstrap';
import 'bootstrap';

// Fungsi chart halaman statistik
window.StatistikChart = {
    chart: null,
    
    // Konfigurasi data buat program yang beda
    chartData: {
        D3: {
            title: 'Program Studi : D3 - Teknik Komputer dan Informatika',
            description: 'Diploma III - Program studi 3 tahun dengan fokus pada praktik dan aplikasi teknik informatika',
            data: {
                labels: ['2015', '2016', '2017', '2018', '2019'],
                datasets: [{
                    label: 'Rata-rata IPK',
                    data: [3.2, 3.3, 3.4, 3.1, 3.5],
                    borderColor: '#ff6b35',
                    backgroundColor: 'rgba(255, 107, 53, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#ff6b35',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true
                }]
            },
            summary: [
                { 
                    title: 'Total Mahasiswa', 
                    value: '1,250', 
                    icon: 'fa-users', 
                    color: 'primary',
                    change: '+12%',
                    changeType: 'increase' 
                },
                { 
                    title: 'Mahasiswa Aktif', 
                    value: '980', 
                    icon: 'fa-user-check', 
                    color: 'success',
                    change: '+8%',
                    changeType: 'increase' 
                },
                { 
                    title: 'Rata-rata IPK', 
                    value: '3.28', 
                    icon: 'fa-chart-line', 
                    color: 'warning',
                    change: '+0.05',
                    changeType: 'increase' 
                },
                { 
                    title: 'Tingkat Kelulusan', 
                    value: '92%', 
                    icon: 'fa-graduation-cap', 
                    color: 'info',
                    change: '+3%',
                    changeType: 'increase' 
                }
            ],
            characteristics: [
                'Fokus pada praktik dan aplikasi',
                'Durasi 3 tahun (6 semester)',
                'Magang industri wajib',
                'Tugas Akhir berupa proyek aplikatif'
            ]
        },
        D4: {
            title: 'Program Studi : D4 - Teknik Komputer dan Informatika',
            description: 'Diploma IV - Program studi 4 tahun setara S1 dengan fokus pada teknologi terapan',
            data: {
                labels: ['2015', '2016', '2017', '2018', '2019'],
                datasets: [{
                    label: 'Rata-rata IPK',
                    data: [3.4, 3.5, 3.3, 3.2, 3.6],
                    borderColor: '#4285f4',
                    backgroundColor: 'rgba(66, 133, 244, 0.1)',
                    tension: 0.4,
                    pointBackgroundColor: '#4285f4',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true
                }]
            },
            summary: [
                { 
                    title: 'Total Mahasiswa', 
                    value: '850', 
                    icon: 'fa-users', 
                    color: 'primary',
                    change: '+15%',
                    changeType: 'increase' 
                },
                { 
                    title: 'Mahasiswa Aktif', 
                    value: '720', 
                    icon: 'fa-user-check', 
                    color: 'success',
                    change: '+10%',
                    changeType: 'increase' 
                },
                { 
                    title: 'Rata-rata IPK', 
                    value: '3.40', 
                    icon: 'fa-chart-line', 
                    color: 'warning',
                    change: '+0.08',
                    changeType: 'increase' 
                },
                { 
                    title: 'Tingkat Kelulusan', 
                    value: '95%', 
                    icon: 'fa-graduation-cap', 
                    color: 'info',
                    change: '+5%',
                    changeType: 'increase' 
                }
            ],
            characteristics: [
                'Program setara dengan S1',
                'Durasi 4 tahun (8 semester)',
                'Penelitian dan pengembangan',
                'Tugas Akhir berupa penelitian aplikatif'
            ]
        }
    },

    // Inisialisasi chart-nya
    init: function(canvasId) {
        const ctx = document.getElementById(canvasId);
        if (!ctx) return;
        
        this.ctx = ctx.getContext('2d');
        this.initChart('D3');
        this.setupEventListeners();
    },

    // Bikin chart
    initChart: function(programType) {
        const data = this.chartData[programType];
        
        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(this.ctx, {
            type: 'line',
            data: data.data,
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: 'rgba(0,0,0,0.8)',
                        titleColor: '#ffffff',
                        bodyColor: '#ffffff',
                        borderColor: data.data.datasets[0].borderColor,
                        borderWidth: 1,
                        callbacks: {
                            label: function(context) {
                                return `IPK: ${context.parsed.y.toFixed(2)}`;
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: false,
                        min: 2.5,
                        max: 4.0,
                        ticks: {
                            stepSize: 0.2,
                            callback: function(value) {
                                return 'IPK ' + value.toFixed(1);
                            },
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            drawBorder: false
                        }
                    },
                    x: {
                        grid: {
                            color: 'rgba(0,0,0,0.1)',
                            drawBorder: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                },
                elements: {
                    point: {
                        hoverRadius: 8,
                        hoverBorderWidth: 3
                    }
                },
                animation: {
                    duration: 1000,
                    easing: 'easeInOutQuart'
                }
            }
        });

        // Update elemen di UI
        this.updateUI(data);
    },

    // Update elemen di UI
    updateUI: function(data) {
        // Update judul
        const titleElement = document.getElementById('programTitle');
        if (titleElement) {
            titleElement.textContent = data.title;
        }

        // Update summary cards
        this.updateSummaryCards(data.summary);
        
        // Update karakteristik kalau elemennya ada
        this.updateCharacteristics(data.characteristics);
    },

    // Update summary cards
    updateSummaryCards: function(summary) {
        const summaryContainer = document.getElementById('dataSummary');
        if (!summaryContainer) return;
        
        summaryContainer.innerHTML = '';

        summary.forEach((item, index) => {
            const changeIcon = item.changeType === 'increase' ? 'fa-arrow-up' : 'fa-arrow-down';
            const changeColor = item.changeType === 'increase' ? 'success' : 'danger';
            
            const cardHtml = `
                <div class="col-md-3 mb-3">
                    <div class="card border-${item.color} h-100" style="animation-delay: ${index * 0.1}s">
                        <div class="card-body text-center">
                            <i class="fas ${item.icon} fa-2x text-${item.color} mb-3"></i>
                            <h3 class="text-${item.color} fw-bold mb-1">${item.value}</h3>
                            <p class="text-muted mb-2">${item.title}</p>
                            <small class="text-${changeColor}">
                                <i class="fas ${changeIcon}"></i> ${item.change}
                            </small>
                        </div>
                    </div>
                </div>
            `;
            summaryContainer.innerHTML += cardHtml;
        });
    },

    // Update karakteristik
    updateCharacteristics: function(characteristics) {
        const characteristicsContainer = document.getElementById('programCharacteristics');
        if (!characteristicsContainer) return;

        characteristicsContainer.innerHTML = '';
        characteristics.forEach(char => {
            const li = document.createElement('li');
            li.className = 'list-group-item border-0 ps-0';
            li.innerHTML = `<i class="fas fa-check text-success me-2"></i>${char}`;
            characteristicsContainer.appendChild(li);
        });
    },

    // Setup event listeners
    setupEventListeners: function() {
        const programSelect = document.getElementById('programSelect');
        if (programSelect) {
            programSelect.addEventListener('change', (e) => {
                this.initChart(e.target.value);
            });
        }

        // Range tahun filter
        const startYear = document.getElementById('startYear');
        const endYear = document.getElementById('endYear');
        
        if (startYear && endYear) {
            [startYear, endYear].forEach(select => {
                select.addEventListener('change', () => {
                    this.filterDataByYear();
                });
            });
        }
    },

    // Filter data pake range tahunan
    filterDataByYear: function() {
        const startYear = parseInt(document.getElementById('startYear').value);
        const endYear = parseInt(document.getElementById('endYear').value);
        const programType = document.getElementById('programSelect').value;
        
        if (startYear > endYear) {
            alert('Tahun mulai tidak boleh lebih besar dari tahun akhir!');
            return;
        }

        // Filter data pake range tahunan
        const originalData = this.chartData[programType];
        const filteredData = { ...originalData };
        
        const startIndex = originalData.data.labels.indexOf(startYear.toString());
        const endIndex = originalData.data.labels.indexOf(endYear.toString());
        
        if (startIndex !== -1 && endIndex !== -1) {
            filteredData.data.labels = originalData.data.labels.slice(startIndex, endIndex + 1);
            filteredData.data.datasets[0].data = originalData.data.datasets[0].data.slice(startIndex, endIndex + 1);
        }

        // Buat ulang chart tapi filtrasi data
        if (this.chart) {
            this.chart.destroy();
        }

        this.chart = new Chart(this.ctx, {
            type: 'line',
            data: filteredData.data,
            options: this.chart?.options || {}
        });
    }
};

// Inisialisasi DOM
document.addEventListener('DOMContentLoaded', function() {
    if (document.getElementById('statistikChart')) {
        window.StatistikChart.init('statistikChart');
    }
});