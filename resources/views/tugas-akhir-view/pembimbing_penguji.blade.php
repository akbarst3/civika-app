@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="container mt-5">
            <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
                <h2 style="font-weight:bold; margin-left:-12px">Daftar Pembimbing dan Penguji Tugas Akhir</h2>
        
                <div class="d-flex gap-3 flex-wrap mt-3">
                    <!-- Search Box dengan icon FontAwesome -->
                    <form class="d-flex mt-5" role="search">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                                <i class="fas fa-search"></i>
                            </span>
                            <input 
                                type="search" 
                                class="form-control border-start-0" 
                                placeholder="Search" 
                                aria-label="Search"
                                style="border-radius: 0 36px 36px 0;">
                        </div>
                    </form>
        
                    <!-- Filter Angkatan dengan icon FontAwesome -->
                    <form class="mt-5">
                        <div class="input-group">
                            <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                                <i class="fas fa-user-group"></i>
                            </span>
                            <select 
                                class="form-select border-start-0" 
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
        </div>
        

        <div class="table-responsive">
            <table class="table table-bordered ">
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
                <tbody>
                    <!-- Kelompok 01 - 3 anggota -->
                    <tr>
                        <td rowspan="3">01</td>
                        <td>12345678</td>
                        <td>Andi Setiawan</td>
                        <td rowspan="3">AI dalam Pendidikan</td>
                        <td rowspan="3">Dr. Budi</td>
                        <td rowspan="3">0123456</td>
                        <td rowspan="3">Prof. Rina</td>
                        <td rowspan="3">0654321</td>
                        <td rowspan="3">Dr. Sinta</td>
                        <td rowspan="3">0987654</td>
                        <td rowspan="3">Prof. Joko</td>
                        <td rowspan="3">0456123</td>
                    </tr>
                    <tr>
                        <td>12345679</td>
                        <td>Budi Hartono</td>
                    </tr>
                    <tr>
                        <td>12345680</td>
                        <td>Citra Dewi</td>
                    </tr>
                
                    <!-- Kelompok 02 - 2 anggota -->
                    <tr>
                        <td rowspan="2">02</td>
                        <td>87654321</td>
                        <td>Siti Aminah</td>
                        <td rowspan="2">Web Semantik</td>
                        <td rowspan="2">Dr. Rudi</td>
                        <td rowspan="2">1122334</td>
                        <td rowspan="2">Prof. Dina</td>
                        <td rowspan="2">5566778</td>
                        <td rowspan="2">Dr. Wawan</td>
                        <td rowspan="2">7788990</td>
                        <td rowspan="2">Prof. Eko</td>
                        <td rowspan="2">3344556</td>
                    </tr>
                    <tr>
                        <td>87654322</td>
                        <td>Dewi Lestari</td>
                    </tr>
                </tbody>                
            </table>
        </div>

        <!-- Pagination Palsu -->
        <div class="d-flex justify-content-center">
            <nav>
                <ul class="pagination">
                    <li class="page-item disabled"><span class="page-link">«</span></li>
                    <li class="page-item active"><span class="page-link">1</span></li>
                    <li class="page-item"><a class="page-link" href="#">2</a></li>
                    <li class="page-item"><a class="page-link" href="#">3</a></li>
                    <li class="page-item"><a class="page-link" href="#">»</span></li>
                </ul>
            </nav>
        </div>
    </div>
    @endsection
