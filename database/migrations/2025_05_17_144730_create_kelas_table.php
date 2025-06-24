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
        Schema::create('kelas', function (Blueprint $table) {
            $table->id();
            $table->string('nama_kelas', 1);
            $table->string('angkatan', 4);
            $table->unsignedBigInteger('kode_prodi');
            $table->string('kode_dosen', 6)->nullable();
            
            $table->unique(['nama_kelas', 'angkatan', 'kode_prodi']);
            $table->foreign('kode_prodi')->references('kode_prodi')->on('prodi')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->nullOnDelete()->restrictOnUpdate();
            $table->index(['kode_prodi'], 'termasuk_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kelas');
    }
};
