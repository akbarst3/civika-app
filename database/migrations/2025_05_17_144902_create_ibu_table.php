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
            $table->string('nama_ibu', 255);
            $table->string('pekerjaan_ibu', 255);
            $table->string('alamat_ibu', 255);
            $table->string('telepon_ibu', 255);
            $table->string('kota_ibu', 255);
            $table->string('instansi_ibu', 255);
            $table->string('telepon_instansi_ibu', 255);
            $table->string('kode_pos_ibu', 5);
            $table->integer('penghasilan_ibu'); 
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
