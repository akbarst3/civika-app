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
        Schema::create('users', function (Blueprint $table) {
            $table->integer('id_user')->primary();
            $table->string('nim', 9)->nullable();
            $table->string('kode_dosen', 6)->nullable();
            $table->string('email', 255);
            $table->string('password', 255);

            $table->foreign('nim')
                ->references('nim')
                ->on('mahasiswa')
                ->restrictOnDelete()
                ->restrictOnUpdate();

            $table->foreign('kode_dosen')
                ->references('kode_dosen')
                ->on('dosen')
                ->restrictOnDelete()
                ->restrictOnUpdate();
                
            $table->index(['nim'], 'merupakan_fk');
            $table->index(['kode_dosen'], 'adalah_fk');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
