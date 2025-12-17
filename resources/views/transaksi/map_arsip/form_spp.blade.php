<br>
<div class="row">
    <div class="col-8">
        <div class="card my-4">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div
                    class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3" id="headerTitle">
                        Pembayaran SPP - tahun ajaran {{ $siswa->tahun_akademik }}
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <form id="FormJenisBiaya" method="POST" action="/app/Jenis-biaya" class="text-start">
                    @csrf
                    <div class="row">
                        <div class="col-md-4">
                            <label for="tanggal">Tanggal</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" name="tanggal" class="form-control datepicker"
                                    value="{{ date('Y-m-d') }}" required>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="jenis_biaya">Jenis Biaya</label>
                            <div class="input-group input-group-outline mb-3">
                                <select name="jenis_biaya" id="jenis_biaya" class="form-control select2">
                                    <option value="">Pilih Jenis Biaya</option>
                                    <option value="spp">SPP</option>
                                    <option value="daftar_ulang">Daftar Ulang</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label for="kelas">Kelas</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" name="kelas" class="form-control" value="{{ $siswa->kode_kelas }}"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
                            <label>Bulan Dibayar</label>
                        </div>

                        {{-- Januari - Juni --}}
                        <div class="col-md-6 col-12 pe-md-3">
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach ($spp as $item)
                                    @php $bulan = \Carbon\Carbon::parse($item->tanggal)->month; @endphp
                                    @if ($bulan <= 6)
                                        <input type="checkbox" class="btn-check"
                                            name="tanggal[]" id="tgl_{{ $item->tanggal }}"
                                            value="{{ $item->tanggal }}">
                                        <label class="btn btn-outline-danger btn-sm rounded-pill"
                                            for="tgl_{{ $item->tanggal }}">
                                            {{ \App\Utils\Tanggal::NamaBulan($item->tanggal) }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        {{-- PEMISAH --}}
                        <div class="col-md-1 d-none d-md-flex align-items-stretch justify-content-center">
                            <div class="border-start"></div>
                        </div>

                        {{-- Juli - Desember --}}
                        <div class="col-md-5 col-12 ps-md-3">
                            <div class="d-flex flex-wrap gap-2 mt-1">
                                @foreach ($spp as $item)
                                    @php $bulan = \Carbon\Carbon::parse($item->tanggal)->month; @endphp
                                    @if ($bulan > 6)
                                        <input type="checkbox" class="btn-check"
                                            name="tanggal[]" id="tgl_{{ $item->tanggal }}"
                                            value="{{ $item->tanggal }}">
                                        <label class="btn btn-outline-info btn-sm rounded-pill"
                                            for="tgl_{{ $item->tanggal }}">
                                            {{ \App\Utils\Tanggal::NamaBulan($item->tanggal) }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <label for="angkatan">Keterangan</label>
                            <div class="input-group input-group-outline mb-3">
                                <textarea name="keterangan" id="keterangan" rows="1" class="form-control" cols="30"
                                    rows="10"></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-1">
                        <div class="col-md-6">
                            <label for="angkatan">Nominal</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="number" name="angkatan" class="form-control" required>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-1">
                        <button type="submit" class="btn btn-secondary" id="simpan">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    document.querySelectorAll('.btn-check').forEach(input => {
        input.addEventListener('change', function () {
            const label = document.querySelector(`label[for="${this.id}"]`);
            if (!label) return;

            if (this.checked) {
                if (!label.querySelector('.check-icon')) {
                    label.insertAdjacentHTML(
                        'afterbegin',
                        '<span class="check-icon me-1">✓</span>'
                    );
                }
            } else {
                const icon = label.querySelector('.check-icon');
                if (icon) icon.remove();
            }
        });
    });
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

    $(".nominal").maskMoney({
        allowNegative: true
    });

</script>
