@extends('layouts.base')
@section('content')
    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 fw-bold">{{ $title }}</h3>
            <p class="mb-4 text-muted">Management System Pembayaran SPP</p>
        </div>
        <div class="col-12">
            <div class="card my-4 shadow-sm">
                <div class="card-header p-1  position-relative mt-n4 mx-3 bg-white rounded-3">
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
                            <div class="col-lg-4 col-md-6">
                                <div class="card h-100">
                                    <div class="card-header pb-0">
                                        <h6>Profil</h6>
                                    </div>
                                    <div class="card-body p-3 text-center">
                                        <div class="mx-auto mb-2"
                                            style="width:120px; height:120px; border-radius:50%; overflow:hidden; background-color:#f1f1f1;">
                                            <img src="{{ asset('storage/siswa/' . ($siswa->foto ?: 'default.png')) }}"
                                                style="width:100%; height:100%; object-fit:cover;">
                                        </div>
                                        <div class="d-flex justify-content-end mb-2">
                                            <span
                                                class="badge 
                                                {{ $siswa->status_siswa == 'aktif' ? 'bg-success' : 'bg-danger' }}">
                                                {{ $siswa->status_siswa }}
                                            </span>
                                        </div>
                                        <h6 class="mt-1 mb-0 fw-bold text-dark">{{ $siswa->nama }}</h6>
                                        <p class="text-secondary text-sm mb-0">NISN : {{ $siswa->nisn }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-8 col-md-6">
                                <div class="card h-100">
                                    <div class="card-header pb-0">
                                        <h6>Detail Data Siswa</h6>
                                    </div>
                                    <div class="card-body p-3">
                                        <h6 class="text-dark mb-3">
                                            <i class="material-symbols-rounded text-success me-1">person</i>
                                            Data Pribadi
                                        </h6>
                                        <div class="row mb-4">
                                            <div class="col-md-6 border-end">
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Tempat Lahir:</strong>
                                                        <span>{{ $siswa->tempat_lahir }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Tanggal Lahir:</strong>
                                                        <span>
                                                            {{ \Carbon\Carbon::parse($siswa->tanggal_lahir)->format('d F Y') }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Jenis Kelamin:</strong>
                                                        <span>{{ $siswa->jenis_kelamin == '1' ? 'Laki-laki' : 'Perempuan' }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Agama:</strong>
                                                        <span>{{ $siswa->agama == '1' ? 'Islam' : '-' }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <h6 class="text-dark mb-3">
                                            <i class="material-symbols-rounded text-primary me-1">badge</i>
                                            Identitas Dasar
                                        </h6>
                                        <div class="row mb-4">
                                            <div class="col-md-6 border-end">
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Password:</strong>
                                                        <span>{{ $siswa->password }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Kelas:</strong>
                                                        <span>{{ $siswa->kode_kelas }}</span>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="col-md-6">
                                                <ul class="list-group">
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Angkatan:</strong>
                                                        <span>{{ $siswa->angkatan }}</span>
                                                    </li>
                                                    <li
                                                        class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                        <strong>Status Siswa:</strong>
                                                        <span>{{ $siswa->status_siswa }}
                                                        </span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="col-lg-12">
                                <div class="card h-100 p-3">
                                    <h6 class="text-dark mb-3">
                                        <i class="material-symbols-rounded text-primary me-1">location_on</i>
                                        Alamat Lengkap
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 border-end">
                                            <ul class="list-group mb-4">
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Alamat:</strong>
                                                    <span>{{ $siswa->alamat }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>RT/RW:</strong>
                                                    <span>{{ $siswa->rt }}/{{ $siswa->rw }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Dusun:</strong>
                                                    <span>{{ $siswa->dusun }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="list-group mb-4">
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Kelurahan:</strong>
                                                    <span>{{ $siswa->kelurahan }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Kecamatan:</strong>
                                                    <span>{{ $siswa->kecamatan }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Kode Pos:</strong>
                                                    <span>{{ $siswa->kode_pos }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                    <h6 class="text-dark mb-3 mt-4">
                                        <i class="material-symbols-rounded text-warning me-1">info</i>
                                        Lain-lain
                                    </h6>
                                    <div class="row">
                                        <div class="col-md-6 border-end">
                                            <ul class="list-group mb-4">
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Status Awal:</strong>
                                                    <span>{{ $siswa->status_awal }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Status Siswa:</strong>
                                                    <span>{{ $siswa->status_siswa }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>NIK:</strong>
                                                    <span>{{ $siswa->nik }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Kebutuhan Khusus:</strong>
                                                    <span>{{ $siswa->kebutuhan_khusus }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Jenis Tinggal:</strong>
                                                    <span>{{ $siswa->jenis_tinggal }}</span>
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="col-md-6">
                                            <ul class="list-group mb-4">
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Transportasi:</strong>
                                                    <span>{{ $siswa->alat_transportasi }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>No Telpon:</strong>
                                                    <span>{{ $siswa->telepon }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>No HP:</strong>
                                                    <span>{{ $siswa->hp }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Email:</strong>
                                                    <span>{{ $siswa->email }}</span>
                                                </li>
                                                <li
                                                    class="list-group-item border-0 p-1 text-sm d-flex justify-content-between">
                                                    <strong>Penerima KPS:</strong>
                                                    <span>{{ $siswa->penerima_kps }}</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade p-3" id="tabWali">
                        <h6 class="text-dark mb-3">
                            <i class="material-symbols-rounded text-info me-1">family_restroom</i>
                            Data Orang Tua / Wali
                        </h6>
                        <div class="row g-4">
                            <div class="col-lg-4">
                                <div class="card shadow-sm p-3" style="background:#f7faff;">
                                    <h6 class="mb-4 text-primary">
                                        <i class="material-symbols-rounded me-1 text-primary">man</i> Ayah
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Nama</div>
                                        <div class="col-7">: {{ $siswa->nama_ayah ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Thn Lahir</div>
                                        <div class="col-7">: {{ $siswa->tahun_lahir_ayah ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pendidikan</div>
                                        <div class="col-7">: {{ $siswa->pendidikan_ayah ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pekerjaan</div>
                                        <div class="col-7">: {{ $siswa->pekerjaan_ayah ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Penghasilan</div>
                                        <div class="col-7">: {{ $siswa->penghasilan_ayah ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">No Telp</div>
                                        <div class="col-7">: {{ $siswa->no_telepon_ayah ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow-sm p-3" style="background:#f8fff7;">
                                    <h6 class="mb-4 text-success">
                                        <i class="material-symbols-rounded me-1 text-success">woman</i> Ibu
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Nama</div>
                                        <div class="col-7">: {{ $siswa->nama_ibu ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Thn Lahir</div>
                                        <div class="col-7">: {{ $siswa->tahun_lahir_ibu ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pendidikan</div>
                                        <div class="col-7">: {{ $siswa->pendidikan_ibu ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pekerjaan</div>
                                        <div class="col-7">: {{ $siswa->pekerjaan_ibu ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Penghasilan</div>
                                        <div class="col-7">: {{ $siswa->penghasilan_ibu ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">No Telp</div>
                                        <div class="col-7">: {{ $siswa->no_telepon_ibu ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="card shadow-sm p-3" style="background:#fffaf5;">
                                    <h6 class="mb-4 text-warning">
                                        <i class="material-symbols-rounded me-1 text-warning">groups</i> Wali
                                    </h6>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Nama</div>
                                        <div class="col-7">: {{ $siswa->nama_wali ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Thn Lahir</div>
                                        <div class="col-7">: {{ $siswa->tahun_lahir_wali ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pendidikan</div>
                                        <div class="col-7">: {{ $siswa->pendidikan_wali ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Pekerjaan</div>
                                        <div class="col-7">: {{ $siswa->pekerjaan_wali ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">Penghasilan</div>
                                        <div class="col-7">: {{ $siswa->penghasilan_wali ?: '-' }}</div>
                                    </div>
                                    <div class="row mb-2">
                                        <div class="col-5 fw-bold">No Telp</div>
                                        <div class="col-7">: {{ $siswa->no_telepon_wali ?: '-' }}</div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-12 mb-1">
            <div class="card my-4 shadow-sm mb-1">
                <div class="card-body d-flex align-items-center p-2 pb-1">
                    <a href="{{ url()->previous() }}" class="kembali btn btn-secondary p-2 mb-1 ms-auto">
                        Kembali ke halaman siswa
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
