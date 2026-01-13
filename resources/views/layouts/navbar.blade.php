@php
    $jatuhTempo = session('profil')->jatuh_tempo ?? null;
@endphp

<div class="container-fluid py-1 px-3">
    <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4 d-flex flex-wrap" id="navbar">
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

            @if(session('msg') && $jatuhTempo && now()->day == (int) $jatuhTempo)
                <button type="button"
                    onclick="window.open('/app/system/generate-tunggakan/{{ time() }}', '_blank'); return false;"
                    class="btn btn-danger">
                    Generate Tunggakan
                </button>
            @endif

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

            <li class="nav-item dropdown pe-3 d-flex align-items-center" data-bs-auto-close="outside">
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
                    <li class="mb-2">
                        <a class="dropdown-item border-radius-md d-flex align-items-center"
                           href="#"
                           data-bs-toggle="modal"
                           data-bs-target="#TeknikalSupport">
                            <span class="material-symbols-rounded me-2">support</span>
                            <span>Teknikal Support</span>
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
<div class="modal fade modal-fullscreen" id="TeknikalSupport" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Teknikal Support</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body d-flex flex-column justify-content-center align-items-center text-center px-4">
                <p class="fs-4 mb-5" style="max-width: 900px;">
                    Jika Anda mengalami kendala teknis pada sistem,
                    seperti error, data tidak muncul, atau kesulitan penggunaan fitur,
                    silakan hubungi teknikal support kami.
                </p>

                <div class="row w-100 justify-content-center">
                    <div class="col-12 col-md-4 mb-4">
                        <a href="tel:6281234567890" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center py-5">
                                    <span class="material-symbols-rounded mb-3" style="font-size:80px;">
                                        support_agent
                                    </span>
                                    <h5 class="mb-1">Call Support</h5>
                                    <p class="mb-0 fs-5">+62 882-0066-44656</p>
                                </div>
                            </div>
                        </a>
                    </div>

                    <div class="col-12 col-md-4 mb-4">
                        <a href="#" id="waSupport" target="_blank" class="text-decoration-none text-dark">
                            <div class="card h-100 shadow-sm">
                                <div class="card-body d-flex flex-column justify-content-center align-items-center py-5">
                                    <span class="material-symbols-rounded mb-3" style="font-size:80px;">
                                        chat
                                    </span>
                                    <h5 class="mb-1">WhatsApp Support</h5>
                                    <p class="mb-0 fs-5">Chat Sekarang</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="modal-footer justify-content-end">
                <button type="button" class="btn btn-secondary px-5" data-bs-dismiss="modal">
                    Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('waSupport').addEventListener('click', function () {
    const pesan = `Halo Teknikal Support,

Saya ingin konsultasi terkait kendala pada sistem.

Halaman: ${document.title}
URL: ${window.location.href}

Terima kasih.`;

    const url = 'https://wa.me/6281229248209?text=' + encodeURIComponent(pesan);
    window.open(url, '_blank');
});
</script>
