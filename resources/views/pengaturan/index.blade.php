@extends('layouts.base')
@section('content')
<style>
    .sop-content {
        display: none
    }

    .sop-content:target {
        display: block
    }

    .sop-setting:not(:has(:target)) #lembaga {
        display: block
    }

    .sop-menu .btn {
        background: #f8f9fa;
        border: 1px solid #dee2e6;
        color: #000
    }

    .sop-setting:not(:has(:target)) .sop-menu a[href="#lembaga"] {
        background: #7f7f80;
        color: #fff
    }

    .sop-wrapper:has(#lembaga:target) .sop-menu a[href="#lembaga"],
    .sop-wrapper:has(#logo:target) .sop-menu a[href="#logo"],
    .sop-wrapper:has(#kelas:target) .sop-menu a[href="#kelas"],
    .sop-wrapper:has(#jurusan:target) .sop-menu a[href="#jurusan"],
    .sop-wrapper:has(#ruangan:target) .sop-menu a[href="#ruangan"] {
        background: #7f7f80;
        color: #fff
    }
</style>
<div class="container-fluid py-4 sop-setting">
    <div class="row sop-wrapper">
        <div class="col-lg-3 col-md-4 mt-3">
            <div class="card shadow-sm">
                <div class="card-header fw-semibold">⚙️ SOP Pengaturan</div>
                <hr class="horizontal dark my-1">
                <div class="card-body d-grid sop-menu">
                    <a href="#lembaga" class="btn text-start">🏫 Lembaga</a>
                    <a href="#logo" class="btn text-start">🖼️ Logo</a>
                </div>
            </div>
        </div>
        <div class="col-lg-9 col-md-8 mt-3">
            <div class="sop-content card shadow-sm" id="lembaga">
                <div class="card-header">
                    <h5 class="mb-0">Pengaturan Lembaga</h5>
                </div>
                <div class="card-body">@include('pengaturan.view.lembaga')</div>
            </div>
            <div class="sop-content card shadow-sm" id="logo">
                <div class="card-header">
                    <h5 class="mb-0">Pengaturan Logo</h5>
                </div>
                <div class="card-body">@include('pengaturan.view.logo')</div>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    });

    $(document).on('click', '#SimpanLembaga', function(e) {
        e.preventDefault();
        $('small').html('');

        var form = $('#FormLembaga');
        var actionUrl = form.attr('action');

            $.ajax({
                type: 'PUT',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {

                        const Toast = Swal.mixin({
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didOpen: (toast) => {
                                toast.addEventListener('mouseenter', Swal.stopTimer)
                                toast.addEventListener('mouseleave', Swal.resumeTimer)
                            }
                        });

                        Toast.fire({
                            icon: 'success',
                            title: result.msg
                        }).then(() => {
                            window.location.reload();
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');

                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('#' + key)
                                .closest('.input-group-outline')
                                .addClass('is-invalid');
                            $('#msg_' + key).html(value[0]);
                        });
                    }
                }
            });
    });

    $(document).on('click', '#SimpanLogo', function (e) {
        e.preventDefault();

        let form = document.getElementById('FormLogo');
        let actionUrl = form.action;
        let formData = new FormData(form);

        $.ajax({
            url: actionUrl,
            type: 'POST',
            data: formData,
            processData: false, 
            contentType: false, 
            headers: {
                'X-HTTP-Method-Override': 'PUT' 
            },
            success: function (result) {

                const Toast = Swal.mixin({
                    toast: true,
                    position: 'top-end',
                    showConfirmButton: false,
                    timer: 3000,
                    timerProgressBar: true
                });

                Toast.fire({
                    icon: 'success',
                    title: result.msg
                }).then(() => {
                    window.location.reload();
                });
            },
            error: function (xhr) {
                Swal.fire('Error', 'Logo belum dipilih atau format salah', 'error');
                console.log(xhr.responseJSON);
            }
        });
    });

</script>
@endsection