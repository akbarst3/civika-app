@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 class="mb-0" style="font-weight:bold">Import Data Mahasiswa</h2>

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
                    <th>Nama Mahasiswa</th>
                    <th>Kota Lahir</th>
                    <th>Tanggal Lahir</th>
                    <th>Statur Mahasiswa</th>
                    <th>Alamat Mahasiswa</th>
                    <th>Nama Kabupaten</th>
                    <th>Kode Pos</th>
                    <th>Telpon</th>
                    <th>Email</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data Mahasiswa -->
                <tr>
                    <td>1</td>
                    <td>231511070</td>
                    <td>Aulia Putri Ramadhani</td>
                    <td>Kab. Garut</td>
                    <td>6 November 2006</td>
                    <td>Aktif</td>
                    <td>Jalan Ciwaruga No. 20</td>
                    <td>Kab. Garut</td>
                    <td>44151</td>
                    <td>081234567890</td>
                    <td>aulia@gmail.com</td>
                    <td>-></td>
                </tr>
                <tr>
                    <td>2</td>
                    <td>231511070</td>
                    <td>Aulia Putri Ramadhani</td>
                    <td>Kab. Garut</td>
                    <td>6 November 2006</td>
                    <td>Aktif</td>
                    <td>Jalan Ciwaruga No. 20</td>
                    <td>Kab. Garut</td>
                    <td>44151</td>
                    <td>081234567890</td>
                    <td>aulia@gmail.com</td>
                    <td>-></td>
                </tr>
                <tr>
                    <td>3</td>
                    <td>231511070</td>
                    <td>Aulia Putri Ramadhani</td>
                    <td>Kab. Garut</td>
                    <td>6 November 2006</td>
                    <td>Aktif</td>
                    <td>Jalan Ciwaruga No. 20</td>
                    <td>Kab. Garut</td>
                    <td>44151</td>
                    <td>081234567890</td>
                    <td>aulia@gmail.com</td>
                    <td>-></td>
                </tr>
                <tr>
                    <td>4</td>
                    <td>231511070</td>
                    <td>Aulia Putri Ramadhani</td>
                    <td>Kab. Garut</td>
                    <td>6 November 2006</td>
                    <td>Aktif</td>
                    <td>Jalan Ciwaruga No. 20</td>
                    <td>Kab. Garut</td>
                    <td>44151</td>
                    <td>081234567890</td>
                    <td>aulia@gmail.com</td>
                    <td>-></td>
                </tr>
                <tr>
                    <td>5</td>
                    <td>231511070</td>
                    <td>Aulia Putri Ramadhani</td>
                    <td>Kab. Garut</td>
                    <td>6 November 2006</td>
                    <td>Aktif</td>
                    <td>Jalan Ciwaruga No. 20</td>
                    <td>Kab. Garut</td>
                    <td>44151</td>
                    <td>081234567890</td>
                    <td>aulia@gmail.com</td>
                    <td>-></td>
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
