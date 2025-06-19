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
            $table->string('nama_ayah', 255);
            $table->string('pekerjaan_ayah', 255);
            $table->string('alamat_ayah', 255);
            $table->string('telepon_ayah', 255);
            $table->string('kota_ayah', 255);
            $table->string('instansi_ayah', 255);
            $table->string('telepon_instansi_ayah', 255);
            $table->string('kode_pos_ayah', 5);
            $table->integer('penghasilan_ayah');
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
