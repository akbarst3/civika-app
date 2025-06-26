<div class="d-flex flex-column flex-shrink-0 p-3 bg-light"
    style="width: 250px; height: calc(100vh - 56px); position: fixed; top: 56px; left: 0;">Add commentMore actions
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#" class="nav-link text-dark" data-bs-toggle="collapse" data-bs-target="#dashboardCollapse">
                <i class="fas fa-home"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="/mahasiswa/dashboard-pengaju" class="nav-link text-dark" data-bs-toggle="collapse"
                data-bs-target="#formPengajuanCollapse">
                <i class="fas fa-file-alt"></i> Form Pengajuan
            </a>
        </li>
        <li class="nav-item">
            <a href="/mahasiswa/daftar-pengajuan-surat" class="nav-link text-dark">
                <i class="fas fa-history"></i> Riwayat Pengajuan
            </a>
        </li>
    </ul>
    <div class="mt-auto">
        <ul class="nav nav-pills flex-column mb-auto">
            <li class="nav-item">
                <a href="#" class="nav-link text-dark" data-bs-toggle="collapse"
                    data-bs-target="#pengaturanCollapse">
                    <i class="fas fa-cog"></i> Pengaturan
                </a>
            </li>
            <li class="nav-item">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="nav-link text-danger border-0 bg-transparent d-flex align-items-center">
                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                </button>
            </form>
            </li>
        </ul>
    </div>
</div>
