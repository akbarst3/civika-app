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
        Schema::create('indeks_prestasi_semester', function (Blueprint $table) {
            $table->string('nim', 9);
            $table->string('semester', 1);
            $table->string('status', 20);
            $table->decimal('indeks_prestasi', 3, 2);
            $table->integer('nilai_bobot');
            $table->integer('jumlah_d');
            $table->primary(['nim', 'semester']);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['nim'], 'mendapatkan_fk');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('indeks_prestasi_semester');
    }
};
