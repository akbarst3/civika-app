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
        Schema::create('ibu', function (Blueprint $table) {
            $table->string('nim', 9)->primary();
            $table->string('nama_ibu', 255)->nullable();
            $table->string('pekerjaan_ibu', 255)->nullable();
            $table->string('alamat_ibu', 255)->nullable();
            $table->string('telepon_ibu', 255)->nullable();
            $table->string('kota_ibu', 255)->nullable();
            $table->enum('pendidikan_ibu', ['S1', 'S2', 'D3', 'SLTA', 'SLTP', 'SD', 'TIDAK SD'])->nullable();
            $table->string('instansi_ibu', 255)->nullable();
            $table->string('telepon_instansi_ibu', 255)->nullable();
            $table->string('kode_pos_ibu', 5)->nullable();
            $table->string('penghasilan_ibu')->nullable();
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ibu');
    }
};
