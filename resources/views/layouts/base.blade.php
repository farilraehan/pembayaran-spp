<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/assets/img/apple-icon.png">
    <link rel="icon" type="image/png" href="/assets/img/apple-icon.png">
    <title>{{ env('APP_NAME') }} | {{ $title ?? 'Dashboard' }}</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900">
    <link rel="stylesheet" href="/assets/css/nucleo-icons.css">
    <link rel="stylesheet" href="/assets/css/nucleo-svg.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded">
    <link id="pagestyle" rel="stylesheet" href="/assets/css/material-dashboard.css?v=3.2.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/themes/default/style.min.css" />
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>
    <style>
        /* tinggi editor (area tulis) */

        #editor {

            min-height: 20px;

            /* ⬅️ pendek */

        }



        /* tinggi konten quill */

        .ql-container {

            min-height: 20px;

        }

        .swal2-container {
            z-index: 99999 !important;
        }

        @media (max-width: 576px) {
            #preview-img-box {
                width: 310px !important;
                height: 310px !important;
            }
        }

        .table tbody tr {
            height: 48px !important;
            width: 100%;
        }

        .table tbody td {
            vertical-align: middle !important;
            width: 100%;
        }

        .table {
            table-layout: fixed !important;
            width: 100% !important;
        }

        .table td,
        .table th {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .td-action .action-container {
            height: 100%;
            display: flex !important;
            justify-content: center !important;
            align-items: center !important;
            gap: 8px;
        }

        .table .btn {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
    </style>
    @yield('style')
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2 bg-white my-2"
        id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
                id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="/" target="_blank">
                <img src="/assets/img/apple-icon.png" class="navbar-brand-img" width="35" height="35">
                <span class="ms-1 text-sm text-dark">Sistem Akademik</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        @include('layouts.sidebar')
    </aside>

    <main class="main-content position-relative ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur"
            data-scroll="true">
            @include('layouts.navbar')
        </nav>

        <div class="container-fluid py-2">
            <div class="app-content">
                <div class="container-fluid">@yield('content')</div>
            </div>

            <footer class="footer py-4">
                @include('layouts.footer')
            </footer>
        </div>

        @yield('modal')
    </main>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <script src="https://cdn.datatables.net/1.13.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-maskmoney/3.0.2/jquery.maskMoney.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

    <script src="/assets/js/core/popper.min.js"></script>
    <script src="/assets/js/core/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/jstree.min.js"></script>
    <script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>
    <script src="/assets/js/plugins/perfect-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/smooth-scrollbar.min.js"></script>
    <script src="/assets/js/plugins/chartjs.min.js"></script>

    <script>
        if (document.getElementById("chart-bars")) {
            const ctx = document.getElementById("chart-bars").getContext("2d");
            new Chart(ctx, {
                type: "bar",
                data: {
                    labels: ["M", "T", "W", "T", "F", "S", "S"],
                    datasets: [{
                        label: "Views",
                        backgroundColor: "#43A047",
                        data: [50, 45, 22, 28, 50, 60, 76],
                        borderRadius: 4
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        if (document.getElementById("chart-line")) {
            const ctx2 = document.getElementById("chart-line").getContext("2d");
            new Chart(ctx2, {
                type: "line",
                data: {
                    labels: ["J", "F", "M", "A", "M", "J", "J", "A", "S", "O", "N", "D"],
                    datasets: [{
                        label: "Sales",
                        borderColor: "#43A047",
                        pointBackgroundColor: "#43A047",
                        borderWidth: 2,
                        data: [120, 230, 130, 440, 250, 360, 270, 180, 90, 300, 310, 220],
                        fill: false
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }

        if (document.getElementById("chart-line-tasks")) {
            const ctx3 = document.getElementById("chart-line-tasks").getContext("2d");
            new Chart(ctx3, {
                type: "line",
                data: {
                    labels: ["Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"],
                    datasets: [{
                        label: "Tasks",
                        borderColor: "#43A047",
                        pointBackgroundColor: "#43A047",
                        borderWidth: 2,
                        data: [50, 40, 300, 220, 500, 250, 400, 230, 500],
                        fill: false
                    }]
                },
                options: {
                    responsive: true
                }
            });
        }
    </script>

    <script>
        if (navigator.platform.indexOf('Win') > -1 && document.querySelector('#sidenav-scrollbar')) {
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'), {
                damping: '0.5'
            });
        }
    </script>

    <script src="/assets/js/material-dashboard.min.js?v=3.2.0"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: "{{ session('success') }}",
                showConfirmButton: false,
                timer: 3000
            });
        </script>
    @endif

    <script>
        $('.btn-logout').on('click', function(e) {
            e.preventDefault();
            Swal.fire({
                title: "Keluar dari aplikasi?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Ya, logout",
                cancelButtonText: "Batal"
            }).then((v) => {
                if (v.isConfirmed) {
                    $('#formLogout').submit();
                }
            });
        });
    </script>

    @yield('script')
</body>

</html>
