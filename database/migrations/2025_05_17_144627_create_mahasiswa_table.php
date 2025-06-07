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
            $table->string('nama_kelas', 1)->nullable();
            $table->string('angkatan', 4)->nullable();
            $table->string('nama_mhs', 255);
            $table->string('no_ktp', 16);
            $table->string('email', 255);
            $table->string('telepon', 255);
            $table->date('tgl_lahir');
            $table->string('kota_lahir', 255);
            $table->boolean('jenis_kelamin');
            $table->enum('agama', ['Islam', 'Kristen', 'Katolik', 'Hindu', 'Buddha', 'Konghucu']);
            $table->string('gol_darah', 2);
            $table->integer('anak_ke');
            $table->string('nama_slta', 255);
            $table->enum('jalur_daftar', ['SNBT', 'SNBP', 'SMBM-TES', 'ADIK','Lainnya']);
            $table->decimal('nem', 5, 2)->nullable();
            $table->unsignedBigInteger('kelas_id');
            $table->foreign('kelas_id')
                ->references('id')
                ->on('kelas')
                ->restrictOnDelete()
                ->restrictOnUpdate();
            $table->timestamps();
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
