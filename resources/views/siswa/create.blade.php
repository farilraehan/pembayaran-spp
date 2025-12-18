@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 fw-bold">{{ $title }}</h3>
            <p class="mb-4 text-muted">Management System Pembayaran SPP</p>
        </div>
        <form id="FormSiswa" method="POST" action="/app/siswa" class="text-start" enctype="multipart/form-data">
            @csrf
            <div class="col-12">
                <div class="card my-4 shadow-sm">
                    <div class="card-header p-1 position-relative mt-n4 mx-3 bg-white rounded-3">
                        <ul class="nav nav-pills nav-fill p-1 bg-secondary">
                            <li class="nav-item">
                                <a class="nav-link active d-flex align-items-center justify-content-center gap-1 py-2"
                                    data-bs-toggle="tab" href="#tabSiswa">
                                    <i class="material-symbols-rounded fs-5">school</i>
                                    <span>Data Siswa</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center justify-content-center gap-1 py-2"
                                    data-bs-toggle="tab" href="#tabWali">
                                    <i class="material-symbols-rounded fs-5">people</i>
                                    <span>Data Orang tua / Wali</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="tab-pane fade show active p-3" id="tabSiswa">
                            <div class="row">
                                <div class="col-md-2">
                                    <div id="preview-img-box" style="width:150px;height:150px"
                                        class="border bg-light overflow-hidden rounded">
                                        <img id="preview-img" class="w-100 h-100" style="object-fit:cover;">
                                    </div>
                                    <div class="col-md-12 mt-3">
                                        <label for="foto"
                                            class="form-label btn btn-outline-primary text-truncate w-100">
                                            Pilih Foto Siswa
                                        </label>
                                        <input type="file" name="foto" id="foto" class="d-none" accept="image/*">
                                    </div>
                                </div>
                                <div class="col-md-10">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">NIPD</label>
                                                <input type="text" name="nipd" id="nipd" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">NISN</label>
                                                <input type="text" name="nisn" id="nisn" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" id="nama" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir" id="tempat_lahir"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="text" name="tanggal_lahir" id="tanggal_lahir"
                                                    class="form-control datepicker">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                                            class="form-select select2">
                                                            <option value="" disabled selected>Pilih Jenis Kelamin
                                                            </option>
                                                            <option value="L">Laki-laki</option>
                                                            <option value="P">Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label class="form-label">NIK</label>
                                                        <input type="text" name="nik" id="nik"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Kecamatan</label>
                                                <input type="text" name="kecamatan" id="kecamatan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Kelurahan</label>
                                                <input type="text" name="kelurahan" id="kelurahan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Dusun</label>
                                                <input type="text" name="dusun" id="dusun"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label class="form-label">RT</label>
                                                        <input type="number" name="rt" id="rt"
                                                            class="form-control" maxlength="3">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div class="input-group input-group-outline mb-3">
                                                        <label class="form-label">RW</label>
                                                        <input type="number" name="rw" id="rw"
                                                            class="form-control" maxlength="3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-outline mb-3">
                                                <label class="form-label">Alamat Lengkap</label>
                                                <textarea name="alamat" rows="1" id="alamat" rows="3" class="form-control"></textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Kode POS</label>
                                            <input type="text" name="kode_pos" id="kode_pos" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Status Awal</label>
                                            <input type="text" name="status_awal" id="status_awal"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group w-100" role="group" aria-label="Status Siswa">
                                            <input type="radio" class="btn-check" name="status_siswa"
                                                id="status_aktif" value="aktif" autocomplete="off" checked>
                                            <label class="btn btn-outline-primary flex-fill"
                                                for="status_aktif">Aktif</label>
                                            <input type="radio" class="btn-check" name="status_siswa"
                                                id="status_nonaktif" value="nonaktif" autocomplete="off">
                                            <label class="btn btn-outline-primary flex-fill"
                                                for="status_nonaktif">Nonaktif</label>
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="agama" id="agama" class="form-select select2">
                                                <option value="" disabled selected>Pilih Agama</option>
                                                <option value="Islam">Islam</option>
                                                <option value="Kristen Protestan">Kristen Protestan</option>
                                                <option value="Katolik">Katolik</option>
                                                <option value="Hindu">Hindu</option>
                                                <option value="Budha">Budha</option>
                                                <option value="Konghucu">Konghucu</option>
                                                <option value="Kepercayaan">Kepercayaan kepada Tuhan YME</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Keb. Khusus</label>
                                            <input type="text" name="kebutuhan_khusus" id="kebutuhan_khusus"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Jenis Tinggal</label>
                                            <input type="text" name="jenis_tinggal" id="jenis_tinggal"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Transportasi</label>
                                            <input type="text" name="transportasi" id="transportasi"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">No Handphone</label>
                                            <input type="text" name="hp" id="hp" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Tanggal Masuk</label>
                                            <input type="text" name="tanggal_masuk" id="tanggal_masuk"
                                                class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Angkatan</label>
                                            <input type="text" name="angkatan" id="angkatan" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="email" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">SKHUN</label>
                                            <input type="text" name="skhun" id="skhun" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="tahun_akademik" id="tahun_akademik"
                                                class="form-select select2">
                                                <option value="" disabled selected>Tahun Ajaran</option>
                                                @foreach ($tahunAkademmik as $tA)
                                                    <option value="{{ $tA->nama_tahun }}">
                                                        {{ $tA->nama_tahun }} -
                                                        {{ $tA->keterangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="kelas" id="kelas" class="form-select select2">
                                                <option value="" disabled selected>Pilih Kelas</option>
                                                @foreach ($kelas as $kls)
                                                    <option value="{{ $kls->kode_kelas }}-{{ $kls->tingkat }}">
                                                        {{ $kls->kode_kelas }} -
                                                        {{ $kls->nama_kelas }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="jurusan" id="jurusan" class="form-select select2">
                                                <option value="" disabled selected>Pilih Jurusan</option>
                                                @foreach ($jurusan as $J)
                                                    <option value="{{ $J->kode_jurusan }}">
                                                        {{ $J->kode_jurusan }} -
                                                        {{ $J->nama }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="ruangan" id="ruangan" class="form-select select2">
                                                <option value="" disabled selected>Pilih Ruangan</option>
                                                @foreach ($ruang as $R)
                                                    <option value="{{ $R->kode_ruangan }}">
                                                        {{ $R->kode_ruangan }} -
                                                        {{ $R->nama_ruangan }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Penerima KPS</label>
                                            <input type="text" name="penerima_kps" id="penerima_kps"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" id="password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="input-group input-group-outline mb-3">
                                            <label class="form-label">No KPS</label>
                                            <input type="text" name="no_kps" id="no_kps" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('alokasi_spp', $jenisBiaya->total_beban) ? 'is-filled' : '' }}">
                                            <label class="form-label">Alokasi SPP per Bulan</label>
                                            <input type="text" name="alokasi_spp"
                                                value="{{ number_format($jenisBiaya->total_beban, 2) }}" id="alokasi_spp"
                                                class="form-control nominal">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade p-3" id="tabWali">
                            <h6 class="text-dark mb-3">Data Ayah</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Nama Ayah</label>
                                        <input type="text" name="nama_ayah" id="nama_ayah" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_ayah" id="tahun_lahir_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_ayah" id="pendidikan_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ayah" id="pekerjaan_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_ayah" id="penghasilan_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_ayah" id="kebutuhan_khusus_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_ayah" id="no_telp_ayah"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-dark mt-4 mb-3">Data Ibu</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Nama Ibu</label>
                                        <input type="text" name="nama_ibu" id="nama_ibu" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_ibu" id="tahun_lahir_ibu"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_ibu" id="pendidikan_ibu"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ibu" id="pekerjaan_ibu"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_ibu" id="penghasilan_ibu"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_ibu" id="kebutuhan_khusus_ibu"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_ibu" id="no_telp_ibu" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <h6 class="text-dark mt-4 mb-3">Data Wali</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Nama Wali</label>
                                        <input type="text" name="nama_wali" id="nama_wali" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_wali" id="tahun_lahir_wali"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_wali" id="pendidikan_wali"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_wali" id="pekerjaan_wali"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_wali" id="penghasilan_wali"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_wali" id="kebutuhan_khusus_wali"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_wali" id="no_telp_wali"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-12 mb-1">
                <div class="card my-4 shadow-sm mb-1">
                    <div class="card-body d-flex justify-content-between align-items-center p-2 pb-1">
                        <span class="fw-bold" style="font-size: 14px;">
                            Silakan isi semua data. Jika ada yang kosong, isi dengan 0 atau -
                        </span>
                        <button type="submit" class="btn btn-info p-2 mb-1" id="simpan">
                            Simpan data Siswa
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
@section('script')
    <script>
        var fotoInput = document.getElementById('foto');
        var labelFoto = document.querySelector('label[for="foto"]');
        var previewImg = document.getElementById('preview-img');

        fotoInput.addEventListener('change', function() {
            if (this.files.length > 0) {
                var file = this.files[0];
                var valid = ['image/jpeg', 'image/jpg', 'image/png'];

                if (!valid.includes(file.type)) {
                    this.value = '';
                    labelFoto.textContent = 'Pilih Foto Siswa';

                    previewImg.src = "";

                    Swal.fire({
                        toast: true,
                        position: 'top-end',
                        icon: 'error',
                        title: 'Hanya boleh upload JPG, JPEG, PNG!',
                        showConfirmButton: false,
                        timer: 3000
                    });
                } else {
                    labelFoto.textContent = file.name;

                    previewImg.src = URL.createObjectURL(file);
                }
            }
        });

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

        $(".nominal").maskMoney({
            allowNegative: true
        });

        $(document).on('click', '#simpan', function(e) {
            e.preventDefault();
            var form = $('#FormSiswa')[0];
            var actionUrl = $('#FormSiswa').attr('action');
            var formData = new FormData(form);
            $.ajax({
                type: 'POST',
                url: actionUrl,
                data: formData,
                contentType: false,
                processData: false,
                success: function(result) {
                    if (result.success) {
                        Swal.fire({
                            title: result.msg,
                            text: "Simpan Data Siswa?",
                            icon: "success",
                            showDenyButton: true,
                            confirmButtonText: "Lanjutkan",
                            denyButtonText: `Tidak`
                        }).then((res) => {
                            if (res.isConfirmed) {
                                window.location.reload();
                            } else if (res.isDenied) {
                                window.location.href = '/app/siswa';
                            }
                        });
                    }
                },
                error: function(result) {
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                }
            });
        });
    </script>
@endsection
