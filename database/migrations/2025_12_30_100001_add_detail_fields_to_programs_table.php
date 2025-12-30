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
        Schema::table('programs', function (Blueprint $table) {
            $table->text('career_opportunities')->nullable()->after('full_description'); // Peluang Karir
            $table->text('competencies')->nullable()->after('career_opportunities'); // Kompetensi Lulusan (JSON array)
            $table->text('facilities')->nullable()->after('competencies'); // Ruang Praktek (JSON array)
            $table->text('equipment')->nullable()->after('facilities'); // Alat Praktek (JSON array)
            $table->string('career_image')->nullable()->after('equipment'); // Gambar untuk card karir
            $table->string('workshop_image')->nullable()->after('career_image'); // Gambar workshop/praktek
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('programs', function (Blueprint $table) {
            $table->dropColumn([
                'career_opportunities',
                'competencies', 
                'facilities',
                'equipment',
                'career_image',
                'workshop_image'
            ]);
        });
    }
};
