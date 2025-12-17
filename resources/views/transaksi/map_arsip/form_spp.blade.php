    <div class="row">
        <div class="ms-3">
            <h3 class="mb-0 h4 font-weight-bolder">Jenis Biaya</h3>
            <p class="mb-4">Management System Pembayaran SPP</p>
        </div>
        <div class="col-8">
            <div class="card my-4">
                <div class="card-header p-0 position-relative mt-n4 mx-3 z-index-2">
                    <div
                        class="bg-gradient-secondary shadow-secondary border-radius-lg pt-3 pb-1 d-flex justify-content-between align-items-center">
                        <h6 class="text-white text-capitalize ps-3" id="headerTitle">
                            Tambah Jenis dan Nominal Pembayaran
                        </h6>
                    </div>
                </div>
                <div class="card-body">
                    <form id="FormJenisBiaya" method="POST" action="/app/Jenis-biaya" class="text-start">
                        @csrf
                        <div class="row">
                            <div class="col-md-4">
                                <label for="angkatan">Tahun Angkatan</label>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label">Masukkan tahun angkatan</label>
                                    <input type="number" name="angkatan" class="form-control" required>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="">Pilih Jenis Pembayaran/Nama jenis lain</label>
                                <div class="input-group input-group-outline flex-fill">
                                    <select name="kode_akun" id="kode_akun" class="form-control select2">
                                        <option value="">-- Pilih Jenis Pembayaran --</option>
                                        
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <label for="total_beban">Total Beban</label>
                                <div class="input-group input-group-outline mb-3">
                                    <label class="form-label"> Masukkan Total Beban</label>
                                    <input type="text" name="total_beban" class="form-control nominal " required>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-between mt-3">
                            <a href="/app/Jenis-biaya" class="btn btn-secondary">Kembali</a>
                            <button type="submit" class="btn btn-info" id="simpan">Simpan Data</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>