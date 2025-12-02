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
        Schema::create('anggota_kelas', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_siswa');
            $table->integer('id_tahun_akademik');
            $table->string('tingkat');
            $table->string('kode_kelas');
            $table->string('spp');
            $table->string('du');
            $table->string('tgl_masuk');
            $table->string('tgl_keluar');
            $table->string('status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('anggota_kelas');
    }
};
