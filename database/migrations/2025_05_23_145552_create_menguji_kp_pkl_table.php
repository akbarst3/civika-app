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
        Schema::create('menguji_kp_pkl', function (Blueprint $table) {
            $table->integer('id_perusahaan');
            $table->integer('tahun');
            $table->string('kode_dosen', 6);
            $table->unsignedTinyInteger('penguji_ke');
            $table->primary(['id_perusahaan', 'tahun', 'kode_dosen']);
            $table->foreign(['id_perusahaan', 'tahun'])->references(['id_perusahaan', 'tahun'])->on('kp_pkl')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['id_perusahaan', 'tahun'], 'menguji_kp_pkl_fk');
            $table->index('kode_dosen', 'menguji_kp_pkl2_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menguji_kp_pkl');
    }
};
