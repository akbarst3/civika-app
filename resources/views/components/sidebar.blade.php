<div class="d-flex flex-column flex-shrink-0 p-3 bg-light" style="width: 250px; height: calc(100vh - 56px); position: fixed; top: 56px; left: 0;">
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#berandaCollapse">
                <i class="fas fa-home"></i> Beranda
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#dataMahasiswaCollapse">
                <i class="fas fa-users"></i> Data Mahasiswa
            </a>
        </li>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#dataMahasiswaCollapse" aria-expanded="false">
                <i class="fas fa-book"></i> Data TA/PKL
                <i class="fas fa-chevron-down ms-auto"></i>
            </a>
            <div class="collapse" id="dataMahasiswaCollapse">
                <ul class="nav nav-pills flex-column ms-4">
                    <li class="nav-item">
                        <a href="{{ route('datapkl.import') }}" class="nav-link text-dark">
                            <i class="fas fa-file-excel me-2"></i> Import Excel
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('datapkl') }}" class="nav-link text-dark">
                            <i class="fas fa-table me-2"></i> Lihat Data
                        </a>
                    </li>
                </ul>
            </div>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#generateLaporanCollapse">
                <i class="fas fa-file-alt"></i> Generate Laporan
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#pencatatanAkademikCollapse">
                <i class="fas fa-pencil-alt"></i> Pencatatan Akademik
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#dataStatistikCollapse">
                <i class="fas fa-chart-bar"></i> Data Statistik
            </a>
        </li>
    </ul>
    <div class="mt-auto">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#pengaturanCollapse">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </li>
            <li class="nav-item">
                <a href="#" class="nav-link text-danger" data-bs-toggle="collapse" data-bs-target="#logoutCollapse">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </li>
        </ul>
    </div>
</div>