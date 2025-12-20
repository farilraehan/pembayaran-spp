@extends('layouts.base')
@section('content')
    <div class="row">
        <form action="/app/laporan/preview" method="GET" target="_blank">
            <div class="col-12">
                <div class="card my-4">
                    <div class="card-body px-0 pb-2">
                        <div class="table-responsive p-3">
                            <div class="row">
                                <div class="col-md-8">
                                    <label class="form-label">Periode Laporan</label>
                                    <div class="d-flex gap-2">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label"></label>
                                            <input type="text" name="tgl_awal" class="form-control datepicker"
                                                value="{{ $tgl_awal }}">
                                        </div>
                                        <span class="d-flex align-items-center">s.d</span>
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label"></label>
                                            <input type="text" name="tgl_akhir" class="form-control datepicker"
                                                value="{{ $tgl_akhir }}">
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Tahun Akademik</label>
                                    <select class="form-control select2" name="tahun_akademik_id">
                                        <option value="">-- Semua Tahun Akademik --</option>
                                        @foreach ($tahunAkademik as $ta)
                                        <option value="{{ $ta->id }}">
                                            {{ $ta->nama_tahun }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <label class="form-label">Jenis Laporan</label>
                                    <select class="form-control select2" name="jenis_laporan" required>
                                        <option value="">-- Pilih Jenis Laporan --</option>
                                        <option value="spp">Laporan Pembayaran SPP</option>
                                        <option value="daftar_ulang">Laporan Pembayaran Daftar Ulang</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label">Kelas</label>
                                    <select class="form-control select2" name="kelas_id">
                                        <option value="">-- Semua Kelas --</option>
                                        @foreach ($kelas as $k)
                                        <option value="{{ $k->id }}">
                                            {{ $k->tingkat }} - {{ $k->nama_kelas }}
                                        </option>
                                        @endforeach
                                    </select>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 ">
                <div class="card my-2">
                    <div class="d-flex justify-content-between mt-3 me-3 ms-3">
                        <a href="/app/Transaksi/pembayaran-spp" class="btn btn-secondary">Kembali</a>
                        <button type="submit" name="action" value="preview" class="btn btn-primary">
                            Preview Laporan
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
    });

    flatpickr('.datepicker', {
        dateFormat: "Y-m-d"
    });

    $('.datepicker').on('change', function () {
        $(this).parent().addClass('is-filled');
    });

</script>
@endsection
