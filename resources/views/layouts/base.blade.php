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
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded">

    <link rel="stylesheet" href="/assets/css/nucleo-icons.css">
    <link rel="stylesheet" href="/assets/css/nucleo-svg.css">
    <link rel="stylesheet" href="/assets/css/material-dashboard.css?v=3.2.0">

    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/jstree@3.3.12/dist/themes/default/style.min.css">
    <link rel="stylesheet" href="https://cdn.quilljs.com/1.3.6/quill.snow.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

    <script src="{{ asset('vendor/tinymce/tinymce.min.js') }}"></script>

    <style>
        #editor,.ql-container{min-height:20px}
        .swal2-container{z-index:99999!important}
        .table{table-layout:fixed;width:100%}
        .table tbody tr{height:48px}
        .table td,.table th{white-space:nowrap;overflow:hidden;text-overflow:ellipsis;vertical-align:middle}
        .td-action .action-container{display:flex;justify-content:center;align-items:center;gap:8px;height:100%}
        .table .btn{margin:0}
        @media(max-width:576px){#preview-img-box{width:310px!important;height:310px!important}}
    
    .twitter-typeahead {
        width: 100%;
        position: relative;
        display: block;
    }

    .twitter-typeahead .tt-input {
        width: 100%;
    }

    .form-search {
        border: none;
        border-bottom: 2px solid #dee2e6;
        border-radius: 0;
        padding-left: 0;
    }

    .form-search:focus {
        box-shadow: none;
        border-bottom-color: #dc3545;
    }

    .tt-menu {
        position: absolute;
        top: 100%;
        left: 0;
        width: 100%;
        margin-top: 6px;
        background: #f70606;
        max-height: 360px;
        z-index: 9999;
        border-radius: 10px;
        overflow: hidden;
        padding-bottom: 4px;
    }

    .tt-suggestion {
        width: 100%;
        display: block !important;
        padding: 2px 5px !important;
        line-height: normal !important;  
        min-height: 0 !important;
        height: auto !important;
        font-size: 13px;
        color: #000000;
        background: #cecece;
        border-bottom: 1px solid rgba(255, 255, 255, 0.25);
    }
    @media (max-width: 768px) {
        .tt-suggestion {
            width: 150% !important;
        }
    }

    @media (max-width: 1024px) and (min-width: 769px) {
        .tt-suggestion {
            width: 300% !important;
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

</style>


    @yield('style')
</head>

<body class="g-sidenav-show bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs fixed-start ms-2 my-2 bg-white border-radius-lg" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-xl-none" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="/" target="_blank">
                <img src="/assets/img/apple-icon.png" width="35" height="35">
                <span class="ms-1 text-sm text-dark">Sistem Akademik</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        @include('layouts.sidebar')
    </aside>

    <main class="main-content position-relative">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
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

        @yield('modal')
    </main>

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
        if(navigator.platform.includes('Win')&&document.querySelector('#sidenav-scrollbar')){
            Scrollbar.init(document.querySelector('#sidenav-scrollbar'),{damping:.5})
        }
    </script>

    @if(session('success'))
    <script>
        Swal.fire({toast:true,position:'top-end',icon:'success',title:"{{ session('success') }}",showConfirmButton:false,timer:3000})
    </script>
    @endif

    <script>
        $('.btn-logout').on('click',function(e){
            e.preventDefault()
            Swal.fire({
                title:'Keluar dari aplikasi?',
                icon:'warning',
                showCancelButton:true,
                confirmButtonText:'Ya, logout',
                cancelButtonText:'Batal'
            }).then(v=>{if(v.isConfirmed)$('#formLogout').submit()})
        })
    </script>
    @yield('script')
</body>
</html>
