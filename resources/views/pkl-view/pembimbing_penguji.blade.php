```html
@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 class="mb-0" style="font-weight:bold">Daftar Pembimbing dan Penguji Kerja Praktik</h2>

        <div class="d-flex gap-3 flex-wrap mt-3">
            <!-- Search Box -->
            <form class="d-flex mt-5" role="search" id="searchForm">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="search" class="form-control border-start-0" placeholder="Search" aria-label="Search"
                        id="searchInput" style="border-radius: 0 36px 36px 0;">
                </div>
            </form>

            <!-- Filter Angkatan -->
            <form class="mt-5" id="filterForm">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                        <i class="fas fa-user-group"></i>
                    </span>
                    <select class="form-select border-start-0" id="angkatanFilter"
                        style="border-radius: 0 36px 36px 0; min-width: 160px;">
                        <option value="" selected>Angkatan</option>
                        <option value="2025">2025</option>
                        <option value="2024">2024</option>
                        <option value="2023">2023</option>
                    </select>
                </div>
            </form>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered text-center align-middle">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Perusahaan</th>
                    <th>Pembimbing 1</th>
                    <th>NIDN</th>
                    <th>Pembimbing 2</th>
                    <th>NIDN</th>
                    <th>Penguji 1</th>
                    <th>NIDN</th>
                    <th>Penguji 2</th>
                    <th>NIDN</th>
                </tr>
            </thead>
            <tbody id="tableBody">
                <!-- Data akan diisi oleh JavaScript -->
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        <nav>
            <ul class="pagination" id="pagination">
                <!-- Pagination akan diisi oleh JavaScript -->
            </ul>
        </nav>
    </div>
</div>

<!-- Include Axios -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script>
    // Fungsi untuk mengambil data dari API
    async function fetchData(page = 1, search = '', angkatan = '') {
        try {
            const response = await axios.get('/api/pkl', {
                params: {
                    page,
                    search,
                    angkatan
                }
            });
            const data = response.data.data;
            renderTable(data);
            renderPagination(response.data);
        } catch (error) {
            console.error('Error fetching data:', error);
            document.getElementById('tableBody').innerHTML = `
                <tr><td colspan="12" class="text-center">Error loading data</td></tr>
            `;
        }
    }

    // Fungsi untuk merender tabel
    function renderTable(data) {
        const tableBody = document.getElementById('tableBody');
        tableBody.innerHTML = '';

        // Kelompokkan data berdasarkan nama_perusahaan untuk rowspan
        const groupedData = groupBy(data, 'nama_perusahaan');
        let rowNumber = 1;

        for (const perusahaan in groupedData) {
            const group = groupedData[perusahaan];
            const rowSpan = group.length;

            group.forEach((item, index) => {
                const row = document.createElement('tr');
                if (index === 0) {
                    row.innerHTML = `
                        <td>${rowNumber}</td>
                        <td>${item.nim}</td>
                        <td>${item.nama_mahasiswa}</td>
                        <td rowspan="${rowSpan}">${item.nama_perusahaan}</td>
                        <td rowspan="${rowSpan}">${item.pembimbing[0]?.nama_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.pembimbing[0]?.kode_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.pembimbing[1]?.nama_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.pembimbing[1]?.kode_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.penguji[0]?.nama_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.penguji[0]?.kode_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.penguji[1]?.nama_dosen || '-'}</td>
                        <td rowspan="${rowSpan}">${item.penguji[1]?.kode_dosen || '-'}</td>
                    `;
                } else {
                    row.innerHTML = `
                        <td>${rowNumber}</td>
                        <td>${item.nim}</td>
                        <td>${item.nama_mahasiswa}</td>
                    `;
                }
                tableBody.appendChild(row);
                rowNumber++;
            });
        }
    }

    // Fungsi untuk mengelompokkan data
    function groupBy(array, key) {
        return array.reduce((result, item) => {
            (result[item[key]] = result[item[key]] || []).push(item);
            return result;
        }, {});
    }

    // Fungsi untuk merender pagination
    function renderPagination(data) {
        const pagination = document.getElementById('pagination');
        pagination.innerHTML = '';

        if (!data.links) return;

        data.links.forEach(link => {
            const li = document.createElement('li');
            li.className = `page-item ${link.active ? 'active' : ''} ${link.url ? '' : 'disabled'}`;
            const span = document.createElement('span');
            span.className = 'page-link';
            span.innerHTML = link.label;

            if (link.url && !link.active) {
                span.style.cursor = 'pointer';
                span.addEventListener('click', () => {
                    const url = new URL(link.url);
                    const page = url.searchParams.get('page') || 1;
                    const search = document.getElementById('searchInput').value;
                    const angkatan = document.getElementById('angkatanFilter').value;
                    fetchData(page, search, angkatan);
                });
            }
            li.appendChild(span);
            pagination.appendChild(li);
        });
    }

    // Event listener untuk pencarian
    document.getElementById('searchForm').addEventListener('submit', (e) => {
        e.preventDefault();
        const search = document.getElementById('searchInput').value;
        const angkatan = document.getElementById('angkatanFilter').value;
        fetchData(1, search, angkatan);
    });

    // Event listener untuk filter angkatan
    document.getElementById('angkatanFilter').addEventListener('change', () => {
        const search = document.getElementById('searchInput').value;
        const angkatan = document.getElementById('angkatanFilter').value;
        fetchData(1, search, angkatan);
    });

    // Panggil data saat halaman dimuat
    fetchData();
</script>
@endsection
