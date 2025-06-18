@extends('layouts.app')

@section('content')
<div class="container mt-5">
    <div class="d-flex align-items-center justify-content-between mb-4 flex-wrap gap-3">
        <h2 class="mb-0" style="font-weight:bold">Data Tugas Akhir</h2>

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
        <table class="table table-bordered fs-6">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Kota</th>
                    <th>NIM</th>
                    <th>Anggota KoTa</th>
                    <th>Topik Sesuai FTA Sidang</th>
                    <th>Tempat</th>
                    <th>Pembimbing 1</th>
                    <th>NIP</th>
                    <th>Pembimbing 2</th>
                    <th>NIP</th>
                    <th>Penguji 1</th>
                    <th>NIP</th>
                    <th>Penguji 2</th>
                    <th>NIP</th>
                </tr>
            </thead>
            <tbody>
                <!-- Data TA -->
                <tr>
                    <td>1</td>
                    <td>KoTA101</td>
                    <td>
                        211511003
                        211511004
                        211511020
                    </td>
                    <td>
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                    </td>
                    <td>
                        Bandung
                        Bandung
                        Bandung
                    </td>
                    <td>
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                    </td>
                    <td>J
                        0002039008
                        0002039008
                        0002039008
                    </td>
                    <td>
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                    </td>
                    <td>
                        0026126001
                        0026126001
                        0026126001
                    </td>
                    <td>
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                    </td>
                    <td>
                        0013126303
                        0013126303
                        0013126303
                    </td>
                    <td>
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                    </td>
                    <td>0015086205
                        0015086205
                        0015086205
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>KoTA101</td>
                    <td>
                        211511003
                        211511004
                        211511020
                    </td>
                    <td>
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                    </td>
                    <td>
                        Bandung
                        Bandung
                        Bandung
                    </td>
                    <td>
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                    </td>
                    <td>J
                        0002039008
                        0002039008
                        0002039008
                    </td>
                    <td>
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                    </td>
                    <td>
                        0026126001
                        0026126001
                        0026126001
                    </td>
                    <td>
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                    </td>
                    <td>
                        0013126303
                        0013126303
                        0013126303
                    </td>
                    <td>
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                    </td>
                    <td>0015086205
                        0015086205
                        0015086205
                    </td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>KoTA101</td>
                    <td>
                        211511003
                        211511004
                        211511020
                    </td>
                    <td>
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                        Pengembangan Aplikasi Pengukuran Capaian Pembelajaran Lulusan Berbasis Web Pada Perguruan Tinggi
                    </td>
                    <td>
                        Bandung
                        Bandung
                        Bandung
                    </td>
                    <td>
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                        Rahil Jumiyani, S.ST., M.Sc.
                    </td>
                    <td>J
                        0002039008
                        0002039008
                        0002039008
                    </td>
                    <td>
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                        Didik Suwito Pribadi, BSCS., M.Kom.
                    </td>
                    <td>
                        0026126001
                        0026126001
                        0026126001
                    </td>
                    <td>
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                        Dr. Nurjannah Syakrani, DRA., M.T.
                    </td>
                    <td>
                        0013126303
                        0013126303
                        0013126303
                    </td>
                    <td>
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                        Ir. Irawan Thamrin, M.T.
                    </td>
                    <td>0015086205
                        0015086205
                        0015086205
                    </td>
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