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
        Schema::create('dosen', function (Blueprint $table) {
            $table->string('kode_dosen', 6)->primary();
            $table->string('nip', 16)->nullable();
            $table->string('nidn', 10)->unique()->nullable();
            $table->string('nama_dosen', 255);
            $table->string('jabatan_dosen', 20)->nullable(); // Enum: Kajur, Kaprodi
            $table->string('ttd', 254)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dosen');
    }
};
