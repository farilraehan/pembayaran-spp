<br>
<div class="row d-flex align-items-stretch">
    <div class="col-md-8 d-flex">
        <div class="card my-4 flex-fill">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div
                    class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3">
                        Rekap - tahun ajaran {{ $siswa->tahun_akademik }}
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <form method="POST" action="/app/transaksi/ProsesPembayaran" id="FormPembayaranSPP">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <input type="hidden" name="siswa_nama" id="siswa_nama" value="{{ $siswa->nama }}">
                    <div class="row mt-3">
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('tanggal', date('Y-m-d')) ? 'is-filled' : '' }}">
                                <label class="form-label">Tanggal</label>
                                <input type="text" name="tanggal" class="form-control datepicker"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('kelas', $siswa->kode_kelas) ? 'is-filled' : '' }}">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control"
                                    value="{{ $siswa->kode_kelas }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row mb-2">
                        <div class="col-md-6 mb-2 d-none">
                            <select name="sumber_dana" id="sumber_dana" class="form-control select2">
                                <option value="0">Sumber Pembayaran</option>
                                @foreach ($sumber_dana as $sd)
                                    <option value="{{ $sd->kode_akun }}">{{ $sd->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-12 mb-2">
                            <select name="jenis_biaya" id="jenis_biaya" class="form-control select2">
                                <option value="0">Pilih Jenis Pembayaran</option>
                                @foreach ($jenis_biaya as $jb)
                                    <option value="{{ $jb->kode_akun }}">{{ $jb->nama_akun }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="row mt-2" id="bulanWrapper" style="display: none;">
                        <div class="col-12 mt-2">
                            <label>Bulan Dibayar</label>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="d-flex flex-wrap gap-0 mt-1">
                                @foreach ($spp->sortBy(function ($item) {
                                    return \Carbon\Carbon::parse($item->tanggal)->month;
                                }) as $item)
                                    @php
                                        $bulan = \Carbon\Carbon::parse($item->tanggal)->month;
                                    @endphp

                                    @if ($bulan > 6)
                                        <input type="checkbox" name="bulan_dibayar[]" class="btn-check spp-checkbox"
                                            data-id="{{ $item->id }}" data-nominal="{{ $item->nominal }}"
                                            id="tgl_{{ $item->id }}" value="{{ $item->tanggal }}"
                                            data-spp_ke="{{ $item->spp_ke }}"
                                            {{ $item->status == 'L' ? 'checked disabled' : '' }}>

                                        <label
                                            class="btn btn-sm rounded-pill flex-fill text-center
                                            {{ $item->status == 'L' ? 'btn-info' : 'btn-outline-info' }}"
                                            for="tgl_{{ $item->id }}">
                                            {{ \App\Utils\Tanggal::namaBulan($item->tanggal) }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12 mt-2">
                            <div class="d-flex flex-wrap gap-0 mt-1">
                                @foreach ($spp->sortBy(function ($item) {
                                        return \Carbon\Carbon::parse($item->tanggal)->month;
                                    }) as $item)
                                    @php
                                        $bulan = \Carbon\Carbon::parse($item->tanggal)->month;
                                    @endphp

                                    @if ($bulan <= 6)
                                        <input type="checkbox" name="bulan_dibayar[]" class="btn-check spp-checkbox"
                                            data-id="{{ $item->id }}" data-spp_ke="{{ $item->spp_ke }}"
                                            data-nominal="{{ $item->nominal }}" id="tgl_{{ $item->id }}"
                                            value="{{ $item->tanggal }}"
                                            {{ $item->status == 'L' ? 'checked disabled' : '' }}>

                                        <label
                                            class="btn btn-sm rounded-pill flex-fill text-center
                                            {{ $item->status == 'L' ? 'btn-danger' : 'btn-outline-danger' }}"
                                            for="tgl_{{ $item->id }}">
                                            {{ \App\Utils\Tanggal::namaBulan($item->tanggal) }}
                                        </label>
                                    @endif
                                @endforeach
                            </div>
                        </div>

                        <div id="sppKEContainer"></div>
                        <div id="sppIDContainer"></div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('keterangan') ? 'is-filled' : '' }}">
                                <label class="form-label">Keterangan</label>
                                <textarea name="keterangan" id="keterangan" rows="1" class="form-control">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('nominal', $siswa->spp_nominal) ? 'is-filled' : '' }}">
                                <label class="form-label">Nominal</label>
                                <input type="text" name="nominal" id="nominal" class="form-control nominal"
                                    readonly>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex
                            flex-column flex-md-row
                            align-items-stretch align-items-md-center
                            justify-content-md-end
                            gap-2
                            p-2 pb-1">
                    <button type="button" id="kuitansi"
                        class="btn btn-outline-secondary btn-sm d-none w-100 w-md-auto">
                        Kwitansi
                    </button>
                    <button type="button" id="CetakPadaKartu"
                        class="btn btn-outline-info btn-sm d-none w-100 w-md-auto">
                        Cetak Pada Kartu
                    </button>
                    <button class="btn btn-danger w-100 w-md-auto"
                        id="btnDetailSiswa" type="button">
                        Detail
                    </button>
                    <button type="submit" id="Tunai"
                        data-sumber="1.1.01.01"
                        class="btn btn-warning w-100 w-md-auto SPPsimpan">
                        Tunai
                    </button>
                    <button type="submit" id="TransferBank"
                        data-sumber="1.1.01.03"
                        class="btn btn-info w-100 w-md-auto SPPsimpan">
                        Transfer Bank
                    </button>
                </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-flex">
        <div class="card my-4 flex-fill">
            <div class="card-header bg-gradient-white text-dark">
                <h6 class="mb-0 text-bold">Grafik SPP</h6>
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body text-center pt-0">
                <canvas id="pie" height="270"></canvas>
            </div>
        </div>
    </div>
</div>
@if($kode_tunggakan->count())
<div id="toast-wrapper"
     class="position-fixed bottom-0 end-0 p-3"
     style="z-index:99999">
    @foreach($kode_tunggakan as $t)
        <div class="toast mb-2" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto text-danger">Tunggakan SPP</strong>
                <button class="btn-close" data-bs-dismiss="toast"></button>
            </div>
            <div class="toast-body">
                {{ $t->keterangan }}
            </div>
        </div>
    @endforeach
</div>
@endif
<script>
document.querySelectorAll('#toast-wrapper .toast').forEach(el => {
    const toast = new bootstrap.Toast(el, {
        delay: 3000,
        autohide: true
    });

    toast.show();

    el.addEventListener('hidden.bs.toast', () => {
        el.remove();

        const wrapper = document.getElementById('toast-wrapper');
        if (wrapper && wrapper.children.length === 0) {
            wrapper.remove();
        }
    });
});
</script>

<script>
    const pieColors = ["#ff6384", "#36a2eb", "#ffcd56"];
    const sppPerBulan = {{ $spp_perbulan ?? 0 }};
    const targetBulan = {{ $target_bulan ?? 0 }};
    const sdBulanIni = {{ $sd_bulan_ini ?? 0 }};
    new Chart(document.getElementById("pie"), {
        type: "pie",
        data: {
            labels: ["SPP Per Bulan", "Target Bulan", "SD Bulan Ini"],
            datasets: [{
                data: [sppPerBulan, targetBulan, sdBulanIni],
                backgroundColor: pieColors,
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    position: "bottom",
                    labels: {
                        usePointStyle: true,
                        pointStyle: "circle",
                        padding: 5,
                        boxWidth: 12,
                        font: {
                            size: 10
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: c =>
                            `${c.label}: ${c.raw.toLocaleString("id-ID",{style:"currency",currency:"IDR"})}`
                    }
                }
            }
        }
    });
</script>
<script>
    $('#keterangan').val('-').trigger('focus').trigger('blur');

    $(document).ready(function() {
        $('.select2').select2({
            theme: 'bootstrap-5'
        });
        flatpickr('.datepicker', {
            dateFormat: 'Y-m-d'
        });
        $('.nominal').maskMoney({
            thousands: '.',
            decimal: ',',
            precision: 2,
            allowZero: true
        });

        $('#jenis_biaya').on('change', function() {

            const jenis = $(this).val();
            const nama = $('#siswa_nama').val();
            const namaAkun = $('#jenis_biaya option:selected').text();

            $('.SPPsimpan')
                .prop('disabled', false)
                .removeAttr('aria-disabled')
                .each(function () {
                    $(this).html($(this).data('original-html'));
            });

            $('#bulanWrapper').toggle(jenis === '4.1.01.01');
            $('.spp-checkbox').prop('checked', false).prop('disabled', false);
            $('#sppIDContainer').empty();

            $('#nominal').val(0).maskMoney('mask');

            if (jenis === '4.1.01.01') {
                $('#nominal').prop('readonly', true);
                $('#keterangan').val(`${namaAkun} an. ${nama}`);
                $('#kuitansi, #CetakPadaKartu').addClass('d-none');
            } else if (jenis.startsWith('4.1.01.')) {
                $('#nominal').prop('readonly', false).val('');
                $('#kuitansi, #CetakPadaKartu').addClass('d-none');
                $('#keterangan').val(`${namaAkun} an. ${nama}`);
            } else {
                $('#keterangan').val('');
            }
        });

        $('.spp-checkbox').on('change', function() {
            let total = 0;
            $('#sppIDContainer').empty();

            $('.spp-checkbox:checked:not(:disabled)').each(function() {
                const id = $(this).data('id');
                const nominal = parseInt($(this).data('nominal'));

                total += nominal;

                $('#sppIDContainer').append(`
            <input type="hidden" name="spp_id[]" value="${id}">
            <input type="hidden" name="nominal_spp[]" value="${nominal}">
        `);
            });

            $('#nominal').maskMoney('mask', total);
        });

        document.querySelectorAll('.btn-check').forEach(input => {
            const label = document.querySelector(`label[for="${input.id}"]`);
            if (input.checked && !label.querySelector('.check-icon')) {
                label.insertAdjacentHTML('afterbegin', '<span class="check-icon me-0">✓</span>');
            }
            input.addEventListener('change', function() {
                if (this.checked) {
                    if (!label.querySelector('.check-icon')) {
                        label.insertAdjacentHTML('afterbegin',
                            '<span class="check-icon me-0">✓</span>');
                    }
                } else {
                    const icon = label.querySelector('.check-icon');
                    if (icon) icon.remove();
                }
            });
        });

        const $ta = $('textarea.form-control');

        function updateState(el) {
            const g = el.closest('.input-group');
            g.toggleClass('is-filled is-focused', el.val().trim() !== '');
        }
        $ta.each(function() {
            updateState($(this));
        });
        $ta.on('focus input', function() {
            $(this).closest('.input-group').addClass('is-filled is-focused');
        });
        $ta.on('blur', function() {
            updateState($(this));
        });
    });
</script>
<script>
    function setTextareaRows() {
        const textarea = document.getElementById('keterangan');
        if (window.innerWidth < 768) {
            textarea.rows = 4; // HP
        } else {
            textarea.rows = 1; // Desktop
        }
    }

    setTextareaRows();
    window.addEventListener('resize', setTextareaRows);
</script>
