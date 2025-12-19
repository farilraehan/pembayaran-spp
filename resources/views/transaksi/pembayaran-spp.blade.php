@extends('layouts.base')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2 px-2">
                <div class="row  align-items-center">
                    <div class="col-md-9 col-12 ps-4">
                        <input type="text" id="pembayaranSPP" placeholder="Search NISN / Nama Siswa ...."
                            class="form-control form-search" autocomplete="off">
                    </div>
                    <div class="col-md-3 col-12 gap-3">
                        <button class="btn btn-danger w-100" id="btnDetailSiswa" type="button">Detail Siswa</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="accordion" class="col-12">
        <div class="mt-7">
            <div class="card-body text-center py-4">
                <i class="bi bi-person-search text-danger fs-1 mb-2"></i>
                <div class="mb-3">
                    <img src="/assets/img/siswa.png" class="img-fluid" style="max-height:220px;">
                </div>
                <h6 class="fw-bold mb-1">Data Siswa Tidak Ditemukan</h6>
                <p class="text-muted small mb-0">
                    Silakan lakukan pencarian atau periksa kembali NISN / Nama siswa.
                </p>
            </div>
        </div>
    </div>
</div>
<div class="col-md-12 mt-3 d-none" id="riwayat-transaksi">
    <div class="card h-100 position-relative">

        <button type="button" id="closeRiwayat"
            class="btn btn-link p-0 position-absolute top-0 end-0 m-3 text-secondary">
            <i class="material-symbols-rounded fs-5">close</i>
        </button>

        <div class="card-body pt-4 p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-0">
                    Riwayat Transaksi
                    <span id="riwayat-tanggal" class="text-xs text-muted"></span>
                </h6>
            </div>
            <ul class="list-group mb-3" id="list-riwayat"></ul>
        </div>
    </div>
</div>

<form action="" method="post" id="FormHapusTransaksi">
    @method('DELETE')
    @csrf
</form>
@endsection
@section('modal')
<div class="modal fade modal-fullscreen" id="detail" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-person-lines-fill me-1"></i> Detail Transaksi Siswa
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <div class="modal-body" id="detailContent">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-info-circle fs-2"></i>
                    <p class="mt-2">Silakan cari siswa terlebih dahulu</p>
                </div>
            </div>

            <div class="position-fixed bottom-0 end-0 p-4 d-flex gap-2">
                <button type="button" class="btn btn-secondary" id="btnPrintAllDetail">
                    <i class="bi bi-printer-fill me-1"></i> Print All
                </button>
                <button type="button" class="btn btn-danger" id="btnTutupDetail">
                    <i class="bi bi-x-circle me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade modal-fullscreen" id="CakboxAll" tabindex="-1">
    <div class="modal-dialog modal-fullscreen">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="bi bi-list-check me-1"></i> Pilih Transaksi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="CakboxAllContent">
                <div class="text-center text-muted py-5">
                    <i class="bi bi-info-circle fs-2"></i>
                    <p class="mt-2">Silakan cari siswa terlebih dahulu</p>
                </div>
            </div>
            <div class="position-fixed bottom-0 end-0 p-4 d-flex gap-2">
                <button type="button" class="btn btn-success" id="btnCetak">
                    <i class="bi bi-printer-fill me-1"></i> Cetak
                </button>
                <button type="button" class="btn btn-danger" id="btnTutupCakbox">
                    <i class="bi bi-x-circle me-1"></i> Tutup
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script>
    var numFormat = new Intl.NumberFormat('id-ID');
    var dataCustomer;

    //search
    $(document).on('click', '#closeRiwayat', function () {
        $('#riwayat-transaksi').addClass('d-none');
        $('#list-riwayat').empty();
    });

    $('#pembayaranSPP').typeahead({
        hint: true,
        highlight: true,
        minLength: 1
    }, {
        name: 'siswa',
        displayKey: 'name',
        source: function (query, process) {
            if (query.length < 2) return process([]);
            $.get('/app/spp/CariSiswa', {
                query: query
            }, function (result) {
                if (!result || !result.length) return process([]);
                process($.map(result, function (item) {
                    let inisial = item.package_inisial ? ' - ' + item.package_inisial :
                        '';
                    return {
                        name: item.nama + ' - ' + item.kode_kelas + inisial + ' [' +
                            item.nisn + ']',
                        item: item
                    };
                }));
            });
        }
    }).bind('typeahead:selected', function (e, data) {
        formTagihanBulanan(data.item);
    });

    function formTagihanBulanan(siswa) {
            $.get('/app/spp/Pembayaran-spp/' + siswa.id_siswa, function (result) {
                $('#accordion').html(result.view ?? '');
                dataCustomer = {
                    item: siswa,
                    rek_debit: result.rek_debit ?? null,
                    rek_kredit: result.rek_kredit ?? null
                };
            });
        }

    //detail siswa
    function loadTransaksiSiswa(mode = 'detail') {
        if (!dataCustomer || !dataCustomer.item) {
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: 'Silakan cari siswa terlebih dahulu'
            });
            return;
        }

        let idSiswa = dataCustomer.item.id_siswa;

        if (mode === 'detail') {
            let modal = '#detail';
            let content = '#detailContent';
            $(content).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-danger"></div>
                    <p class="mt-2">Memuat detail transaksi...</p>
                </div>
            `);
            $(modal).modal('show');
            $.get('/app/transaksi/pembayaranSPPDetail/' + idSiswa)
                .done(function(res){
                    $(content).html(res);
                })
                .fail(function(){
                    $(content).html(`<div class="alert alert-danger">Gagal memuat data</div>`);
                });
        }

        if (mode === 'printAll') {
            let modal = '#CakboxAll';
            let content = '#CakboxAllContent';
            $(content).html(`
                <div class="text-center py-5">
                    <div class="spinner-border text-success"></div>
                    <p class="mt-2">Memuat transaksi untuk dicetak...</p>
                </div>
            `);
            $(modal).modal('show');
            $.get('/app/transaksi/pembayaran/printAll/' + idSiswa)
                .done(function(res){
                    $(content).html(res);
                })
                .fail(function(){
                    $(content).html(`<div class="alert alert-danger">Gagal memuat data</div>`);
                });
        }
    }

    $(document).on('click', '#btnDetailSiswa', function() {
        loadTransaksiSiswa('detail');
    });

    $(document).on('click', '#btnPrintAllDetail', function() {
        loadTransaksiSiswa('printAll');
    });

    $(document).on('change', '#CakboxAllContent #checkAll', function(){
        $('#CakboxAllContent .checkItem').prop('checked', $(this).is(':checked'));
    });

    $(document).on('change', '#CakboxAllContent .checkItem', function(){
        $('#CakboxAllContent #checkAll').prop(
            'checked',
            $('#CakboxAllContent .checkItem:checked').length === $('#CakboxAllContent .checkItem').length
        );
    });

    $(document).on('click', '#btnCetak', function(e){
        e.preventDefault();
        let ids = [];
        $('#CakboxAllContent .checkItem:checked').each(function(){
            ids.push($(this).val());
        });
        if(ids.length === 0){
            Swal.fire({
                icon:'warning',
                title:'Perhatian',
                text:'Pilih minimal 1 transaksi untuk dicetak'
            });
            return;
        }
        let url = '/app/transaksi/pembayaran/printAllSelected?ids=' + ids.join(',');
        window.open(url, '_blank');
    });

    $(document).on('click', '#detail .btn-danger, #CakboxAll .btn-danger', function() {
        window.location.href = '/app/Transaksi/pembayaran-spp';
    });


    //simpan pembayaran spp
    $(document).on('click', '#SPPsimpan', function (e) {
        e.preventDefault();

        let $btn = $(this);
        $btn.prop('disabled', true);

        let form = $('#FormPembayaranSPP')[0];
        let actionUrl = $('#FormPembayaranSPP').attr('action');
        let formData = new FormData(form);

        $.ajax({
            type: 'POST',
            url: actionUrl,
            data: formData,
            contentType: false,
            processData: false,
            success: function (result) {
                if (!result.success) return;

                $('#riwayat-transaksi').removeClass('d-none');
                $('#list-riwayat').empty();

                let color, icon, title;
                if (result.tipe === 'spp') {
                    color = 'success';
                    icon = 'payments';
                    title = 'Pembayaran SPP';
                } else {
                    color = 'warning';
                    icon = 'assignment';
                    title = 'Daftar Ulang';
                }

                let detailHtml = '';
                if (result.detail_spp && result.detail_spp.length) {
                    detailHtml = '<ul class="ps-3 mb-1">';
                    result.detail_spp.forEach(item => {
                        detailHtml += `
                            <li class="text-xs">
                                ${item.bulan}
                                <span class="text-muted">(Rp ${numFormat.format(item.nominal)})</span>
                            </li>
                        `;
                    });
                    detailHtml += '</ul>';
                }

                let transaksiIds = Array.isArray(result.id_transaksi) ?
                    result.id_transaksi.join(',') :
                    result.id_transaksi;

                $('#riwayat-tanggal').text(result.tanggal);
                $('#list-riwayat').prepend(`
                    <li class="list-group-item border-0 ps-0 mb-2 border-radius-lg position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-start">
                                <button class="btn btn-icon-only btn-rounded btn-outline-${color} me-3 p-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="material-symbols-rounded text-lg">${icon}</i>
                                </button>

                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">${title}</h6>
                                    <span class="text-xs">${result.keterangan}</span>
                                    ${detailHtml}
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="text-${color} text-sm fw-bold mb-1">
                                    Rp ${numFormat.format(result.nominal)}
                                </div>
                            </div>
                        </div>

                        <div class="text-end mt-2">
                            <a href="/app/transaksi/kwitansi-spp?ids=${transaksiIds}"
                            target="_blank"
                            class="btn btn-outline-secondary btn-sm px-2 py-1">
                                <i class="bi bi-printer"></i> Cetak Struk
                            </a>
                        </div>
                    </li>
                `);

                Swal.fire({
                    icon: 'success',
                    title: 'Transaksi Berhasil',
                    text: result.msg,
                    confirmButtonText: 'OK Lanjutkan',
                });

                $('#FormPembayaranSPP')[0].reset();
            },
            error: function () {
                Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
            },
            complete: function () {
                $btn.prop('disabled', false);
            }
        });
    });

    //hapus
    $(document).on('click', '.btnDelete', function (e) {
        e.preventDefault();

        var hapus_id = $(this).attr('data-id');
        var actionUrl = '/app/transaksi/pembayaranSPPDestroy/' + hapus_id;

        Swal.fire({
            title: "Apakah Anda yakin?",
            text: "Transaksi akan dihapus secara permanen dari aplikasi dan tidak bisa dikembalikan!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonText: "Hapus",
            cancelButtonText: "Batal",
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                var form = $('#FormHapusTransaksi');
                $.ajax({
                    type: form.attr('method'),
                    url: actionUrl,
                    data: form.serialize(),
                    success: function (result) {
                        if (result.success) {
                            Swal.fire({
                                title: "Berhasil!",
                                text: result.msg,
                                icon: "success",
                                confirmButtonText: "OK"
                            }).then((res) => {
                                if (res.isConfirmed) {
                                    window.location.reload();
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
                    error: function (response) {
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
