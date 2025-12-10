@extends('layouts.base')
@section('content')
    <div class="card">
        <div class="card-body">
            <form action="/app/pelaporan/preview" method="GET" target="_blank">
                <div id="laporanRow" class="row g-3 mt-2">
                    {{-- Tahun --}}
                    <div class="col-md-4">
                        <label class="form-label">Pilih Tahun</label>
                        <select name="tahun" class="form-select select2">
                            @for ($i = 2020; $i <= date('Y'); $i++)
                                <option value="{{ $i }}" {{ $i == date('Y') ? 'selected' : '' }}>
                                    {{ $i }}</option>
                            @endfor
                        </select>
                    </div>

                    {{-- Bulan --}}
                    <div class="col-md-4">
                        <label class="form-label"> Pilih Bulan</label>
                        <select name="bulan" class="form-select select2">
                            @foreach ([
            '01' => 'JANUARI',
            '02' => 'FEBRUARI',
            '03' => 'MARET',
            '04' => 'APRIL',
            '05' => 'MEI',
            '06' => 'JUNI',
            '07' => 'JULI',
            '08' => 'AGUSTUS',
            '09' => 'SEPTEMBER',
            '10' => 'OKTOBER',
            '11' => 'NOVEMBER',
            '12' => 'DESEMBER',
        ] as $num => $name)
                                <option value="{{ $num }}" {{ $num == date('m') ? 'selected' : '' }}>
                                    {{ $num }}.
                                    {{ $name }}</option>
                            @endforeach
                        </select>
                    </div>


                    <div class="col-md-4 mb-3" style="padding-right: 10px;">
                        <label class="form-label">Pilih Hari</label>
                        <select name="hari" class="form-select select2">
                            <option value="">---</option>
                            @for ($i = 1; $i <= 31; $i++)
                                <option value="{{ $i }}">{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </option>
                            @endfor
                        </select>
                    </div>

                    <div id="colLaporan" class="col-md-6 mb-3" style="padding-left: 10px;">
                        <label class="form-label"> Pilih Nama Laporan</label>
                        <select id="laporan" name="laporan" class="form-select select2">
                            <option value="">---</option>
                            @foreach ($laporan as $item)
                                <option value="{{ $item->file }}"
                                    {{ request('laporan') == $item->file ? 'selected' : '' }}>
                                    {{ $item->nama_laporan }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Sub Laporan --}}
                    <div id="subLaporan" class="col-md-6">
                        <label class="form-label"> Pilih Nama Sub Laporan</label>
                        <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                            <option value="">---</option>
                        </select>
                    </div>

                    <div class="col-12">
                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="submit" name="action" value="excel" class="btn btn-success">Excel</button>
                            <button type="submit" name="action" value="preview" class="btn btn-primary">Preview</button>
                        </div>
                    </div>
            </form>
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
    <script>
        $('#laporan').on('change', function() {
            let file = $(this).val();

            if (file !== '') {
                $.ajax({
                    url: '/app/pelaporan/sub_laporan/' + file,
                    type: 'GET',
                    success: function(res) {
                        $('#subLaporan').html(res);

                        // aktifkan kembali select2
                        $('#sub_laporan').select2({
                            theme: 'bootstrap-5'
                        });
                    }
                });
            } else {
                $('#subLaporan').html(`
                <label class="form-label"> Pilih Nama Sub Laporan</label>
                <select name="sub_laporan" id="sub_laporan" class="form-select select2">
                    <option value="">---</option>
                </select>
            `);
            }
        });
    </script>
@endsection
