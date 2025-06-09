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
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">Laporan Honor</a>
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
                <a href="{{ route('import.buku-besar.status') }}" class="d-block nav-link text-dark py-1 ps-4">Import Buku Besar</a>
                <a href="{{ route('buku-besar') }}" class="d-block nav-link text-dark py-1 ps-4">History Buku Besar</a>
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
            <a href="#" class="nav-link text-danger d-flex align-items-center gap-2">
                <i class="fas fa-sign-out-alt"></i>
                <span>Logout</span>
            </a>
        </nav>
    </div>
</div>
