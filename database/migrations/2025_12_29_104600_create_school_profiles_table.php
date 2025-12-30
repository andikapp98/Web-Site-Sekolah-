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
        // Profil Sekolah (Sambutan Kepala Sekolah, Visi Misi, Sejarah, dll)
        Schema::create('school_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // e.g., 'principal_message', 'vision', 'mission', 'history'
            $table->string('title')->nullable();
            $table->longText('content');
            $table->string('image')->nullable(); // Foto kepala sekolah, dll
            $table->string('subtitle')->nullable();
            $table->json('metadata')->nullable(); // Data tambahan dalam format JSON
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('school_profiles');
    }
};
