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

    <style>
        .swal2-container {
            z-index: 99999 !important;
        }

        .dataTables_wrapper .dataTables_paginate {
            display: flex;
            justify-content: center;
            gap: 8px;
            margin-top: 10px;
            align-items: center;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button {
            border-radius: 50%;
            width: 32px;
            height: 32px;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 0;
            transition: background-color 0.3s, transform 0.2s;
            border: 1px solid rgba(0, 0, 0, 0.3);
            background: rgba(0, 0, 0, 0.05);
            color: inherit !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover:not(.disabled) {
            background-color: rgba(186, 186, 186, 0.315);
            transform: scale(1.1);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.current {
            background: linear-gradient(to bottom, rgba(171, 168, 168, 0) 0%, rgba(203, 24, 24, 0.05) 100%);
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:hover,
        .dataTables_wrapper .dataTables_paginate .paginate_button.disabled:active {
            cursor: default;
            color: #666 !important;
            border: 1px solid transparent;
            background: #eb1d1d00;
            box-shadow: none;
        }

        .dataTables_wrapper .dataTables_paginate .previous,
        .dataTables_wrapper .dataTables_paginate .next {
            border-radius: 4px;
            width: auto;
            height: auto;
            padding: 4px 12px;
            margin: 0 3px;
            background: #f0f0f0;
            border: 1px solid rgba(0, 0, 0, 0.2);
            color: inherit !important;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: background-color 0.3s, transform 0.2s;
        }

        .dataTables_wrapper .dataTables_paginate .previous:hover:not(.disabled),
        .dataTables_wrapper .dataTables_paginate .next:hover:not(.disabled) {
            background-color: rgba(186, 186, 186, 0.3);
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
