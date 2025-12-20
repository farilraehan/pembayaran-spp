@extends('layouts.base')
@section('content')
<div class="row">
    <div class="ms-3">
        <h3 class="mb-0 h4 font-weight-bolder">{{ $title }}</h3>
        <p class="mb-4">Management System Pembayaran SPP</p>
    </div>
    <div class="col-md-8 mt-3">
        <div class="card">
            <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                <div
                    class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                    <h6 class="text-white text-capitalize ps-3" id="headerTitle">
                        Pencatatan Transaksi Keuangan (Jurnal Umum)
                    </h6>
                </div>
            </div>
            <div class="card-body">
                <form action="/app/Transaksi" method="post" id="FormTransaksi">
                    @csrf
                    <input type="hidden" name="transaksi" id="transaksi" value="jurnal_umum">
                    <div class="row">
                        <div class="col-md-6">
                            <label for="tanggal">Tanggal Transaksi</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control" id="tanggal" name="tanggal"
                                    value="{{ date('Y-m-d') }}">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="jenis_transaksi">Jenis Transaksi</label>
                            <div class="input-group input-group-outline mb-3">
                                <select id="jenis_transaksi" name="jenis_transaksi" class="form-control select2">
                                    <option value="">-- Pilih Jenis Transaksi --</option>
                                    @foreach ($jenisTransaksi as $jt)
                                    <option value="{{ $jt->id }}">{{ $jt->nama }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <label for="sumber_dana">Sumber Dana</label>
                            <div class="input-group input-group-outline mb-3">
                                <select id="sumber_dana" name="sumber_dana" class="form-control select2">
                                    <option value="">-- Pilih Sumber Dana --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="disimpan_ke">Disimpan Ke</label>
                            <div class="input-group input-group-outline mb-3">
                                <select id="disimpan_ke" name="disimpan_ke" class="form-control select2">
                                    <option value="">-- Pilih Tujuan --</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="row" id="form-jurnal-umum">
                        <div class="col-md-12">
                            <label for="keterangan_transaksi">Keterangan</label>
                            <div class="input-group input-group-outline mb-3">
                                <textarea class="form-control" rows="1" id="keterangan_transaksi"
                                    name="jurnal_umum[keterangan]" rows="3"></textarea>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label for="nominal">Nominal</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control nominal" id="nominal" name="jurnal_umum[nominal]"
                                    value="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="form-beli-inventaris" style="display:none">
                        <input type="hidden" name="beli_inventaris[jenis_inventaris]" id="jenis_inventaris">
                        <input type="hidden" name="beli_inventaris[kategori_inventaris]" id="kategori_inventaris">
                        <div class="col-md-12">
                            <label>Nama Barang</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control" name="beli_inventaris[nama_barang]">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Harga Satuan</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control nominal" name="beli_inventaris[harga_satuan]"
                                    value="0.00">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Umur Ekonomis (bulan)</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="number" class="form-control" name="beli_inventaris[umur_ekonomis]"
                                    value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Jumlah Unit</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="number" class="form-control" name="beli_inventaris[jumlah_unit]" value="0">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Harga Perolehan</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control nominal" name="beli_inventaris[harga_perolehan]"
                                    value="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="row" id="form-hapus-inventaris" style="display:none">
                        <div class="col-md-12">
                            <label>Daftar Barang</label>
                            <div class="input-group input-group-outline mb-3">
                                <select id="daftar_barang" name="hapus_inventaris[daftar_barang]"
                                    class="form-control select2">
                                    <option value="">-- Pilih Barang --</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Alasan</label>
                            <div class="input-group input-group-outline mb-3">
                                <select id="alasan" name="hapus_inventaris[alasan]" class="form-control select2">
                                    <option value="">-- Pilih Alasan --</option>
                                    <option value="jual">Jual</option>
                                    <option value="hapus">Hapus</option>
                                    <option value="hilang">Hilang</option>
                                    <option value="revaluasi">Revaluasi</option>
                                    <option value="rusak">Rusak</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <label>Jumlah Unit</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="number" class="form-control"
                                    name="hapus_inventaris[jumlah_unit_inventaris]" value="0" min="0">
                            </div>
                        </div>
                        <div class="col-md-12">
                            <label>Nilai Buku</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control nominal" name="hapus_inventaris[nilai_buku]"
                                    value="0.00" readonly>
                            </div>
                        </div>
                        <div class="col-md-6" id="input-harga-jual" style="display:none">
                            <label>Harga Jual</label>
                            <div class="input-group input-group-outline mb-3">
                                <input type="text" class="form-control nominal" name="hapus_inventaris[harga_jual]"
                                    value="0.00">
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-info">Simpan Transaksi</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4 mt-3">
        <div class="card h-100">
            <div class="card-body pt-4 p-3">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                    Saldo</span>
                </h6>
                <hr class="horizontal dark my-2">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3 text-center">
                    Cetak Buku Bantu</span>
                </h6>
                <hr class="horizontal dark my-2">
                <div class="row">
                    <div class="col-md-12">
                        <label>Tahunan</label>
                        <div class="input-group input-group-outline mb-3">
                            <select class="form-control select2" id="filter-tahunan">
                                <option value="">Pilih Tahun</option>
                                <option value="2025">2025</option>
                                <option value="2026">2026</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>Bulanan</label>
                        <div class="input-group input-group-outline mb-3">
                            <select class="form-control select2" id="filter-bulanan">
                                <option value="">Pilih Bulan</option>
                                <option value="1">1.Januari</option>
                                <option value="2">2.Februari</option>
                                <option value="3">3.Maret</option>
                                <option value="4">4.April</option>
                                <option value="5">5.Mei</option>
                                <option value="6">6.Juni</option>
                                <option value="7">7.Juli</option>
                                <option value="8">8.Agustus</option>
                                <option value="9">9.September</option>
                                <option value="10">10.Oktober</option>
                                <option value="11">11.November</option>
                                <option value="12">12.Desember</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-12">
                        <label>Harian</label>
                        <div class="input-group input-group-outline mb-3">
                            <select class="form-control select2" id="filter-harian">
                                <option value="">Pilih Tanggal</option>
                                <option value="1">01</option>
                                <option value="2">02</option>
                                <option value="3">03</option>
                                <option value="4">04</option>
                                <option value="5">05</option>
                                <option value="6">06</option>
                                <option value="7">07</option>
                                <option value="8">08</option>
                                <option value="9">09</option>
                                <option value="10">10</option>
                                <option value="11">11</option>
                                <option value="12">12</option>
                                <option value="13">13</option>
                                <option value="14">14</option>
                                <option value="15">15</option>
                                <option value="16">16</option>
                                <option value="17">17</option>
                                <option value="18">18</option>
                                <option value="19">19</option>
                                <option value="20">20</option>
                                <option value="21">21</option>
                                <option value="22">22</option>
                                <option value="23">23</option>
                                <option value="24">24</option>
                                <option value="25">25</option>
                                <option value="26">26</option>
                                <option value="27">27</option>
                                <option value="28">28</option>
                                <option value="29">29</option>
                                <option value="30">30</option>
                                <option value="31">31</option>
                            </select>
                        </div>
                    </div>
                </div>
                    <div class="d-flex justify-content-end mt-4 mb-1">
                        <button type="submit" class="btn btn-primary">Detail Transaksi</button>
                    </div>
            </div>
        </div>
    </div>
    <div class="col-md-12 mt-3 d-none" id="riwayat-transaksi">
        <div class="card h-100">
            <div class="card-body pt-4 p-3">
                <h6 class="text-uppercase text-body text-xs font-weight-bolder mb-3">
                    Riwayat Transaksi</span>
                </h6>
                <ul class="list-group mb-3" id="list-riwayat"></ul>
            </div>
        </div>
    </div>
</div>
@endsection
@section('script')
<script>
const REKENING = @json($rekening);
const INVENTARIS = { id: 0, jumlah: 0, nilai_buku: 0 };

function numberUnformat(val) {
    if (!val) return 0;
    return parseFloat(val.toString().replace(/[^0-9-]/g, '')) || 0;
}

function numberFormat(val) {
    val = Number(val) || 0;
    return val.toLocaleString('id-ID');
}

$(document).ready(function () {
    $('.select2').select2({ theme: 'bootstrap-5', allowClear: false });
    $('#tanggal').flatpickr();
    $('.nominal').maskMoney({ allowNegative: true });
});

$(document).on('change', '#tanggal', function () {
    ambilDaftarInventaris($(this).val());
});

$(document).on('change', '#jenis_transaksi', function () {
    var jenis_transaksi = $(this).val();
    var sumber_dana = [];
    var disimpan_ke = [];
    var label_sumber_dana = 'Sumber Dana';
    var label_disimpan_ke = 'Disimpan Ke';

    if (jenis_transaksi == '1') {
        sumber_dana = REKENING.filter(item =>
            (item.lev1 == '2' || item.lev1 == '3' || item.lev1 == '4') &&
            !['2.1.04.01','2.1.04.02','2.1.04.03','2.1.02.01','2.1.03.01'].includes(item.kode_akun) &&
            !item.kode_akun.startsWith('4.1.01')
        ).map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));

        disimpan_ke = REKENING.filter(item => item.lev1 == '1')
            .map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));
    }

    if (jenis_transaksi == '2') {
        sumber_dana = REKENING.filter(item =>
            (item.lev1 == '1' || item.lev1 == '2') &&
            !item.kode_akun.startsWith('2.1.04')
        ).map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));

        disimpan_ke = REKENING.filter(item =>
            item.lev1 == '2' || item.lev1 == '3' || item.lev1 == '5'
        ).map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));

        label_disimpan_ke = 'Keperluan';
    }

    if (jenis_transaksi == '3') {
        sumber_dana = REKENING.map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));
        disimpan_ke = REKENING.map(item => ({ id: item.id, text: item.kode_akun + '. ' + item.nama_akun }));
    }

    setFormSelect2('#sumber_dana', sumber_dana);
    setFormSelect2('#disimpan_ke', disimpan_ke);

    $('label[for="sumber_dana"]').text(label_sumber_dana);
    $('label[for="disimpan_ke"]').text(label_disimpan_ke);
});

$(document).on('change', '#sumber_dana, #disimpan_ke', function () {
    var jenis_transaksi = $('#jenis_transaksi').val();
    var sumber_dana = $('#sumber_dana').val();
    var disimpan_ke = $('#disimpan_ke').val();

    var sd = REKENING.find(i => i.id == sumber_dana);
    var dk = REKENING.find(i => i.id == disimpan_ke);

    var keterangan = '';

    if (sd) {
        if (jenis_transaksi == '1') {
            keterangan = 'Dari ' + sd.nama_akun;
            if (dk) keterangan += ' ke ' + dk.nama_akun;
        }
        if (jenis_transaksi == '2') {
            if (sd.kode_akun.startsWith('1.1.01')) keterangan = 'Bayar ';
            if (sd.kode_akun.startsWith('1.1.02')) keterangan = 'Transfer ';
            if (dk) keterangan += dk.nama_akun;
        }
        if (jenis_transaksi == '3') {
            keterangan = 'Pemindahan Saldo ' + sd.nama_akun;
            if (dk) keterangan += ' ke ' + dk.nama_akun;
        }
    }

    $('#keterangan_transaksi').val(keterangan);

    if (sd && dk) handleFormTransaksi(sd, dk, jenis_transaksi);
});

$(document).on('submit', '#FormTransaksi', function (e) {
    e.preventDefault();

    $.ajax({
        type: 'POST',
        url: $(this).attr('action'),
        data: $(this).serialize(),
        success: function (r) {
            if (!r.success) return;

            Swal.fire('Berhasil!', r.message, 'success');

            var sd = REKENING.find(i => i.id == $('#sumber_dana').val());
            var dk = REKENING.find(i => i.id == $('#disimpan_ke').val());

            var nominal = numberUnformat($('#nominal').val());
            var keterangan = $('#keterangan_transaksi').val();

            var icon = 'swap_horiz';
            var color = 'info';
            var sign = '';

            if (sd && dk) {
                if (sd.lev1 == '1' && dk.lev1 != '1') {
                    icon = 'south';
                    color = 'danger';
                    sign = '-';
                }
                if (sd.lev1 != '1' && dk.lev1 == '1') {
                    icon = 'north';
                    color = 'success';
                    sign = '+';
                }
            }

            $('#riwayat-transaksi').removeClass('d-none');

            $('#list-riwayat').prepend(`
                <li class="list-group-item border-0 d-flex justify-content-between ps-0 mb-2 border-radius-lg">
                    <div class="d-flex align-items-center">
                        <button class="btn btn-icon-only btn-rounded btn-outline-${color} me-3 p-3 btn-sm d-flex align-items-center justify-content-center">
                            <i class="material-symbols-rounded text-lg">${icon}</i>
                        </button>
                        <div class="d-flex flex-column">
                            <h6 class="mb-1 text-dark text-sm">${keterangan}</h6>
                            <span class="text-xs">${sd.nama_akun} → ${dk.nama_akun}</span>
                        </div>
                    </div>
                    <div class="text-${color} text-sm font-weight-bold">
                        ${sign} Rp ${numberFormat(nominal)}
                    </div>
                </li>
            `);

            $('#FormTransaksi')[0].reset();
            $('.select2').val(null).trigger('change');
        },
        error: function (xhr) {
            Swal.fire('Gagal!', xhr.responseJSON?.error || 'Terjadi kesalahan', 'error');
        }
    });
});

function handleFormTransaksi(sd, dk, jt) {
    if (sd.kode_akun.startsWith('1.2.01') && dk.kode_akun.startsWith('5.3.02.01') && jt == '2') {
        $('#form-jurnal-umum').hide();
        $('#form-beli-inventaris').hide();
        $('#form-hapus-inventaris').show();
        $('#jenis_inventaris').val(sd.kode_akun.startsWith('1.2.03') ? 'atb' : 'ati');
        $('#kategori_inventaris').val(sd.kode_akun.split('.').pop());
        $('#transaksi').val('hapus_inventaris');
        ambilDaftarInventaris($('#tanggal').val());
        return;
    }

    if (dk.kode_akun.startsWith('1.2.01') || dk.kode_akun.startsWith('1.2.03')) {
        $('#form-jurnal-umum').hide();
        $('#form-beli-inventaris').show();
        $('#form-hapus-inventaris').hide();
        $('#jenis_inventaris').val(dk.kode_akun.startsWith('1.2.03') ? 'atb' : 'ati');
        $('#kategori_inventaris').val(dk.kode_akun.split('.').pop());
        $('#transaksi').val('beli_inventaris');
        return;
    }

    $('#form-jurnal-umum').show();
    $('#form-beli-inventaris').hide();
    $('#form-hapus-inventaris').hide();
    $('#transaksi').val('jurnal_umum');
}

function ambilDaftarInventaris(tanggal) {
    if (!$('#jenis_inventaris').val() || !$('#kategori_inventaris').val()) return;

    $.get('/app/transaksi/daftar-inventaris', {
        tanggal: tanggal,
        jenis: $('#jenis_inventaris').val(),
        kategori: $('#kategori_inventaris').val()
    }).done(function (res) {
        setFormSelect2('#daftar_barang', res.map(item => ({
            id: item.id + '#' + item.jumlah + '#' + item.nilai_buku,
            text: item.id + '. ' + item.nama + ' (' + item.jumlah + ' unit x ' +
                numberFormat(item.harga_satuan) + ') | NB. ' + numberFormat(item.nilai_buku)
        })));
    });
}

function setFormSelect2(target, data) {
    var el = $(target);
    el.empty();
    el.append(new Option('Select Value', '', true, true));
    data.forEach(opt => el.append(new Option(opt.text, opt.id, false, false)));
    el.trigger('change');
}
</script>
@endsection
