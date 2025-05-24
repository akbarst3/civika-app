@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 class="mb-0" style="font-weight:bold">Daftar Pembimbing dan Penguji Kerja Praktik</h2>

        <div class="d-flex gap-3 flex-wrap mt-3">
            <!-- Search Box -->
            <form class="d-flex mt-5" role="search">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                        <i class="fas fa-search"></i>
                    </span>
                    <input type="search" class="form-control border-start-0" placeholder="Search" aria-label="Search"
                        style="border-radius: 0 36px 36px 0;">
                </div>
            </form>

            <!-- Filter Angkatan -->
            <form class="mt-5">
                <div class="input-group">
                    <span class="input-group-text bg-white border-end-0" style="border-radius:36px 0 0 36px;">
                        <i class="fas fa-user-group"></i>
                    </span>
                    <select class="form-select border-start-0" style="border-radius: 0 36px 36px 0; min-width: 160px;">
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
            <tbody>
                <!-- Data Mahasiswa -->
                <tr>
                    <td>1</td>
                    <td>12345678</td>
                    <td>Andi Setiawan</td>
                    <td>PT Teknologi Nusantara</td>
                    <td>Dr. Budi</td>
                    <td>0123456</td>
                    <td>Prof. Rina</td>
                    <td>0654321</td>
                    <td>Dr. Sinta</td>
                    <td>0987654</td>
                    <td>Prof. Joko</td>
                    <td>0456123</td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>12345679</td>
                    <td>Budi Hartono</td>
                    <td>PT Teknologi Nusantara</td>
                    <td>Dr. Budi</td>
                    <td>0123456</td>
                    <td>Prof. Rina</td>
                    <td>0654321</td>
                    <td>Dr. Sinta</td>
                    <td>0987654</td>
                    <td>Prof. Joko</td>
                    <td>0456123</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>12345680</td>
                    <td>Citra Dewi</td>
                    <td>PT Teknologi Nusantara</td>
                    <td>Dr. Budi</td>
                    <td>0123456</td>
                    <td>Prof. Rina</td>
                    <td>0654321</td>
                    <td>Dr. Sinta</td>
                    <td>0987654</td>
                    <td>Prof. Joko</td>
                    <td>0456123</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>87654321</td>
                    <td>Siti Aminah</td>
                    <td>CV Solusi Digital</td>
                    <td>Dr. Rudi</td>
                    <td>1122334</td>
                    <td>Prof. Dina</td>
                    <td>5566778</td>
                    <td>Dr. Wawan</td>
                    <td>7788990</td>
                    <td>Prof. Eko</td>
                    <td>3344556</td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>87654322</td>
                    <td>Dewi Lestari</td>
                    <td>CV Solusi Digital</td>
                    <td>Dr. Rudi</td>
                    <td>1122334</td>
                    <td>Prof. Dina</td>
                    <td>5566778</td>
                    <td>Dr. Wawan</td>
                    <td>7788990</td>
                    <td>Prof. Eko</td>
                    <td>3344556</td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-center">
        <nav>
            <ul class="pagination">
                <li class="page-item disabled"><span class="page-link">«</span></li>
                <li class="page-item active"><span class="page-link">1</span></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">»</a></li>
            </ul>
        </nav>
    </div>
</div>
@endsection
