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
        // Jadwal Pembelajaran
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained('programs')->nullOnDelete();
            $table->string('class_name'); // Nama kelas: X TPM 1, XI TKR 2, dll
            $table->string('day'); // Hari: Senin, Selasa, dll
            $table->time('start_time');
            $table->time('end_time');
            $table->string('subject'); // Mata pelajaran
            $table->foreignId('teacher_id')->nullable()->constrained('teachers')->nullOnDelete();
            $table->string('room')->nullable(); // Ruangan
            $table->string('semester')->nullable(); // Semester: Ganjil/Genap
            $table->string('academic_year')->nullable(); // Tahun ajaran: 2024/2025
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
