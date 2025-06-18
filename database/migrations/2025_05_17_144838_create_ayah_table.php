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
        Schema::create('ayah', function (Blueprint $table) {
            $table->string('nim', 9)->primary();
            $table->string('nama_ayah', 255)->nullable();
            $table->string('pekerjaan_ayah', 255)->nullable();
            $table->string('alamat_ayah', 255)->nullable();
            $table->string('telepon_ayah', 255)->nullable();
            $table->string('kota_ayah', 255)->nullable();
            $table->enum('pendidikan_ayah', ['S1', 'S2', 'D3', 'SLTA', 'SLTP', 'SD', 'TIDAK SD'])->nullable();
            $table->string('instansi_ayah', 255)->nullable();
            $table->string('telepon_instansi_ayah', 255)->nullable();
            $table->string('kode_pos_ayah', 5)->nullable();
            $table->string('penghasilan_ayah')->nullable();
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ayah');
    }
};
