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
        Schema::create('kp_pkl', function (Blueprint $table) {
            $table->integer('id_perusahaan');
            $table->integer('tahun');
            $table->string('nim', 9)->nullable();
            $table->string('kode_dosen', 6)->nullable();
            $table->string('nama_perusahaan', 255);
            $table->primary(['id_perusahaan', 'tahun']);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['nim'], 'menjalani_fk');
            $table->index(['kode_dosen'], 'mengawasi_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kp_pkl');
    }
};
