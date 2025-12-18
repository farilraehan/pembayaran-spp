@extends('layouts.base')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-body py-2 px-2">
                <div class="row  align-items-center">
                    <div class="col-md-9 col-12 ps-4">
                        <input type="text" id="pembayaranSPP" placeholder="Search NISN / Nama Siswa ...." class="form-control form-search" autocomplete="off">
                    </div>
                    <div class="col-md-3 col-12 gap-3">
                        <button class="btn btn-danger w-100" type="button">Detail Siswa</button>
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
        <button type="button"
                id="closeRiwayat"
                class="btn btn-link p-0 position-absolute top-0 end-0 m-3 text-secondary">
            <i class="material-symbols-rounded fs-5">close</i>
        </button>
        <div class="card-body pt-4 p-3">
            <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                Riwayat Transaksi
            </h6>
            <ul class="list-group mb-3" id="list-riwayat"></ul>
        </div>
    </div>
</div>

@endsection

@section('script')
<script>
    var numFormat = new Intl.NumberFormat('id-ID');
    var dataCustomer;

    $(document).on('click', '#closeRiwayat', function () {
        $('#riwayat-transaksi').addClass('d-none');
        $('#list-riwayat').empty(); 
    });

    $('#pembayaranSPP').typeahead({
        hint:true,
        highlight:true,
        minLength:1
    },{
        name:'siswa',
        displayKey:'name',
        source:function(query,process){
            if(query.length<2) return process([]);
            $.get('/app/spp/CariSiswa',{query:query},function(result){
                if(!result||!result.length) return process([]);
                process($.map(result,function(item){
                    let inisial=item.package_inisial?' - '+item.package_inisial:'';
                    return{
                        name:item.nama+' - '+item.kode_kelas+inisial+' ['+item.nisn+']',
                        item:item
                    };
                }));
            });
        }
    }).bind('typeahead:selected',function(e,data){
        formTagihanBulanan(data.item);
    });

    function formTagihanBulanan(siswa){
        $.get('/app/spp/Pembayaran-spp/'+siswa.id_siswa,function(result){
            $('#accordion').html(result.view??'');
            dataCustomer={
                item:siswa,
                rek_debit:result.rek_debit??null,
                rek_kredit:result.rek_kredit??null
            };
        });
    }

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

                $('#list-riwayat').prepend(`
                    <li class="list-group-item border-0 ps-0 mb-2 border-radius-lg position-relative">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="d-flex align-items-center">
                                <button class="btn btn-icon-only btn-rounded btn-outline-${color} me-3 p-3 btn-sm d-flex align-items-center justify-content-center">
                                    <i class="material-symbols-rounded text-lg">${icon}</i>
                                </button>

                                <div class="d-flex flex-column">
                                    <h6 class="mb-1 text-dark text-sm">${title}</h6>
                                    <span class="text-xs">${result.keterangan}</span>
                                    <span class="text-xs text-muted">${result.tanggal}</span>
                                </div>
                            </div>

                            <div class="text-end">
                                <div class="text-${color} text-sm fw-bold mb-1">
                                    Rp ${numFormat.format(result.nominal)}
                                </div>
                            </div>
                        </div>

                        <a href="/app/transaksi/struk/${result.id_transaksi}"
                            target="_blank"
                            class="btn btn-outline-secondary btn-sm px-1 py-1 position-absolute end-0">
                            <i class="bi bi-printer"></i> Cetak Struk
                        </a>
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
</script>
@endsection
