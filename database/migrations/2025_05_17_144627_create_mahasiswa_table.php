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
        Schema::create('mahasiswa', function (Blueprint $table) {
            $table->string('nim', 9)->primary();
            $table->string('nama_mhs', 255);
            $table->string('no_ktp', 16)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('telepon', 255)->nullable();
            $table->date('tgl_lahir')->nullable();
            $table->string('kota_lahir', 255)->nullable();
            $table->boolean('jenis_kelamin')->nullable();
            $table->enum('agama', ['Islam', 'Kristen', 'Katholik', 'Hindu', 'Buddha', 'Konghucu'])->nullable();
            $table->string('gol_darah', 2)->nullable();
            $table->integer('anak_ke')->nullable();
            $table->string('nama_slta', 255)->nullable();
            $table->enum('jalur_daftar', ['SNBT', 'SNBP', 'SMBM-TES', 'ADIK','Lainnya'])->nullable();
            $table->decimal('nem', 5, 2)->nullable();
            $table->string('status_mhs')->default('Aktif');
            $table->unsignedBigInteger('kelas_id')->nullable();
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->timestamps();
            $table->string('kota', 7)->nullable();
            $table->foreign('kota')->references('kota')->on('tugas_akhir')->restrictOnDelete()->restrictOnUpdate();
            $table->index(['kota'], 'tugas_akhir_fk');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mahasiswa');
    }
};
