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
        Schema::create('nilai', function (Blueprint $table) {
            $table->string('kode_dosen', 6);
            $table->string('kode_matkul', 8);
            $table->string('nim', 9);
            $table->string('indeks_nilai', 1)->nullable();
            $table->integer('semester_ke')->nullable();
            $table->primary(['kode_dosen', 'kode_matkul', 'nim']);
            $table->unique(['kode_dosen', 'kode_matkul', 'nim'], 'identifier_1_nilai');
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_matkul')->references('kode_matkul')->on('mata_kuliah')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['kode_dosen'], 'nilai2_fk');
            $table->index(['kode_matkul'], 'nilai3_fk');
            $table->index(['nim'], 'nilai_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai');
    }
};
