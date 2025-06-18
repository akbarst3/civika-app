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
                        <a href="{{ route('kp-pkl.import.form') }}" class="nav-link text-dark">
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
<div x-data="{ active: '' }" class="d-flex flex-column flex-shrink-0 bg-light p-3 sidebar-nav"
    style="width: 258px; height: 100%; position: fixed; left: 0; overflow-y: auto;">

    <!-- Logo & Judul -->
    <div class="d-flex align-items-center mb-4 mt-3">
        <img src="{{ asset('images/logo_polban.png') }}" alt="Logo" width="50" height="75" class="me-2" style="margin-top: 18px;">
        <span class="fs-5 fw-semibold">Tata Usaha</span>
    </div>

    <!-- Navigasi Sidebar -->
    <nav class="nav nav-pills flex-column gap-3 sidebar-nav mt-3" style="font-size: 15px;">

        <!-- Beranda -->
        <a href="#" class="nav-link text-dark d-flex align-items-center gap-2">
            <i class="fas fa-home"></i>
            <span>Beranda</span>
        </a>

        <!-- Data Mahasiswa -->
        <div class="w-100">
            <button @click="active === 'mahasiswa' ? active = '' : active = 'mahasiswa'" type="button"
                class="nav-link text-dark d-flex align-items-center justify-between w-100 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-users"></i>
                    <span style="text-align: left">Data Mahasiswa</span>
                </div>
                <i :class="active === 'mahasiswa' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="active === 'mahasiswa'" x-transition.duration.300ms class="ps-4 mt-1" x-cloak>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Lihat Mahasiswa</a>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Tambah Mahasiswa</a>
            </div>
        </div>

        <!-- Data TA -->
        <div class="w-100">
            <button @click="active === 'ta' ? active = '' : active = 'ta'" type="button"
                class="nav-link text-dark d-flex align-items-center justify-between w-100 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-folder"></i>
                    <span>Data TA</span>
                </div>
                <i :class="active === 'ta' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="active === 'ta'" x-transition.duration.300ms class="ps-4 mt-1" x-cloak>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Data TA</a>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Laporan PDPT</a>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Laporan Honor</a>
            </div>
        </div>

        <!-- Data KP -->
        <div class="w-100">
            <button @click="active === 'kp' ? active = '' : active = 'kp'" type="button"
                class="nav-link text-dark d-flex align-items-center justify-between w-100 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-folder"></i>
                    <span>Data KP</span>
                </div>
                <i :class="active === 'kp' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="active === 'kp'" x-transition.duration.300ms class="ps-4 mt-1" x-cloak>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Data PKL</a>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Laporan PDPT</a>
                <a href="{{route('generate.honor.kp-pkl.form') }}" class="d-block nav-link text-dark py-1 ps-4">Laporan Honor</a>
            </div>
        </div>

        <!-- Pencatatan Akademik -->
        <div class="w-100">
            <button @click="active === 'akademik' ? active = '' : active = 'akademik'" type="button"
                class="nav-link text-dark d-flex align-items-center justify-between w-100 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-pencil-alt"></i>
                    <span style="text-align: left; margin-left:3px;">Pencatatan Akademik</span>
                </div>
                <i :class="active === 'akademik' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="active === 'akademik'" x-transition.duration.300ms class="ps-4 mt-1" x-cloak>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Import Buku Besar</a>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">History Buku Besar</a>
            </div>
        </div>

        <!-- Data Statistik -->
        <a href="#" class="nav-link text-dark d-flex align-items-center gap-2">
            <i class="fas fa-chart-line"></i>
            <span>Data Statistik</span>
        </a>
    </nav>

    <!-- Footer Sidebar -->
    <div class="mt-auto pt-3 border-top">
        <nav class="nav nav-pills flex-column gap-2 sidebar-nav">
            <a href="#" class="nav-link text-dark d-flex align-items-center gap-2">
                <i class="fas fa-cog"></i>
                <span>Pengaturan</span>
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
