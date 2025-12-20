<br>
<div class="row  d-flex align-items-stretch">
    <div class="col-md-8 d-flex">
        <div class="card my-4 flex-fill">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div
                    class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3">
                        Pembayaran SPP - tahun ajaran {{ $siswa->tahun_akademik }}
                    </h6>
                </div>
            </div>

            <div class="card-body">
                <form method="POST" action="/app/transaksi/ProsesPembayaran" id="FormPembayaranSPP">
                    @csrf
                    <input type="hidden" name="siswa_id" value="{{ $siswa->id }}">
                    <input type="hidden" name="siswa_nama" id="siswa_nama" value="{{ $siswa->nama }}">

                    <div class="row mt-3">
                        <div class="col-md-4">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('tanggal', date('Y-m-d')) ? 'is-filled' : '' }}">
                                <label class="form-label">Tanggal</label>
                                <input type="text" name="tanggal" class="form-control datepicker"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select name="jenis_biaya" id="jenis_biaya" class="form-control select2">
                                <option value="0">Pilih Jenis Biaya</option>
                                <option value="1.1.03.01">SPP</option>
                                <option value="1.1.03.02">Daftar Ulang</option>
                            </select>
                        </div>

                        <div class="col-md-4">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('kelas', $siswa->kode_kelas) ? 'is-filled' : '' }}">
                                <label class="form-label">Kelas</label>
                                <input type="text" name="kelas" id="kelas" class="form-control"
                                    value="{{ $siswa->kode_kelas }}" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="bulanWrapper" style="display: none;">
                        <div class="col-12">
                            <label>Bulan Dibayar</label>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap gap-0 mt-1">
                                @foreach ($spp as $item)
                                @php $bulan = \Carbon\Carbon::parse($item->tanggal)->month; @endphp
                                @if ($bulan > 6)
                                <input type="checkbox" name="bulan_dibayar[]" class="btn-check spp-checkbox"
                                    data-id="{{ $item->id }}" data-nominal="{{ $item->nominal }}"
                                    id="tgl_{{ $item->id }}" value="{{ $item->tanggal }}"data-spp_ke="{{ $item->spp_ke }}"
                                    {{ $item->status == 'L' ? 'checked disabled' : '' }}>

                                <label class="btn btn-sm rounded-pill flex-fill text-center
                                    {{ $item->status == 'L' ? 'btn-info' : 'btn-outline-info' }}" for="tgl_{{ $item->id }}">
                                    {{ \App\Utils\Tanggal::NamaBulan($item->tanggal) }}
                                </label>
                                @endif
                                @endforeach
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="d-flex flex-wrap gap-0 mt-1">
                                @foreach ($spp as $item)
                                @php $bulan = \Carbon\Carbon::parse($item->tanggal)->month; @endphp
                                @if ($bulan <= 6) <input type="checkbox" name="bulan_dibayar[]"
                                    class="btn-check spp-checkbox" data-id="{{ $item->id }}"data-spp_ke="{{ $item->spp_ke }}"
                                    data-nominal="{{ $item->nominal }}" id="tgl_{{ $item->id }}"
                                    value="{{ $item->tanggal }}"
                                    {{ $item->status == 'L' ? 'checked disabled' : '' }}>

                                    <label class="btn btn-sm rounded-pill flex-fill text-center
                                        {{ $item->status == 'L' ? 'btn-danger' : 'btn-outline-danger' }}"
                                        for="tgl_{{ $item->id }}">
                                        {{ \App\Utils\Tanggal::NamaBulan($item->tanggal) }}
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
                                <textarea name="keterangan" id="keterangan" rows="1"
                                    class="form-control">{{ old('keterangan') }}</textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-2">
                        <div class="col-md-12">
                            <div
                                class="input-group input-group-outline mb-3 {{ old('nominal', $siswa->spp_nominal) ? 'is-filled' : '' }}">
                                <label class="form-label">Nominal</label>
                                <input type="text" name="nominal" id="nominal" class="form-control nominal" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        <button type="submit" id="SPPsimpan" class="btn btn-secondary">Proses Pembayaran</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4 d-flex">
        <div class="card my-4 flex-fill">
            <div class="card-header bg-gradient-white text-dark">
                <h6 class="mb-0 text-bold">Penjabaran SPP</h6>
            </div>
            <hr class="horizontal dark my-1">
            <div class="card-body text-center pt-0">
                <canvas id="pie" height="270"></canvas>
            </div>
        </div>
    </div>
</div>
<script>
const pieColors=["#ff6384","#36a2eb","#ffcd56"];
const sppPerBulan={{ $spp_perbulan ?? 0 }};
const targetBulan={{ $target_bulan ?? 0 }};
const sdBulanIni={{ $sd_bulan_ini ?? 0 }};
new Chart(document.getElementById("pie"),{
    type:"pie",
    data:{
        labels:["SPP Per Bulan","Target Bulan","SD Bulan Ini"],
        datasets:[{data:[sppPerBulan,targetBulan,sdBulanIni],backgroundColor:pieColors,borderWidth:1}]
    },
    options:{
        responsive:true,
        maintainAspectRatio:false,
        plugins:{
            legend:{display:true,position:"bottom",labels:{usePointStyle:true,pointStyle:"circle",padding:5,boxWidth:12,font:{size:10}}},
            tooltip:{callbacks:{label:c=>`${c.label}: ${c.raw.toLocaleString("id-ID",{style:"currency",currency:"IDR"})}`}}
        }
    }
});
</script>

<script>
$('#keterangan').val('-').trigger('focus').trigger('blur');

$(document).ready(function(){
    $('.select2').select2({theme:'bootstrap-5'});
    flatpickr('.datepicker',{dateFormat:'Y-m-d'});
    $('.nominal').maskMoney({thousands:'.',decimal:',',precision:2,allowZero:true});

    $('#jenis_biaya').on('change',function(){
        const jenis=$(this).val();
        const nama=$('#siswa_nama').val();
        $('#bulanWrapper').toggle(jenis==='1.1.03.01');
        $('.spp-checkbox').prop('checked',false).prop('disabled',false);
        $('#sppIDContainer').empty();
        $('#nominal').val(0).maskMoney('mask');
        if(jenis==='1.1.03.01'){
            $('#nominal').prop('readonly',true);
            $('#keterangan').val('Pembayaran SPP an. '+nama);
        }else if(jenis==='1.1.03.02'){
            $('#nominal').prop('readonly',false).val('');
            $('#keterangan').val('Pembayaran Daftar Ulang an. '+nama+' - kelas '+$('#kelas').val());
        }else{
            $('#keterangan').val('');
        }
    });

    $('.spp-checkbox').on('change', function () {
    let total = 0;
    $('#sppIDContainer').empty();

    $('.spp-checkbox:checked:not(:disabled)').each(function () {
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

    document.querySelectorAll('.btn-check').forEach(input=>{
        const label=document.querySelector(`label[for="${input.id}"]`);
        if(input.checked&&!label.querySelector('.check-icon')){
            label.insertAdjacentHTML('afterbegin','<span class="check-icon me-0">✓</span>');
        }
        input.addEventListener('change',function(){
            if(this.checked){
                if(!label.querySelector('.check-icon')){
                    label.insertAdjacentHTML('afterbegin','<span class="check-icon me-0">✓</span>');
                }
            }else{
                const icon=label.querySelector('.check-icon');
                if(icon)icon.remove();
            }
        });
    });

    const $ta=$('textarea.form-control');
    function updateState(el){
        const g=el.closest('.input-group');
        g.toggleClass('is-filled is-focused',el.val().trim()!=='');
    }
    $ta.each(function(){updateState($(this));});
    $ta.on('focus input',function(){
        $(this).closest('.input-group').addClass('is-filled is-focused');
    });
    $ta.on('blur',function(){updateState($(this));});
});
</script>
