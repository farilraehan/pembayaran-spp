@extends('layouts.base')
@section('content')
<style>
    .avatar-upload {
        cursor: pointer
    }

    .avatar-overlay {
        position: absolute;
        inset: 0;
        background: rgba(0, 0, 0, .45);
        display: flex;
        align-items: center;
        justify-content: center;
        color: #fff;
        font-size: 28px;
        opacity: 0;
        transition: .3s;
        border-radius: .75rem
    }

    .avatar-upload:hover .avatar-overlay {
        opacity: 1
    }

</style>

<div class="container-fluid px-2 px-md-4">
    <form action="/app/profile/update/{{ $user->id }}" method="POST" id="FormProfil" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <div class="card card-body mx-2 mx-md-2 mt-3">
            <div class="row gx-4 mb-2">
                <div class="col-auto">
                    <label class="avatar avatar-xl position-relative avatar-upload">
                        <input type="file" name="photo" accept="image/*" hidden onchange="previewAvatar(this)">
                        <img src="{{ asset('storage/users/' . $user->foto) }}" id="avatarPreview"
                            class="w-100 border-radius-lg shadow-sm">
                        <div class="avatar-overlay">
                            <i class="material-symbols-rounded">photo_camera</i>
                        </div>
                    </label>
                </div>
                <div class="col-auto my-auto">
                    <div class="h-100">
                        <h5 class="mb-1">{{ $user->nama }}</h5>
                        <p class="mb-0 font-weight-normal text-sm">{{ $user->jabatan }}</p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6 my-sm-auto ms-sm-auto me-sm-0 mx-auto mt-3">
                    <div class="nav-wrapper position-relative end-0">
                        <ul class="nav nav-pills nav-fill p-1" role="tablist">
                            <li class="nav-item">
                                <a class="nav-link active" data-bs-toggle="tab" href="#home" role="tab">
                                    <i class="material-symbols-rounded text-lg position-relative">home</i>
                                    <span class="ms-1">Home</span>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" data-bs-toggle="tab" href="#access" role="tab">
                                    <i class="material-symbols-rounded text-lg position-relative">email</i>
                                    <span class="ms-1">Access</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="tab-content mt-3">
                <div class="tab-pane fade show active" id="home">
                    <div class="card card-plain h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Nama</label>
                                        <input type="text" name="nama" value="{{ $user->nama }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Nik</label>
                                        <input type="text" name="nik" value="{{ $user->nik }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Jabatan</label>
                                        <input type="text" name="jabatan" value="{{ $user->jabatan }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 ">
                                        <label class="form-label">Update Jenis Kelamin</label>
                                        <select name="jenis_kelamin" class="form-select select2">
                                            <option value="L" {{ $user->jenis_kelamin=='L'?'selected':'' }}>Laki-laki
                                            </option>
                                            <option value="P" {{ $user->jenis_kelamin=='P'?'selected':'' }}>Perempuan
                                            </option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Email</label>
                                        <input type="text" name="email" value="{{ $user->email }}" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Nomor Telepon</label>
                                        <input type="text" name="telepon" value="{{ $user->telepon }}"
                                            class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Alamat Lengkap</label>
                                        <textarea name="alamat" rows="1"
                                            class="form-control">{{ $user->alamat }}</textarea>
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button class="btn btn-info" type="button" data-type="home">Update Data</button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="tab-pane fade" id="access">
                    <div class="card card-plain h-100">
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3 is-filled">
                                        <label class="form-label">Update Username</label>
                                        <input type="text" name="username" value="{{ $user->username }}"
                                            class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Update Password</label>
                                        <input type="password" name="password" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="input-group input-group-outline mb-3">
                                        <label class="form-label">Konfirmasi Password</label>
                                        <input type="password" name="password_confirmation" class="form-control">
                                    </div>
                                </div>
                            </div>

                            <div class="text-end mt-3">
                                <button class="btn btn-secondary" type="button" data-type="access">Update Data</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
    </form>
</div>
@endsection

@section('script')
<script>
    function previewAvatar(i) {
        if (i.files && i.files[0]) {
            const r = new FileReader();
            r.onload = e => $('#avatarPreview').attr('src', e.target.result);
            r.readAsDataURL(i.files[0]);
        }
    }
    $('.select2').select2({
        theme: 'bootstrap-5'
    });

    $('[data-type]').on('click', function () {
        let formData = new FormData($('#FormProfil')[0]);
        formData.append('section', $(this).data('type'));

        $.ajax({
            url: $('#FormProfil').attr('action'),
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function (res) {
                Swal.fire({
                    toast: true,
                    position: 'top-end',
                    icon: 'success',
                    title: 'Berhasil update data',
                    showConfirmButton: false,
                    timer: 3000
                });
                setTimeout(() => location.href = '/app/profile', 1500);
            },
            error: function () {
                Swal.fire('Error', 'Cek kembali input', 'error');
            }
        });
    });

</script>
@endsection
