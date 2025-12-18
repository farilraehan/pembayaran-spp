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
@endsection

@section('script')
<script>
    var numFormat = new Intl.NumberFormat('en-EN',{minimumFractionDigits:2});
    var dataCustomer;

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

    $(document).on('click', '#SPPsimpan', function(e) {
            e.preventDefault();
            var form = $('#FormPembayaranSPP')[0];
            var actionUrl = $('#FormPembayaranSPP').attr('action');
            var formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            text: "Simpan Data Pembayaran SPP?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Lanjutkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload();
                            } else if (res.isDenied) {
                                window.location.href = '/print/spp/' + result.pembayaran_id;
                            }
                        });
                    }
                },
                error: function(result) {
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                }
            });
        });
</script>
@endsection
