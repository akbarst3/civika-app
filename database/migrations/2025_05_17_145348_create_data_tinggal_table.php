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
        Schema::create('data_tinggal', function (Blueprint $table) {
            $table->string('nim', 9)->primary();
            $table->string('alamat_tinggal', 255)->nullable();
            $table->string('kode_pos', 5)->nullable();
            $table->string('kab_kota', 255)->nullable();
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['nim'], 'bertempat_tinggal_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_tinggal');
    }
};
