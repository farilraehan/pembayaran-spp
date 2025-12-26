<div class="card-body">
    <form action="/app/pengaturan/lembaga/{{ $profil->id }}" method="POST" class="text-start" id="FormLembaga">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('nama', $profil->nama) ? 'is-filled' : '' }}">
                    <label class="form-label">Update Nama Lembaga</label>
                    <input type="text" name="nama" id="nama" value="{{ $profil->nama }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('alamat', $profil->alamat) ? 'is-filled' : '' }}">
                    <label class="form-label">Update Alamat</label>
                    <input type="text" name="alamat" id="alamat" value="{{ $profil->alamat }}" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('telpon', $profil->telpon) ? 'is-filled' : '' }}">
                    <label class="form-label">Update No Telepon</label>
                    <input type="text" name="telpon" id="telpon" value="{{ $profil->telpon }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('penanggung_jawab', $profil->penanggung_jawab) ? 'is-filled' : '' }}">
                    <label class="form-label">Update Penangung Jawab</label>
                    <input type="text" name="penanggung_jawab" id="penanggung_jawab" value="{{ $profil->penanggung_jawab }}" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-info px-3" type="submit" id="SimpanLembaga">
                Simpan Lembaga
            </button>
        </div>
    </form>
</div>
