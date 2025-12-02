<?php

use App\Models\Keuangan_Jenis;
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
        Schema::create('keuangan_bayar', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Keuangan_Jenis::class);
            $table->string('tingkat');
            $table->string('kode_kelas');
            $table->integer('id_siswa');
            $table->string('tahun_akademik');
            $table->string('total_bayar');
            $table->date('waktu_dibayar');
            $table->string('keterangan');
            $table->string('id_user');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_bayar');
    }
};
