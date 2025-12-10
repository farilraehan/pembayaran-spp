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
                            Edit Jenis dan Nominal Pembayaran
                        </h6>
                    </div>
                </div>
                <br>
                <div class="card-body">
                    <form id="FormJenisBiaya" method="POST" action="/app/Jenis-biaya/{{ $Jenis_biaya->id }}"
                        class="text-start">
                        @csrf
                        @method('PUT')
                        <div class="row">
                            <div class="col-md-4">
                                <label class="form-label">Masukkan tahun angkatan</label>
                                <div class="input-group input-group-outline mb-3">
                                    <input type="number" name="angkatan" class="form-control"
                                        value="{{ $Jenis_biaya->angkatan }}" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Pilih Jenis</label>
                                <div class="input-group input-group-outline mb-3">
                                    <select name="kode_akun" id="kode_akun" class="form-control select2">
                                        <option value="{{ $Jenis_biaya->getkeuanganJenis->id ?? '' }}">
                                            {{ $Jenis_biaya->getkeuanganJenis->nama_jenis ?? 'Pilih Jenis' }}
                                        </option>
                                        @foreach ($Rekening as $RK)
                                            <option value="{{ $RK->kode_akun }}"
                                                {{ $RK->kode_akun == $Jenis_biaya->kode_akun ? 'selected' : '' }}>
                                                {{ $RK->kode_akun }} - {{ $RK->nama_akun }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Total Beban</label>
                                <div class="input-group input-group-outline mb-3">
                                    <input type="text" name="total_beban" class="form-control nominal"
                                        value="{{ number_format($Jenis_biaya->total_beban, 2) }}" required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="/app/Jenis-biaya" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-info" id="simpan">Update Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script>
        $('.select2').select2({
            theme: 'bootstrap-5',
            allowClear: false
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
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: result.msg,
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true,
                            didClose: () => {
                                window.location.href = '/app/Jenis-biaya';
                            }
                        });
                    }
                },
                error: function(result) {
                    const response = result.responseJSON;
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                    if (response && typeof response === 'object') {
                        $.each(response, function(key, message) {
                            $('#' + key).closest('.input-group.input-group-static').addClass(
                                'is-invalid');
                            $('#msg_' + key).html(message);
                        });
                    }
                }
            });
        });
    </script>
@endsection
