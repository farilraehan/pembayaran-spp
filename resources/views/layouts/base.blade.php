@php
    use App\Utils\Tanggal;
    $jatuhTempo = session('profil')->jatuh_tempo ?? null;
@endphp

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ env('APP_NAME') }} | {{ $title ?? 'Dashboard' }}</title>

    <link rel="icon" type="image/png" href="/assets/img/apple-icon.png">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@20..48,100..700,0..1,-50..200" />

    <link rel="stylesheet" href="/assets/css/nucleo-icons.css">
    <link rel="stylesheet" href="/assets/css/nucleo-svg.css">
    <link rel="stylesheet" href="/assets/css/material-dashboard.css?v=3.2.0">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/themes/default/style.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="/assets/tinymce/tinymce.min.js"></script>

    <style>
        .modal-fullscreen {
            z-index: 2000 !important;
        }
        .modal-backdrop.show {
            z-index: 1999 !important;
        }

        .card-body {
            transition: all 0.3s ease;
        }
        #editor,
        .ql-container {
            min-height: 20px
        }

        .table tbody tr {
            cursor: pointer;
        }

        .table tbody tr:hover {
            background-color: #f5f5f5;
        }

        .swal2-container {
            z-index: 99999 !important
        }

        .table {
            table-layout: fixed;
            width: 100%
        }

        .table tbody tr {
            height: 48px
        }
        .table thead th{
            font-size: 16px;
        }
        .table tbody td {
            font-size: 13px;
        }
        .table td input[type="checkbox"] {
            width: 20px;
            height: 20px;
            transform: scale(1);
        }
        .table td,
        .table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle
        }

        .td-action .action-container {
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            height: 100%
        }

        .table .btn {
            margin: 0
        }

        @media(max-width:576px) {
            #preview-img-box {
                width: 310px !important;
                height: 310px !important
            }
        }
        .twitter-typeahead {
            width: 100%;
            position: relative;
            display: block;
        }

        .twitter-typeahead .tt-input {
            width: 100%;
            font-size: 13px;
        }

        .form-search {
            border: none;
            border-bottom: 2px solid #dee2e6;
            border-radius: 0;
            padding-left: 0;
        }

        .form-search:focus {
            box-shadow: none;
            border-bottom-color: #37d17c;
        }

        .tt-menu {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            margin-top: 6px;
            background: #37d17c;
            max-height: 260px;
            z-index: 9999;
            border-radius: 10px;
            overflow-y: auto;
            padding: 4px 0;
        }

        .tt-suggestion {
            display: block !important;
            width: 100% !important;
            padding: 4px 8px !important;
            line-height: 1.25 !important;
            font-size: 11px !important;
            color: #000;
            background: #cecece;
            border-bottom: 1px solid rgba(0, 0, 0, 0.15);
            cursor: pointer;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .tt-suggestion * {
            margin: 0 !important;
            padding: 0 !important;
            line-height: 1.25 !important;
            font-size: 11px !important;
        }

        .tt-suggestion.tt-cursor {
            background: #6d6d6d;
            color: #fff;
            border-bottom-color: #37d17c;
        }

        .tt-suggestion:last-child {
            border-bottom: none;
        }

        @media (max-width: 768px) {
            .tt-suggestion {
                font-size: 12px !important;
                padding: 5px 8px !important;
            }
        }

        .tt-suggestion * {
            margin: 8px !important;
            padding: 0 !important;
            line-height: 1 !important;
        }

        .tt-suggestion:last-child {
            border-bottom-left-radius: 8px;
            border-bottom-right-radius: 8px;
        }

        .tt-suggestion:hover,
        .tt-suggestion.tt-cursor {
            background: #6d6d6d;
        }

        .tt-highlight {
            font-weight: 600;
        }

        .select2-container--bootstrap-5 * {
            --bs-primary: #37d17c;
        }

        .select2-container--bootstrap-5 .select2-selection {
            border: 1px solid #37d17c !important;
            box-shadow: none !important;
        }

        .select2-container--bootstrap-5.select2-container--focus .select2-selection {
            border-color: #37d17c !important;
            box-shadow: 0 0 0 .25rem rgba(53, 220, 89, 0.25) !important;
        }

        .select2-container--bootstrap-5 .select2-selection__rendered {
            color: #000 !important;
        }

        .select2-container--bootstrap-5 .select2-selection__arrow {
            color: #000 !important;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field {
            border: 1px solid #37d17c !important;
            outline: none !important;
            box-shadow: none !important;
            color: #000 !important;
        }

        .select2-container--bootstrap-5 .select2-search--dropdown .select2-search__field:focus {
            border-color: #37d17c !important;
            outline: none !important;
            box-shadow: 0 0 0 .2rem rgba(42, 249, 128, 0.25) !important;
        }

        .select2-container--bootstrap-5 .select2-dropdown {
            border: 1px solid #37d17c !important;
        }

        .select2-container--bootstrap-5 .select2-results__option {
            color: #000 !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--highlighted {
            background-color: #37d17c !important;
            color: #fff !important;
        }

        .select2-container--bootstrap-5 .select2-results__option--selected {
            background-color: rgba(53, 220, 73, 0.2) !important;
            color: #000 !important;
        }

        .select2-container--bootstrap-5 *:focus {
            outline: none !important;
        }

        /* teks yang tampil di select */
        .select2-container--bootstrap-5 .select2-selection__rendered {
            font-family: inherit !important;
            font-size: 0.875rem !important;
            /* sama dengan .form-control */
            font-weight: 400 !important;
            /* ini yang bikin tidak tebal */
            line-height: 1.5 !important;
            padding-left: 0.75rem !important;
        }

        /* input search di dropdown */
        .select2-container--bootstrap-5 .select2-search__field {
            font-family: inherit !important;
            font-size: 0.875rem !important;
            font-weight: 400 !important;
            line-height: 1.5 !important;
        }

        /* tinggi & alignment box select */
        .select2-container--bootstrap-5 .select2-selection {
            min-height: calc(1.5em + .75rem + 2px);
            display: flex;
            align-items: center;
        }

        /* arrow biar sejajar */
        .select2-container--bootstrap-5 .select2-selection__arrow {
            height: 100% !important;
        }


        /* NORMAL: belum klik */
        .select2-container--bootstrap-5 .select2-selection {
            border-color: #ced4da !important;
            box-shadow: none !important;
        }

        /* focus & focus-within TIDAK BOLEH ubah warna */
        .select2-container--bootstrap-5.select2-container--focus .select2-selection,
        .select2-container--bootstrap-5:focus-within .select2-selection {
            border-color: #ced4da !important;
            box-shadow: none !important;
        }

        /* hover tetap normal */
        .select2-container--bootstrap-5 .select2-selection:hover {
            border-color: #ced4da !important;
        }

        /*HANYA SAAT DIKLIK / DROPDOWN TERBUKA */
        .select2-container--bootstrap-5.select2-container--open .select2-selection {
            border-color: #37d17c !important;
            box-shadow: 0 0 0 .25rem rgba(53, 220, 103, 0.25) !important;
        }
        .material-symbols-rounded {
            font-family: 'Material Symbols Rounded' !important;
            font-variation-settings:
                'FILL' 0,
                'wght' 400,
                'GRAD' 0,
                'opsz' 24;
            font-size: 20px;
            line-height: 1;
            display: inline-flex;
            vertical-align: middle;
            white-space: nowrap;
        }
    </style>


    @yield('style')
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs fixed-start ms-2 my-2 bg-white border-radius-lg"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-xl-none"
                id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="/" target="_blank">
                <img src="/assets/img/apple-icon.png" width="35" height="35">
                <span class="ms-1 text-sm text-dark">Sistem Akademik</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        @include('layouts.sidebar')
    </aside>

    <main class="main-content position-relative">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            @include('layouts.navbar')
        </nav>

        <div class="container-fluid py-2">
            <div class="container-fluid">
                @yield('content')
            </div>
            <footer class="footer py-4">
                @include('layouts.footer')
            </footer>
        </div>
    </main>
    
    <div class="fixed-plugin">
        <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
            <span id="iconSettings" class="material-symbols-rounded py-2">settings</span>
        </a>
        <div class="card shadow-lg">
        <div class="card-header pb-0 pt-3">
            <div class="float-start">
            <h5 class="mt-3 mb-0">Material UI Configurator</h5>
            <p>See our dashboard options.</p>
            </div>
            <div class="float-end mt-4">
            <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
                <span class="material-symbols-rounded">clear</span>
            </button>
            </div>
        </div>
        <hr class="horizontal dark my-1">
        <div class="card-body pt-sm-3 pt-0">
            <div>
            <h6 class="mb-0">Sidebar Colors</h6>
            </div>
            <a href="javascript:void(0)" class="switch-trigger background-color">
            <div class="badge-colors my-2 text-start">
                <span class="badge filter bg-gradient-primary" data-color="primary" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-dark active" data-color="dark" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-info" data-color="info" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-success" data-color="success" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-warning" data-color="warning" onclick="sidebarColor(this)"></span>
                <span class="badge filter bg-gradient-danger" data-color="danger" onclick="sidebarColor(this)"></span>
            </div>
            </a>
            <div class="mt-5">
            <h6 class="mb-0">Sidenav Type</h6>
            <p class="text-sm">Choose between different sidenav types.</p>
            </div>
            <div class="d-flex ">
            <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark" onclick="sidebarType(this)">Dark</button>
            <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparent</button>
            <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" onclick="sidebarType(this)">White</button>
            </div>
            <p class="text-sm d-xl-none d-block mt-2">You can change the sidenav type just on desktop view.</p>
            <div class="mt-5 d-flex">
                <h6 class="mb-0">Navbar Fixed</h6>
                <div class="form-check form-switch ps-0 ms-auto my-auto">
                    <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
                </div>
            </div>
            <hr class="horizontal dark my-3">
            <div class="mt-2 d-flex">
            <h6 class="mb-0">Light / Dark</h6>
            <div class="form-check form-switch ps-0 ms-auto my-auto">
                <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
            </div>
            </div>
            <hr class="horizontal dark my-sm-4">
        </div>
        </div>
    </div>
    @yield('modal')

    <script>
        const icon = document.getElementById('iconSettings');
        let angle = 0;
        function rotate() {
            angle += 2; // kecepatan
            icon.style.transform = `rotate(${angle}deg)`;
            requestAnimationFrame(rotate);
        } rotate();
    </script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/jstree.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/typeahead.js/0.10.3/typeahead.jquery.min.js"></script>
    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/chartjs.min.js"></script>
    <script src="/assets/js/material-dashboard.min.js?v=3.2.0"></script>
    <script>
        const sideBar = document.querySelector('.sidenav'); 
        const modals = document.querySelectorAll('.modal-fullscreen');

        modals.forEach(modal => {
            modal.addEventListener('show.bs.modal', () => {
                sideBar.style.display = 'none'; // sembunyikan sidebar
            });
            modal.addEventListener('hidden.bs.modal', () => {
                sideBar.style.display = ''; // tampilkan lagi
            });
        });
    </script>
    <script>
        if (navigator.platform.includes('Win') && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), {
                damping: .5
            })
        }
    </script>
    <script>
        $(document).ready(function() {

            $('textarea.form-control').each(function() {
                if ($(this).val()) {
                    $(this).closest('.input-group').addClass('is-filled');
                }
            });

            $('textarea.form-control').on('focus input', function() {
                $(this).closest('.input-group').addClass('is-filled');
            });

            $('textarea.form-control').on('blur', function() {
                if (!$(this).val()) {
                    $(this).closest('.input-group').removeClass('is-filled');
                }
            });

        });
    </script>
    @if(session('msg') && $jatuhTempo)
        <script>
        (function () {
            const today = new Date();
            const day = today.getDate();
            const jatuhTempo = {{ (int) $jatuhTempo }};

            if (day === jatuhTempo) {
                Swal.fire({
                    icon: 'warning',
                    html: `
                        <strong>Waktunya Generate Tunggakan</strong><br>
                        {{ Tanggal::NamaBulan(now()) }} {{ Tanggal::tahun(now()) }}
                    `,
                    text: "{{ session('msg') }}",
                    showCancelButton: true,
                    confirmButtonText: 'Generate Sekarang',
                    cancelButtonText: 'Nanti'
                }).then((result) => {
                    if (result.isConfirmed) {
                        window.open(
                            '/app/system/generate-tunggakan/{{ time() }}',
                            '_blank'
                        );
                    }
                });
            } else {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'info',
                    title: "{{ session('msg') }}",
                    showConfirmButton: false,
                    timer: 3000
                });
            }
        })();
        </script>
    @endif


    <script>
        $('.btn-logout').on('click', function(e) {
            e.preventDefault()
            Swal.fire({
                title: 'Keluar dari aplikasi?',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, logout',
                cancelButtonText: 'Batal'
            }).then(v => {
                if (v.isConfirmed) $('#formLogout').submit()
            })
        })
    </script>
    @yield('script')
</body>

</html>
