@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
            <h2 style="font-weight:bold; margin-left:-12px">Daftar Pembimbing dan Penguji Tugas Akhir</h2>
            <div class="d-flex gap-3 flex-wrap mt-3">
                <!-- Search Box dengan icon FontAwesome -->
                <form class="d-flex mt-5" role="search" id="searchForm">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                            <i class="fas fa-search"></i>
                        </span>
                        <input 
                            type="search" 
                            class="form-control border-start-0" 
                            placeholder="Search" 
                            aria-label="Search"
                            id="searchInput"
                            style="border-radius: 0 36px 36px 0;">
                    </div>
                </form>

                <!-- Filter Angkatan dengan icon FontAwesome -->
                <form class="mt-5" id="filterForm">
                    <div class="input-group">
                        <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                            <i class="fas fa-user-group"></i>
                        </span>
                        <select 
                            class="form-select border-start-0" 
                            id="angkatanFilter"
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
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>No Kelompok</th>
                        <th>NIM</th>
                        <th>Nama</th>
                        <th>Topik</th>
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
                
                </ul>
            </nav>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('searchInput');
            const angkatanFilter = document.getElementById('angkatanFilter');
            const tableBody = document.getElementById('tableBody');
            const pagination = document.getElementById('pagination');
            let currentPage = 1;

            function fetchData(page = 1) {
                const search = searchInput.value;
                const angkatan = angkatanFilter.value;

                fetch(`/tugas-akhir/pembimbing-penguji?page=${page}&search=${encodeURIComponent(search)}&angkatan=${angkatan}`)
                    .then(response => response.json())
                    .then(data => {
                        // Update table
                        tableBody.innerHTML = '';
                        data.data.forEach(item => {
                            const row = document.createElement('tr');
                            row.innerHTML = `
                                <td>${item.kota}</td>
                                <td>${item.nim}</td>
                                <td>${item.nama_mhs}</td>
                                <td>${item.topik}</td>
                                <td>${item.pembimbing1}</td>
                                <td>${item.nip_pembimbing1}</td>
                                <td>${item.pembimbing2}</td>
                                <td>${item.nip_pembimbing2}</td>
                                <td>${item.penguji1}</td>
                                <td>${item.nip_penguji1}</td>
                                <td>${item.penguji2}</td>
                                <td>${item.nip_penguji2}</td>
                            `;
                            tableBody.appendChild(row);
                        });

                        // Update pagination
                        pagination.innerHTML = '';
                        const totalPages = data.last_page;
                        const maxPagesToShow = 5;
                        let startPage = Math.max(1, currentPage - Math.floor(maxPagesToShow / 2));
                        let endPage = Math.min(totalPages, startPage + maxPagesToShow - 1);

                        if (endPage - startPage + 1 < maxPagesToShow) {
                            startPage = Math.max(1, endPage - maxPagesToShow + 1);
                        }

                        // Previous button
                        if (currentPage > 1) {
                            pagination.innerHTML += `
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${currentPage - 1}">Previous</a>
                                </li>
                            `;
                        }

                        // Page numbers
                        for (let i = startPage; i <= endPage; i++) {
                            pagination.innerHTML += `
                                <li class="page-item ${i === currentPage ? 'active' : ''}">
                                    <a class="page-link" href="#" data-page="${i}">${i}</a>
                                </li>
                            `;
                        }

                        // Next button
                        if (currentPage < totalPages) {
                            pagination.innerHTML += `
                                <li class="page-item">
                                    <a class="page-link" href="#" data-page="${currentPage + 1}">Next</a>
                                </li>
                            `;
                        }

                        // Add click events to pagination links
                        document.querySelectorAll('.page-link').forEach(link => {
                            link.addEventListener('click', (e) => {
                                e.preventDefault();
                                currentPage = parseInt(e.target.dataset.page);
                                fetchData(currentPage);
                            });
                        });
                    });
            }

            // Initial data load
            fetchData();

            // Search and filter event listeners
            searchInput.addEventListener('input', debounce(() => {
                currentPage = 1;
                fetchData();
            }, 300));

            angkatanFilter.addEventListener('change', () => {
                currentPage = 1;
                fetchData();
            });

            // Debounce function to limit API calls
            function debounce(func, wait) {
                let timeout;
                return function executedFunction(...args) {
                    const later = () => {
                        clearTimeout(timeout);
                        func(...args);
                    };
                    clearTimeout(timeout);
                    timeout = setTimeout(later, wait);
                };
            }
        });
    </script>
    @endpush
@endsection