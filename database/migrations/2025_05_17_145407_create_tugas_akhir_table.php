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
        Schema::create('tugas_akhir', function (Blueprint $table) {
            $table->string('kota', 7)->primary();
            $table->string('nim', 9)->nullable();
            $table->string('topik', 255);
            $table->foreign('nim')->references('nim')->on('mahasiswa')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['nim'], 'mengerjakan_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tugas_akhir');
    }
};
