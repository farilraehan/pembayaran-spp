<div class="container-fluid py-1 px-3">
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex flex-wrap"
        id="navbar">
        <ul class="navbar-nav d-flex align-items-center justify-content-end ms-auto order-0 order-md-2">
            <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                    <div class="sidenav-toggler-inner">
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                        <i class="sidenav-toggler-line"></i>
                    </div>
                </a>
            </li>
            <li class="nav-item px-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0">
                    <span class="material-symbols-rounded fixed-plugin-button-nav">settings</span>
                </a>
            </li>
            <li class="nav-item dropdown pe-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" data-bs-toggle="dropdown">
                    <span class="material-symbols-rounded">notifications</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4">
                    <li class="mb-2">
                        <a class="dropdown-item border-radius-md d-flex align-items-center" href="/app/profile">
                            <span class="material-symbols-rounded me-2">info</span>
                            <span>Belum ada notifikasi</span>
                        </a>
                    </li>
                </ul>
            </li>
            <li class="nav-item dropdown pe-3 d-flex align-items-center">
                <a href="javascript:;" class="nav-link text-body p-0" data-bs-toggle="dropdown">
                    <span class="material-symbols-rounded">account_circle</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end px-2 py-3 me-sm-n4">
                    <li class="mb-2">
                        <a class="dropdown-item border-radius-md d-flex align-items-center" href="/app/profile">
                            <span class="material-symbols-rounded me-2">person</span>
                            <span>Profil</span>
                        </a>
                    </li>
                    <li>
                        <form id="formLogout" action="/app/logout" method="POST">
                            @csrf
                            <button type="button"
                                class="dropdown-item border-radius-md d-flex align-items-center btn-logout">
                                <span class="material-symbols-rounded me-2">logout</span>
                                <span>Logout</span>
                            </button>
                        </form>
                    </li>
                </ul>
            </li>
        </ul>
        <div class="ms-3 w-100 w-md-auto order-1 order-md-0 text-center text-md-start mt-4 mt-lg-0">
            <h3 class="mb-0 h4 fw-bold">{{ $title }}</h3>
            <p class="mb-0 text-muted">System Akademik Berbasis ( IT )</p>
        </div>
    </div>
</div>
