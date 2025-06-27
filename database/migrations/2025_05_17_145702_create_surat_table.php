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
        Schema::create('surat', function (Blueprint $table) {
            $table->string('kode_surat', 5)->primary();
            $table->string('ditujukan', 255)->nullable();
            $table->string('keperluan', 255);
            $table->string('berkas', 255)->nullable();
            // $table->integer('id_user')->nullable();
            $table->string('nim', 9)->nullable();
            $table->string('kode_dosen', 6);
            $table->string('jenis_surat', 20); // Enum: Rekomendasi, Beasiswa, Pengantar, Lainnya
            // $table->text('pesan')->nullable();
            $table->string('status_surat', 20)->default('draft');
            $table->string('tahap_verifikasi', 20)->default('WaliDosen');
            // $table->foreign('id_user')->references('id_user')->on('users')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->restrictOnDelete()->restrictOnUpdate();
            // $table->index(['id_user'], 'membuat_fk');
            $table->index(['nim'], 'mengajukan_fk');
            // $table->index(['kode_dosen'], 'menandatangani_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('surat');
    }
};
