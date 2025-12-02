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
        Schema::create('keuangan_nominal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('angkatan');
            $table->integer('id_keuangan_jenis');
            $table->string('total_beban');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('keuangan_nominal');
    }
};
