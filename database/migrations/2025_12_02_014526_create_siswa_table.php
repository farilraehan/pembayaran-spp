<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('siswa', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('nipd');
            $table->string('password');
            $table->date('tanggal_masuk');
            $table->integer('tahun_akademik');
            $table->string('nisn');
            $table->string('nama');
            $table->string('jenis_kelamin');
            $table->string('nik');
            $table->date('tanggal_lahir');
            $table->string('tempat_lahir');
            $table->string('agama');
            $table->string('alamat');
            $table->string('kebutuhan_khusus');
            $table->string('rt');
            $table->string('rw');
            $table->string('dusun');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kode_pos');
            $table->string('jenis_tinggal');
            $table->string('alat_transportasi');
            $table->string('telepon');
            $table->string('hp');
            $table->string('email');
            $table->string('skhun');
            $table->string('penerima_kps');
            $table->string('no_kps');
            $table->string('foto')->nullable();
            $table->string('angkatan');
            $table->string('status_awal');
            $table->string('status_siswa');
            $table->string('tingkat');
            $table->string('kode_kelas');
            $table->string('kode_jurusan');
            $table->string('ruang');
            $table->string('spp_nominal');
            $table->string('id_user');
            $table->string('nama_ayah');
            $table->string('tahun_lahir_ayah');
            $table->string('pendidikan_ayah');
            $table->string('pekerjaan_ayah');
            $table->string('penghasilan_ayah');
            $table->string('kebutuhan_khusus_ayah');
            $table->string('no_telepon_ayah');
            $table->string('nama_ibu');
            $table->string('tahun_lahir_ibu');
            $table->string('pendidikan_ibu');
            $table->string('pekerjaan_ibu');
            $table->string('penghasilan_ibu');
            $table->string('kebutuhan_khusus_ibu');
            $table->string('no_telepon_ibu');
            $table->string('nama_wali');
            $table->string('tahun_lahir_wali');
            $table->string('pendidikan_wali');
            $table->string('pekerjaan_wali');
            $table->string('penghasilan_wali');
            $table->string('kebutuhan_khusus_wali');
            $table->string('no_telepon_wali');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('siswa');
    }
};
