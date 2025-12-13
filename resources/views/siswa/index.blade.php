@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Siswa</h3>
            <p class="mb-4">Management System Pembayaran SPP</p>
        </div>

        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div
                        class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3">Filter Data Siswa</h6>
                        <div class="d-flex align-items-center gap-3 pe-3">
                            <div class="input-group input-group-outline mb-3" style="width: 220px;">
                                <select id="tahun_akademik" class="form-control select2 text-white">
                                    <option value="">Pilih Tahun Akademik</option>
                                </select>
                            </div>
                            <div class="input-group input-group-outline mb-3" style="width: 200px;">
                                <select id="kelas" class="form-control select2 text-white">
                                    <option value="">Kelas</option>
                                </select>
                            </div>

                            <button id="btnFilter" class="btn btn-info text-white px-4">Lihat</button>
                            <button id="btnPrint" class="btn btn-success text-white px-4 btnPrint">Print Siswa</button>
                        </div>
                    </div>
                </div>

                <div class="card-body px-3 pb-3">
                    <div id="notifikasi">
                        <div class="alert alert-light alert-dismissible text-white" role="alert">
                            Silakan gunakan fitur Filter untuk menampilkan data siswa.
                            <button type="button" class="btn-close text-lg opacity-10" data-bs-dismiss="alert"></button>
                        </div>
                    </div>
                    <div class="table-responsive mt-3 d-none" id="tableWrapper">
                        <table id="siswa" class="table table-striped align-items-center mb-0">
                            <thead>
                                <tr>
                                    <th>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="checkAll">
                                        </div>
                                    </th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Angkatan</th>
                                    <th>Kode Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="loadingOverlay"
        style="display:none; position:fixed; top:0; left:0; width:100%; height:100%;
        background:rgba(255,255,255,0.8); z-index:9999; text-align:center;">

        <div style="position:absolute; top:45%; left:50%; transform:translate(-50%,-50%);">
            <div class="spinner-border text-primary" style="width:3rem; height:3rem;"></div>
            <p class="mt-3 fw-bold">Memproses data...</p>
        </div>
    </div>

    <form action="" method="post" id="FormHapusSiswa">
        @method('DELETE')
        @csrf
    </form>
@endsection
@section('script')
    <script>
        $(document).ready(function() {

            let urlParams = new URLSearchParams(window.location.search);
            let qs_tahun = urlParams.get('tahun_akademik');
            let qs_kelas = urlParams.get('kelas');
            let tahunLoaded = false;
            let kelasLoaded = false;

            function tryReloadTable() {
                if (!tahunLoaded || !kelasLoaded) return;

                let tahunVal = $('#tahun_akademik').val();
                let kelasVal = $('#kelas').val();

                if (!tahunVal || !kelasVal) {
                    $('#notifikasi').removeClass('d-none');
                    $('#tableWrapper').addClass('d-none');
                    return;
                }

                $('#notifikasi').addClass('d-none');
                $('#tableWrapper').removeClass('d-none');

                table.ajax.url(`/app/siswa?tahun_akademik=${tahunVal}&kelas=${kelasVal}`).load();
            }

            $('.select2').select2({
                theme: 'bootstrap-5'
            });

            $.getJSON('/app/siswa/listTahun', function(data) {
                let tahun = $('#tahun_akademik');
                tahun.empty().append('<option value="">Pilih Tahun Akademik</option>');
                data.forEach(t => tahun.append(`<option value="${t.nama_tahun}">${t.nama_tahun}</option>`));

                if (qs_tahun) {
                    $('#tahun_akademik').val(qs_tahun).trigger('change');
                }

                tahunLoaded = true;
                tryReloadTable();
            });

            $.getJSON('/app/siswa/listKelas', function(data) {
                let kelas = $('#kelas');
                kelas.empty().append('<option value="">Pilih Kelas</option>');
                data.forEach(k => kelas.append(
                    `<option value="${k.kode_kelas}">${k.kode_kelas} - ${k.nama_kelas}</option>`));

                if (qs_kelas) {
                    $('#kelas').val(qs_kelas).trigger('change');
                }

                kelasLoaded = true;
                tryReloadTable();
            });

            let table = $('#siswa').DataTable({
                processing: true,
                serverSide: true,
                searching: true,
                paging: false,
                info: true,
                autoWidth: true,
                scrollX: true,
                ajax: {
                    url: '/app/siswa',
                    data: function(d) {
                        d.tahun_akademik = $('#tahun_akademik').val();
                        d.kelas = $('#kelas').val();
                    }
                },
                columns: [{
                        width: "7%",
                        data: 'checkbox',
                        orderable: false,
                        searchable: false
                    },
                    {
                        width: "15%",
                        nama: 'nisn',
                        data: 'nisn'
                    },
                    {
                        width: "28%",
                        nama: 'nama',
                        data: 'nama'
                    },
                    {
                        width: "15%",
                        nama: 'angkatan',
                        data: 'angkatan'
                    },
                    {
                        width: "8%",
                        nama: 'kode_kelas',
                        data: 'kode_kelas'
                    },
                    {
                        width: "27%",
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false,
                        className: 'text-center td-action'
                    }
                ],
                drawCallback: function() {
                    $('#siswa').css('width', '100%');
                }
            });
            
            table.on('draw.dt', function() {
                $('.btnDetail').off('click').on('click', function() {
                    let id = $(this).data('id');
                    let tahun = $('#tahun_akademik').val();
                    let kelas = $('#kelas').val();
                    window.location.href =
                        `/app/siswa/${id}?tahun_akademik=${tahun}&kelas=${kelas}`;
                });

                $('.btnEdit').off('click').on('click', function() {
                    let id = $(this).data('id');
                    let tahun = $('#tahun_akademik').val();
                    let kelas = $('#kelas').val();
                    window.location.href =
                        `/app/siswa/${id}/edit?tahun_akademik=${tahun}&kelas=${kelas}`;
                });
            });

            if (qs_tahun || qs_kelas) {
                $('#notifikasi').addClass('d-none');
                $('#tableWrapper').removeClass('d-none');
                table.ajax.reload();
            }

            $('#btnFilter').click(function() {
                let tahun = $('#tahun_akademik').val();
                let kelas = $('#kelas').val();
                let params = new URLSearchParams(window.location.search);
                params.set('tahun_akademik', tahun);
                params.set('kelas', kelas);
                window.history.replaceState({}, '', `${location.pathname}?${params.toString()}`);
                $('#notifikasi').addClass('d-none');
                $('#tableWrapper').removeClass('d-none');
                table.ajax.reload(function () {
                    table.columns.adjust().draw(); 
                });
            });

            $('#checkAll').on('click', function () {
                $('.checkItem').prop('checked', this.checked);

                if (this.checked) {
                    Swal.fire({
                        title: 'Aksi Massal',
                        html: `
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Nama Kelas</label>
                                <div class="input-group input-group-outline mb-3">
                                    <select name="mutasi_kelas" id="mutasi_kelas" class="form-control kelas"></select>
                                </div>
                            </div>
                        </div>
                        `,
                        showCancelButton: true,
                        showDenyButton: true,
                        confirmButtonText: "Pindahkan Siswa",
                        denyButtonText: "Print Data Siswa",
                        cancelButtonText: "Cancel",
                        width: 600,

                        didOpen: () => {
                            $.getJSON('/app/siswa/listKelas', function(data) {
                                let kelas = $('#mutasi_kelas');
                                kelas.empty().append('<option value="">Pilih Kelas</option>');

                                data.forEach(k => {
                                    kelas.append(`<option value="${k.kode_kelas}-${k.tingkat}">${k.kode_kelas} - ${k.nama_kelas}</option>`);
                                });
                                $('#mutasi_kelas').select2({
                                    dropdownParent: $('.swal2-popup'),
                                    theme: 'bootstrap-5',
                                    placeholder: "Pilih Kelas",
                                    allowClear: true
                                });
                            });
                        }
                    }).then((result) => {

                        if (result.isConfirmed) {
                            pindahkanViaButton(
                                $('#mutasi_kelas').val(),
                            );
                        }

                        else if (result.isDenied) {
                            $('#btnPrint')[0].click();
                        }

                        else {
                            $('#checkAll').prop('checked', false);
                            $('.checkItem').prop('checked', false);
                        }
                    });
                }
            });

            $(document).on('click', '.btnMutasi', function () {
                let ids = $('.checkItem:checked').map(function () {
                    return $(this).val();
                }).get();

                if (ids.length === 0) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Belum ada siswa dipilih',
                        text: 'Silakan centang siswa yang ingin dipindahkan.',
                    });
                    return;
                }

                Swal.fire({
                    title: 'Mutasi Siswa',
                    html: `
                        <div class="row">
                            <div class="col-md-12">
                                <label class="form-label">Nama Kelas</label>
                                <div class="input-group input-group-outline mb-3">
                                    <select name="mutasi_kelas" id="mutasi_kelas" class="form-control kelas"></select>
                                </div>
                            </div>
                        </div>
                    `,
                    showCancelButton: true,
                    confirmButtonText: "Pindahkan",
                    didOpen: () => {
                        $.getJSON('/app/siswa/listKelas', function(data) {
                            let kelas = $('#mutasi_kelas');

                            kelas.empty().append('<option value="">Pilih Kelas</option>');

                            data.forEach(k => {
                                kelas.append(`<option value="${k.kode_kelas}-${k.tingkat}">${k.kode_kelas} - ${k.nama_kelas}</option>`);
                            });
                            $('#mutasi_kelas').select2({
                                dropdownParent: $('.swal2-popup'), 
                                theme: 'bootstrap-5',
                                placeholder: "Pilih Kelas",
                                allowClear: true
                            });
                        });
                    }
                }).then((r) => {
                    if (!r.isConfirmed) return;

                    pindahkanViaButton(
                        $('#mutasi_kelas').val(),
                        ids.join(',')
                    );
                });
            });
            
            $('#btnPrint').click(function() {
            let ids = [];

                if ($('#checkAll').is(':checked')) {
                    let allData = table.rows({ search: 'applied' }).data().toArray();
                    allData.forEach(row => {
                        ids.push(row.id);
                    });
                } else {
                    $('.checkItem:checked').each(function() {
                        ids.push($(this).val());
                    });
                }
                if (ids.length < 1) {
                    Swal.fire({
                        title: "Peringatan",
                        text: "Silakan pilih minimal satu siswa terlebih dahulu.",
                        icon: "warning",
                        confirmButtonText: "OK"
                    });
                    return;
                }
                window.open('/app/siswa/printSiswa?ids=' + ids.join(','), '_blank');
            });
        });

        //mutasi
        function pindahkanViaButton(kelas, singleId = null) {

            let ids = [];

            if (singleId) {
                ids = singleId.split(',');
            } else {
                $('.checkItem:checked').each(function () {
                    ids.push($(this).val());
                });
            }

            if (ids.length < 1) {
                Swal.fire("Peringatan", "Tidak ada siswa yang dipilih", "info");
                return;
            }

            $("#loadingOverlay").show();
            $("button").prop("disabled", true);

            $.ajax({
                type: "POST",
                url: "/app/siswa/mutasi",
                data: {
                    _token: $('meta[name="csrf-token"]').attr('content'),
                    ids: ids,
                    kelas: kelas
                },
                success: function (result) {

                    // Sembunyikan loading kembali
                    $("#loadingOverlay").hide();
                    $("button").prop("disabled", false);

                    if (result.success) {
                        Swal.fire({
                            title: "Berhasil!",
                            text: result.msg,
                            icon: "success",
                            confirmButtonText: "OK"
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.href =
                                    '/app/siswa?tahun_akademik=' + qs_tahun +
                                    '&kelas=' + qs_kelas;
                            }
                        });
                    } else {
                        Swal.fire("Gagal", result.msg, "error");
                    }
                },
                error: function () {

                    // Sembunyikan loading kembali
                    $("#loadingOverlay").hide();
                    $("button").prop("disabled", false);

                    Swal.fire("Error", "Terjadi kesalahan pada server", "error");
                }
            });
        }

        //hapus siswa        
        $(document).on('click', '.btnDelete', function(e) {
            e.preventDefault();
            let hapus_id = $(this).attr('data-id');
            let actionUrl = '/app/siswa/' + hapus_id;
            let urlParams = new URLSearchParams(window.location.search);
            let qs_tahun = urlParams.get('tahun_akademik');
            let qs_kelas = urlParams.get('kelas');

            Swal.fire({
                title: "Apakah Anda yakin?",
                text: "Data akan dihapus secara permanen dari aplikasi dan tidak bisa dikembalikan!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "Hapus",
                cancelButtonText: "Batal",
                reverseButtons: true
            }).then((result) => {
                if (result.isConfirmed) {
                    let form = $('#FormHapusSiswa');
                    $.ajax({
                        type: form.attr('method'),
                        url: actionUrl,
                        data: form.serialize(),
                        success: function(result) {
                            if (result.success) {
                                Swal.fire({
                                    title: "Berhasil!",
                                    text: result.msg,
                                    icon: "success",
                                    confirmButtonText: "OK"
                                }).then((res) => {
                                    if (res.isConfirmed) {
                                        window.location.href =
                                            '/app/siswa?tahun_akademik=' + qs_tahun +
                                            '&kelas=' + qs_kelas;
                                    }
                                });
                            } else {
                                Swal.fire({
                                    title: "Gagal",
                                    text: result.msg,
                                    icon: "info",
                                    confirmButtonText: "OK"
                                });
                            }
                        },
                        error: function(response) {
                            Swal.fire({
                                title: "Error",
                                text: "Terjadi kesalahan pada server. Silakan coba lagi.",
                                icon: "error",
                                confirmButtonText: "OK"
                            });
                        }
                    });
                } else if (result.dismiss === Swal.DismissReason.cancel) {
                    Swal.fire({
                        title: "Dibatalkan",
                        text: "Data tidak jadi dihapus.",
                        icon: "info",
                        confirmButtonText: "OK"
                    });
                }
            });
        });
    </script>
@endsection
