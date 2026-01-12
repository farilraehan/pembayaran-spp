<div class="card-body">
    <form action="/app/pengaturan/jatuh_tempo/{{ $profil->id }}" method="POST" class="text-start" id="FormJatuhTempo">
        @csrf
        
        <div class="row mb-3">
            <div class="col-md-6 mb-3">
                <div class="input-group input-group-outline {{ old('jatuh_tempo', $profil->jatuh_tempo) ? 'is-filled' : '' }}">
                    <label class="form-label">Update Tanggal Toleransi</label>
                    <input type="number" name="jatuh_tempo" id="jatuh_tempo" value="{{ $profil->jatuh_tempo }}" class="form-control" required>
                </div>
            </div>
        </div>
        <div class="text-end">
            <button class="btn btn-info px-3" type="submit" id="SimpanJatuhTempo">
                Simpan Jatuh Tempo
            </button>
        </div>
    </form>
</div>
