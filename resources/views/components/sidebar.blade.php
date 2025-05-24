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
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#dataTaPklCollapse">
                <i class="fas fa-book"></i> Data TA/PKL
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#generateLaporanCollapse">
                <i class="fas fa-file-alt"></i> Generate Laporan
            </a>
        </li>
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#pencatatanAkademikCollapse" aria-expanded="false" aria-controls="pencatatanAkademikCollapse">
                <i class="fas fa-pencil-alt"></i> Pencatatan Akademik
            </a>
            <div class="collapse" id="pencatatanAkademikCollapse">
                <ul class="nav flex-column ms-3">
                    <li class="nav-item">
                        <a href="{{ route('import.buku-besar.index') }}" class="nav-link text-dark {{ request()->is('import-buku-besar') ? 'active' : '' }}">
                            Import Buku Besar
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="/history-buku-besar" class="nav-link text-dark {{ request()->is('history-buku-besar') ? 'active' : '' }}">
                            History Buku Besar
                        </a>
                    </li>
                </ul>
            </div>
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
