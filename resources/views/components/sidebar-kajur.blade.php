<div class="d-flex flex-column flex-shrink-0 p-3 bg-light"
    style="width: 250px; height: calc(100vh - 56px); position: fixed; top: 56px; left: 0;">
    
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="#berandaCollapse" class="nav-link text-dark d-flex align-items-center"
                data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="berandaCollapse">
                <i class="fas fa-home me-2"></i> Beranda
            </a>
            <div class="collapse" id="berandaCollapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                    <li>
                        <a href="{{ route('dashboard-reviewer1') }}" class="nav-link text-dark">
                            <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                        </a>
                    </li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="#dataMahasiswaCollapse" class="nav-link text-dark d-flex align-items-center"
                data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="dataMahasiswaCollapse">
                <i class="fas fa-users me-2"></i> Data Mahasiswa
            </a>
            <div class="collapse" id="dataMahasiswaCollapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                    <li><a href="#" class="nav-link text-dark">List Mahasiswa</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="#dataTaPklCollapse" class="nav-link text-dark d-flex align-items-center"
                data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="dataTaPklCollapse">
                <i class="fas fa-book me-2"></i> Data TA/PKL
            </a>
            <div class="collapse" id="dataTaPklCollapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                    <li><a href="#" class="nav-link text-dark">Lihat TA</a></li>
                    <li><a href="#" class="nav-link text-dark">Lihat PKL</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="#generateLaporanCollapse" class="nav-link text-dark d-flex align-items-center"
                data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="generateLaporanCollapse">
                <i class="fas fa-file-alt me-2"></i> Generate Laporan
            </a>
            <div class="collapse" id="generateLaporanCollapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                    <li><a href="#" class="nav-link text-dark">Laporan Mahasiswa</a></li>
                </ul>
            </div>
        </li>

        <li class="nav-item">
            <a href="#pencatatanAkademikCollapse" class="nav-link text-dark d-flex align-items-center"
                data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="pencatatanAkademikCollapse">
                <i class="fas fa-pencil-alt me-2"></i> Pencatatan Akademik
            </a>
            <div class="collapse" id="pencatatanAkademikCollapse">
                <ul class="btn-toggle-nav list-unstyled fw-normal pb-1 small ps-4">
                    <li><a href="#" class="nav-link text-dark">Input Nilai</a></li>
                </ul>
            </div>
        </li>
    </ul>
    <div class="mt-auto pt-3">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="nav-link text-danger border-0 bg-transparent d-flex align-items-center">
                <i class="fas fa-sign-out-alt me-2"></i> Logout
            </button>
        </form>
    </div>
</div>
