<header style="position: fixed; top: 0; left: 0px; right: 0; z-index: 0; background-color: white;" class="shadow-sm">

    {{-- Style margin-left dihapus dari nav karena parent-nya (header) sudah diatur posisinya --}}
    <nav class="navbar navbar-expand-lg navbar-light" style="height: 56px;">
        <div class="container-fluid">
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link active" style="font-weight:600; margin-left:250px" aria-current="page" href="#">{{ $navtitle }}</a>
                    </li>
                </ul>

                <div class="d-flex align-items-center ms-auto me-4">
                    <a href="#" class="position-relative me-4">
                        <i class="fa fa-bell fs-4" style="color: #717171;"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                            style="width: 10px; height: 10px; padding:0;"></span>
                    </a>
                    <img src="https://ui-avatars.com/api/?name=User" alt="User" class="rounded-circle" width="32"
                        height="32">
                </div>
            </div>
        </div>
    </nav>

</header>
