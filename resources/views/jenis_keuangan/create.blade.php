@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Jenis Biaya</h3>
            <p class="mb-4">Management System Pembayaran SPP</p>
        </div>
        <div class="col-12">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div
                        class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3" id="headerTitle">
                            Tambah Jenis dan Nominal Pembayaran
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <form id="FormJenisBiaya" method="POST" action="/app/keuangan-nominal" class="text-start">
                        @csrf
                        <div class="row">
                            <div class="col-md-12">
                                <label for="angkatan">Tahun Angkatan</label>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Masukkan tahun angkatan</label>
                                    <input type="number" name="angkatan" class="form-control" required>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="">Pilih Jenis Pembayaran/Nama jenis lain</label>
                                <div class="d-flex gap-2 mb-3">
                                    <div class="input-group input-group-outline flex-fill">
                                        <select name="nama_jenis_select" id="namaJenisSelect" class="form-control select2">
                                            <option value="">-- Pilih Jenis Pembayaran --</option>
                                            @foreach ($keuangan_jenis as $KJ)
                                                <option value="{{ $KJ->id }}">{{ $KJ->nama_jenis }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="input-group input-group-outline flex-fill">
                                        <label class="form-label">Nama Jenis Lain</label>
                                        <input type="text" name="nama_jenis_input" id="namaJenisInput"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-12">
                                <label for="total_beban">Total Beban</label>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label"> Masukkan Total Beban</label>
                                    <input type="text" name="total_beban" class="form-control nominal " required>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-info" id="simpan">Simpan data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap-5',
                placeholder: "-- Pilih Jenis Pembayaran  --",
                allowClear: true
            });

            $('#namaJenisSelect').on('change', function() {
                if ($(this).val()) {
                    $('#namaJenisInput').val('').prop('disabled', true);
                } else {
                    $('#namaJenisInput').prop('disabled', false);
                }
            });

            $('#namaJenisInput').on('input', function() {
                if ($(this).val().trim() !== '') {
                    $('#namaJenisSelect').val(null).trigger('change').prop('disabled', true);
                } else {
                    $('#namaJenisSelect').prop('disabled', false);
                }
            });
        });

        $(".nominal").maskMoney({
            allowNegative: true
        });

        $(document).on('click', '#simpan', function(e) {
            e.preventDefault();
            $('small').html('');

            var form = $('#FormJenisBiaya');
            var actionUrl = form.attr('action');

            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: form.serialize(),
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            text: "Simpan Jenis Biaya Baru ?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Lanjutkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload();
                            } else if (res.isDenied) {
                                window.location.href = '/app/keuangan-nominal';
                            }
                        });
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                    if (response && typeof response === 'object') {
                        $.each(response, function(key, message) {
                            $('#' + key)
                                .closest('.input-group.input-group-static')
                                .addClass('is-invalid');
                            $('#msg_' + key).html(message);
                        });
                    }
                }
            });
        });
    </script>
@endsection
