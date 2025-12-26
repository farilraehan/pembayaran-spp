<div class="card-body">
    <form action="/app/pengaturan/kelas" method="POST" id="FormKelas">
        @csrf
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('kode_kelas', $kelas->kode_kelas) ? 'is-filled' : '' }}">
                    <label class="form-label">masukkan Kode Kelas</label>
                    <input type="text" name="kode_kelas" id="kode_kelas" value="{{ $kelas->kode_kelas }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('nama_kelas', $kelas->nama_kelas) ? 'is-filled' : '' }}">
                    <label class="form-label">masukkan Nama Kelas</label>
                    <input type="text" name="nama_kelas" id="nama_kelas" value="{{ $kelas->nama_kelas }}" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('tingkat', $kelas->tingkat) ? 'is-filled' : '' }}">
                    <label class="form-label">masukkan Tingkat</label>
                    <input type="number" name="tingkat" id="tingkat" value="{{ $kelas->tingkat }}" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('kode_kurikulum', $kelas->kode_kurikulum) ? 'is-filled' : '' }}">
                    <label class="form-label">masukkan Kode Kurikulum</label>
                    <input type="number" name="kode_kurikulum" id="kode_kurikulum" value="{{ $kelas->kode_kurikulum }}" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="text-end gap-2">
            <button class="btn btn-warning px-3" type="button" data-bs-toggle="modal" data-bs-target="#previewKelas">
                Preview Kelas
            </button>
            <button class="btn btn-info px-3" type="submit" id="SimpanKelas">
                Simpan Kelas
            </button>
        </div>
    </form>
</div>
