@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 fw-bold">{{ $title }}</h3>
            <p class="mb-4 text-muted">Management System Pembayaran SPP</p>
        </div>
        <form id="FormSiswa" method="PUT" action="/app/siswa/{{ $siswa->id }}" class="text-start"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')
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
                                        <img id="preview-img"
                                            src="{{ $siswa->foto ? asset('storage/siswa/' . $siswa->foto) : asset('no-img.png') }}"
                                            class="w-100 h-100" style="object-fit:cover;">
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
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('nipd', $siswa->nipd) ? 'is-filled' : '' }}">
                                                <label class="form-label">NIPD</label>
                                                <input type="text" name="nipd" id="nipd"
                                                    value="{{ old('nipd', $siswa->nipd) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('nisn', $siswa->nisn) ? 'is-filled' : '' }}">
                                                <label class="form-label">NISN</label>
                                                <input type="text" name="nisn" id="nisn"
                                                    value="{{ old('nisn', $siswa->nisn) }}" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('nama', $siswa->nama) ? 'is-filled' : '' }}">
                                                <label class="form-label">Nama Lengkap</label>
                                                <input type="text" name="nama" id="nama"
                                                    value="{{ old('nama', $siswa->nama) }}" class="form-control">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('tempat_lahir', $siswa->tempat_lahir) ? 'is-filled' : '' }}">
                                                <label class="form-label">Tempat Lahir</label>
                                                <input type="text" name="tempat_lahir"
                                                    value="{{ old('tempat_lahir', $siswa->tempat_lahir) }}"
                                                    id="tempat_lahir" class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('tanggal_lahir', $siswa->tanggal_lahir) ? 'is-filled' : '' }}">
                                                <label class="form-label">Tanggal Lahir</label>
                                                <input type="text" name="tanggal_lahir"
                                                    value="{{ old('tanggal_lahir', $siswa->tanggal_lahir) }}"
                                                    id="tanggal_lahir" class="form-control datepicker">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="row">
                                                <div class="col-md-5">
                                                    <div
                                                        class="input-group input-group-outline mb-3 {{ old('jenis_kelamin', $siswa->jenis_kelamin) ? 'is-filled' : '' }}">
                                                        <select name="jenis_kelamin" id="jenis_kelamin"
                                                            class="form-select select2">
                                                            <option value="" disabled selected>Pilih Jenis Kelamin
                                                            </option>
                                                            <option value="L"
                                                                {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'L' ? 'selected' : '' }}>
                                                                Laki-laki</option>
                                                            <option value="P"
                                                                {{ old('jenis_kelamin', $siswa->jenis_kelamin) == 'P' ? 'selected' : '' }}>
                                                                Perempuan</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <div class="col-md-7">
                                                    <div
                                                        class="input-group input-group-outline mb-3 {{ old('nik', $siswa->nik) ? 'is-filled' : '' }}">
                                                        <label class="form-label">NIK</label>
                                                        <input type="text" name="nik"
                                                            value="{{ old('nik', $siswa->nik) }}" id="nik"
                                                            class="form-control">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('kecamatan', $siswa->kecamatan) ? 'is-filled' : '' }}">
                                                <label class="form-label">Kecamatan</label>
                                                <input type="text" name="kecamatan"
                                                    value="{{ old('kecamatan', $siswa->kecamatan) }}" id="kecamatan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('kelurahan', $siswa->kelurahan) ? 'is-filled' : '' }}">
                                                <label class="form-label">Kelurahan</label>
                                                <input type="text" name="kelurahan"
                                                    value="{{ old('kelurahan', $siswa->kelurahan) }}" id="kelurahan"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('dusun', $siswa->dusun) ? 'is-filled' : '' }}">
                                                <label class="form-label">Dusun</label>
                                                <input type="text" name="dusun"
                                                    value="{{ old('dusun', $siswa->dusun) }}" id="dusun"
                                                    class="form-control">
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="row">
                                                <div class="col-6">
                                                    <div
                                                        class="input-group input-group-outline mb-3 {{ old('rt', $siswa->rt) ? 'is-filled' : '' }}">
                                                        <label class="form-label">RT</label>
                                                        <input type="number" name="rt"
                                                            value="{{ old('rt', $siswa->rt) }}" id="rt"
                                                            class="form-control" maxlength="3">
                                                    </div>
                                                </div>
                                                <div class="col-6">
                                                    <div
                                                        class="input-group input-group-outline mb-3 {{ old('rw', $siswa->rw) ? 'is-filled' : '' }}">
                                                        <label class="form-label">RW</label>
                                                        <input type="number" name="rw"
                                                            value="{{ old('rw', $siswa->rw) }}" id="rw"
                                                            class="form-control" maxlength="3">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div
                                                class="input-group input-group-outline mb-3 {{ old('alamat', $siswa->alamat) ? 'is-filled' : '' }}">
                                                <label class="form-label">Alamat Lengkap</label>
                                                <textarea name="alamat" id="alamat" rows="1" class="form-control">{{ old('alamat', $siswa->alamat) }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-12">
                                <div class="row">
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('kode_pos', $siswa->kode_pos) ? 'is-filled' : '' }}">
                                            <label class="form-label">Kode POS</label>
                                            <input type="text" name="kode_pos" id="kode_pos"
                                                value="{{ old('kode_pos', $siswa->kode_pos) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('status_awal', $siswa->status_awal) ? 'is-filled' : '' }}">
                                            <label class="form-label">Status Awal</label>
                                            <input type="text" name="status_awal" id="status_awal"
                                                value="{{ old('status_awal', $siswa->status_awal) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="btn-group w-100" role="group" aria-label="Status Siswa">
                                            <input type="radio" class="btn-check" name="status_siswa"
                                                id="status_aktif" value="aktif"
                                                {{ old('status_siswa', $siswa->status_siswa) == 'aktif' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary flex-fill"
                                                for="status_aktif">Aktif</label>
                                            <input type="radio" class="btn-check" name="status_siswa"
                                                id="status_nonaktif" value="nonaktif"
                                                {{ old('status_siswa', $siswa->status_siswa) == 'nonaktif' ? 'checked' : '' }}>
                                            <label class="btn btn-outline-primary flex-fill"
                                                for="status_nonaktif">Nonaktif</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('agama', $siswa->agama) ? 'is-filled' : '' }}">
                                            <select name="agama" id="agama" class="form-select select2">
                                                <option value="" disabled>Pilih Agama</option>
                                                @foreach (['Islam', 'Kristen Protestan', 'Katolik', 'Hindu', 'Budha', 'Konghucu', 'Kepercayaan kepada Tuhan YME'] as $agama)
                                                    <option value="{{ $agama }}"
                                                        {{ old('agama', $siswa->agama) == $agama ? 'selected' : '' }}>
                                                        {{ $agama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('kebutuhan_khusus', $siswa->kebutuhan_khusus) ? 'is-filled' : '' }}">
                                            <label class="form-label">Keb. Khusus</label>
                                            <input type="text" name="kebutuhan_khusus" id="kebutuhan_khusus"
                                                value="{{ old('kebutuhan_khusus', $siswa->kebutuhan_khusus) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('jenis_tinggal', $siswa->jenis_tinggal) ? 'is-filled' : '' }}">
                                            <label class="form-label">Jenis Tinggal</label>
                                            <input type="text" name="jenis_tinggal" id="jenis_tinggal"
                                                value="{{ old('jenis_tinggal', $siswa->jenis_tinggal) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('transportasi', $siswa->alat_transportasi) ? 'is-filled' : '' }}">
                                            <label class="form-label">Transportasi</label>
                                            <input type="text" name="transportasi" id="transportasi"
                                                value="{{ old('transportasi', $siswa->alat_transportasi) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('hp', $siswa->hp) ? 'is-filled' : '' }}">
                                            <label class="form-label">No Handphone</label>
                                            <input type="text" name="hp" id="hp"
                                                value="{{ old('hp', $siswa->hp) }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('tanggal_masuk', $siswa->tanggal_masuk) ? 'is-filled' : '' }}">
                                            <label class="form-label">Tanggal Masuk</label>
                                            <input type="text" name="tanggal_masuk"
                                                value="{{ old('tanggal_masuk', $siswa->tanggal_masuk) }}"
                                                id="tanggal_masuk" class="form-control datepicker">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('angkatan', $siswa->angkatan) ? 'is-filled' : '' }}">
                                            <label class="form-label">Angkatan</label>
                                            <input type="text" name="angkatan" id="angkatan"
                                                value="{{ old('angkatan', $siswa->angkatan) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('email', $siswa->email) ? 'is-filled' : '' }}">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email" id="email"
                                                value="{{ old('email', $siswa->email) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('skhun', $siswa->skhun) ? 'is-filled' : '' }}">
                                            <label class="form-label">SKHUN</label>
                                            <input type="text" name="skhun" id="skhun"
                                                value="{{ old('skhun', $siswa->skhun) }}" class="form-control">
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="kelas" id="kelas" class="form-select select2">
                                                <option value="" disabled>Pilih Kelas</option>
                                                @foreach ($kelas as $kls)
                                                    <option value="{{ $kls->kode_kelas }}-{{ $kls->tingkat }}"
                                                        {{ old('kelas', $siswa->kelas) == $kls->kode_kelas ? 'selected' : '' }}>
                                                        {{ $kls->kode_kelas }} - {{ $kls->nama_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="jurusan" id="jurusan" class="form-select select2">
                                                <option value="" disabled>Pilih Jurusan</option>
                                                @foreach ($jurusan as $J)
                                                    <option value="{{ $J->kode_jurusan }}"
                                                        {{ old('jurusan', $siswa->jurusan) == $J->kode_jurusan ? 'selected' : '' }}>
                                                        {{ $J->kode_jurusan }} - {{ $J->nama }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="input-group input-group-outline mb-3">
                                            <select name="ruangan" id="ruangan" class="form-select select2">
                                                <option value="" disabled>Pilih Ruangan</option>
                                                @foreach ($ruang as $R)
                                                    <option value="{{ $R->kode_ruangan }}"
                                                        {{ old('ruangan', $siswa->ruang) == $R->kode_ruangan ? 'selected' : '' }}>
                                                        {{ $R->kode_ruangan }} - {{ $R->nama_ruangan }}
                                                    </option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('penerima_kps', $siswa->penerima_kps) ? 'is-filled' : '' }}">
                                            <label class="form-label">Penerima KPS</label>
                                            <input type="text" name="penerima_kps" id="penerima_kps"
                                                value="{{ old('penerima_kps', $siswa->penerima_kps) }}"
                                                class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('password', $siswa->password) ? 'is-filled' : '' }}">
                                            <label class="form-label">Password</label>
                                            <input type="password" name="password" id="password"
                                                value="{{ old('password', $siswa->password) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('no_kps', $siswa->no_kps) ? 'is-filled' : '' }}">
                                            <label class="form-label">No KPS</label>
                                            <input type="text" name="no_kps" id="no_kps"
                                                value="{{ old('no_kps', $siswa->no_kps) }}" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div
                                            class="input-group input-group-outline mb-3 {{ old('spp_nominal', $siswa->spp_nominal) ? 'is-filled' : '' }}">
                                            <label class="form-label">Alokasi SPP</label>
                                            <input type="text" name="spp_nominal" id="spp_nominal"
                                                value="{{ old('spp_nominal', number_format($siswa->spp_nominal, 2, ',', '.')) }}"
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
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('nama_ayah', $siswa->nama_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Nama Ayah</label>
                                        <input type="text" name="nama_ayah"
                                            value="{{ old('nama_ayah', $siswa->nama_ayah) }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('tahun_lahir_ayah', $siswa->tahun_lahir_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_ayah"
                                            value="{{ old('tahun_lahir_ayah', $siswa->tahun_lahir_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pendidikan_ayah', $siswa->pendidikan_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_ayah"
                                            value="{{ old('pendidikan_ayah', $siswa->pendidikan_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ayah"
                                            value="{{ old('pekerjaan_ayah', $siswa->pekerjaan_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('penghasilan_ayah', $siswa->penghasilan_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_ayah"
                                            value="{{ old('penghasilan_ayah', $siswa->penghasilan_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('kebutuhan_khusus_ayah', $siswa->kebutuhan_khusus_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_ayah"
                                            value="{{ old('kebutuhan_khusus_ayah', $siswa->kebutuhan_khusus_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('no_telp_ayah', $siswa->no_telepon_ayah) ? 'is-filled' : '' }}">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_ayah"
                                            value="{{ old('no_telp_ayah', $siswa->no_telepon_ayah) }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-dark mt-4 mb-3">Data Ibu</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('nama_ibu', $siswa->nama_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Nama Ibu</label>
                                        <input type="text" name="nama_ibu"
                                            value="{{ old('nama_ibu', $siswa->nama_ibu) }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('tahun_lahir_ibu', $siswa->tahun_lahir_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_ibu"
                                            value="{{ old('tahun_lahir_ibu', $siswa->tahun_lahir_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pendidikan_ibu', $siswa->pendidikan_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_ibu"
                                            value="{{ old('pendidikan_ibu', $siswa->pendidikan_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_ibu"
                                            value="{{ old('pekerjaan_ibu', $siswa->pekerjaan_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('penghasilan_ibu', $siswa->penghasilan_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_ibu"
                                            value="{{ old('penghasilan_ibu', $siswa->penghasilan_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('kebutuhan_khusus_ibu', $siswa->kebutuhan_khusus_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_ibu"
                                            value="{{ old('kebutuhan_khusus_ibu', $siswa->kebutuhan_khusus_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('no_telp_ibu', $siswa->no_telepon_ibu) ? 'is-filled' : '' }}">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_ibu"
                                            value="{{ old('no_telp_ibu', $siswa->no_telepon_ibu) }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>
                            <h6 class="text-dark mt-4 mb-3">Data Wali</h6>
                            <div class="row">
                                <div class="col-md-6">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('nama_wali', $siswa->nama_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Nama Wali</label>
                                        <input type="text" name="nama_wali"
                                            value="{{ old('nama_wali', $siswa->nama_wali) }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('tahun_lahir_wali', $siswa->tahun_lahir_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Tahun Lahir</label>
                                        <input type="text" name="tahun_lahir_wali"
                                            value="{{ old('tahun_lahir_wali', $siswa->tahun_lahir_wali) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pendidikan_wali', $siswa->pendidikan_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pendidikan</label>
                                        <input type="text" name="pendidikan_wali"
                                            value="{{ old('pendidikan_wali', $siswa->pendidikan_wali) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('pekerjaan_wali', $siswa->pekerjaan_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Pekerjaan</label>
                                        <input type="text" name="pekerjaan_wali"
                                            value="{{ old('pekerjaan_wali', $siswa->pekerjaan_wali) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('penghasilan_wali', $siswa->penghasilan_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Penghasilan</label>
                                        <input type="text" name="penghasilan_wali"
                                            value="{{ old('penghasilan_wali', $siswa->penghasilan_wali) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('kebutuhan_khusus_wali', $siswa->kebutuhan_khusus_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">Kebutuhan Khusus</label>
                                        <input type="text" name="kebutuhan_khusus_wali"
                                            value="{{ old('kebutuhan_khusus_wali', $siswa->kebutuhan_khusus_wali) }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div
                                        class="input-group input-group-outline mb-3 {{ old('no_telp_wali', $siswa->no_telepon_wali) ? 'is-filled' : '' }}">
                                        <label class="form-label">No Telepon</label>
                                        <input type="text" name="no_telp_wali"
                                            value="{{ old('no_telp_wali', $siswa->no_telepon_wali) }}"
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
                        <a href="{{ url()->previous() }}" class="btn btn-secondary p-2 mb-1">Kembali</a>
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
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: result.msg ?? "Berhasil update data",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                        setTimeout(() => {
                            let params = new URLSearchParams(window.location.search);
                            let tahun = params.get('tahun_akademik');
                            let kelas = params.get('kelas');
                            window.location.href = `/app/siswa?tahun_akademik=${tahun}&kelas=${kelas}`;
                        }, 1500);
                    } else {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'error',
                            title: result.message ?? "Gagal update data",
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                },
                error: function(xhr) {
                    Swal.fire('Error', 'Cek kembali input yang anda masukkan', 'error');
                    if (xhr.status === 422) {
                        let errors = xhr.responseJSON.errors;
                        $.each(errors, function(key, value) {
                            $('[name="' + key + '"]').addClass('is-invalid');
                        });
                    }
                }
            });
        });
    </script>
@endsection
