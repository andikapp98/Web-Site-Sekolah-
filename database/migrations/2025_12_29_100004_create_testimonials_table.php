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
        // Testimonial Alumni
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('jurusan'); // e.g., ALUMNI TPM, ALUMNI TKR
            $table->string('graduation_year')->nullable();
            $table->string('photo')->nullable();
            $table->text('quote');
            $table->string('current_position')->nullable(); // Pekerjaan saat ini
            $table->string('company')->nullable();
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
        Schema::dropIfExists('testimonials');
    }
};
