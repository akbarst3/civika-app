<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">
            @auth
                {{ ucfirst(auth()->user()->role) }}
            @else
                Guest
            @endauth
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto" style="margin-left: 8rem;">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="#">{{ $navtitle }}</a>
                </li>
            </ul>
            <div class="d-flex align-items-center ms-auto">
                <a href="#" class="position-relative me-3">
                    <i class="fa fa-bell fs-4"></i>
                    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                        style="width: 10px; height: 10px; padding:0;"></span>
                </a>
                <img src="https://ui-avatars.com/api/?name=User" alt="User" class="rounded-circle" width="32"
                    height="32">
            </div>
        </div>
    </div>
</nav>
