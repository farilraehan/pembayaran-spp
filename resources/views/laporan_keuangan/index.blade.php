@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Laporan Keuangan</h3>
        </div>
        <div class="col-12">
            <div class="card my-4">
                <div class="card-body px-0 pb-2">
                    <div class="table-responsive p-3">
                        <div class="row">
                            <div class="col-md-8">
                                <label class="form-label">Periode Laporan</label>
                                <div class="d-flex gap-2">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">tanggal awal</label>
                                        <input type="text" class="form-control datepicker">
                                    </div>
                                    <span class="d-flex align-items-center">s.d</span>
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">tanggal akhir</label>
                                        <input type="text" class="form-control datepicker">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Tahun Akademik</label>
                                <select class="form-control select2" name="state">
                                    <option value="AL">Alabama</option>
                                    ...
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label">Jenis Laporan</label>
                                <select class="form-control select2" name="state">
                                    <option value="AL">Alabama</option>
                                    ...
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Kelas</label>
                                <select class="form-control select2" name="state">
                                    <option value="AL">Alabama</option>
                                    ...
                                    <option value="WY">Wyoming</option>
                                </select>
                            </div>
                        </div>
                    </div>
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

        flatpickr('.datepicker', {
            dateFormat: "Y-m-d"
        });

        $('.datepicker').on('change', function() {
            $(this).parent().addClass('is-filled');
        });
    </script>
@endsection
