<div x-data="{ active: '' }" class="d-flex flex-column flex-shrink-0 bg-light p-3 sidebar-nav"
    style="width: 258px; height: 100%; position: fixed; left: 0; top: 0; overflow-y: auto; z-index: 1000;">

    <!-- Logo & Judul -->
    <div class="d-flex align-items-center mb-4 mt-3">
        <img src="{{ asset('images/logo_polban.png') }}" alt="Logo" width="50" height="75" class="me-2" style="margin-top: 18px;">
        <span class="fs-5 fw-semibold">Dosen</span>
    </div>

    <!-- Navigasi Sidebar -->
    <nav class="nav nav-pills flex-column gap-3 mt-4" style="font-size: 15px;">
        <!-- Pencatatan Akademik -->
        <div class="w-100">
            <button @click="active === 'akademik' ? active = '' : active = 'akademik'" type="button"
                class="nav-link text-dark d-flex align-items-center justify-content-between w-100 gap-2">
                <div class="d-flex align-items-center gap-2">
                    <i class="fas fa-pencil-alt"></i>
                    <span style="text-align: left; margin-left: 3px;">Pencatatan Akademik</span>
                </div>
                <i :class="active === 'akademik' ? 'fas fa-chevron-up' : 'fas fa-chevron-down'"></i>
            </button>
            <div x-show="active === 'akademik'" x-transition.duration.300ms class="ps-4 mt-1" x-cloak>
                <a href="#" class="d-block nav-link text-dark py-1 ps-4">History Buku Besar</a>
            </div>
        </div>
    </nav>
</div>
