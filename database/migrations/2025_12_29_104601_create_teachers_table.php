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
        // Data Guru
        Schema::create('teachers', function (Blueprint $table) {
            $table->id();
            $table->string('nip')->nullable()->unique(); // Nomor Induk Pegawai
            $table->string('name');
            $table->string('photo')->nullable();
            $table->string('position')->nullable(); // Jabatan: Kepala Sekolah, Wakasek, Guru, dll
            $table->string('subject')->nullable(); // Mata pelajaran yang diampu
            $table->string('education')->nullable(); // Pendidikan terakhir
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->text('bio')->nullable(); // Biografi singkat
            $table->string('program_id')->nullable(); // Jurusan yang diampu
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teachers');
    }
};
