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
        Schema::create('membimbing', function (Blueprint $table) {
            $table->string('kota', 7);
            $table->string('kode_dosen', 6);
            $table->primary(['kota', 'kode_dosen']);
            $table->unsignedTinyInteger('pembimbing_ke');
            $table->foreign('kota')->references('kota')->on('tugas_akhir')->restrictOnDelete()->restrictOnUpdate();
            $table->foreign('kode_dosen')->references('kode_dosen')->on('dosen')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['kode_dosen'], 'membimbing2_fk');
            $table->index(['kota'], 'membimbing_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('membimbing');
    }
};
