<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Api\AchievementController;
use App\Http\Controllers\Api\ArticleController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\PartnerController;
use App\Http\Controllers\Api\TestimonialController;
use App\Http\Controllers\Api\HeroSlideController;
use App\Http\Controllers\Api\SchoolProfileController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\FormerPrincipalController;
use App\Http\Controllers\Api\GalleryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public API Routes (No Authentication Required)
|--------------------------------------------------------------------------
*/

// Get data for frontend (public)
Route::prefix('public')->group(function () {
    // Homepage
    Route::get('/hero-slides', [HeroSlideController::class, 'publicIndex']);
    Route::get('/achievements', [AchievementController::class, 'publicIndex']);
    Route::get('/programs', [ProgramController::class, 'publicIndex']);
    Route::get('/programs/{slug}', [ProgramController::class, 'publicShow']);
    Route::get('/partners', [PartnerController::class, 'publicIndex']);
    Route::get('/testimonials', [TestimonialController::class, 'publicIndex']);
    
    // Articles
    Route::get('/articles', [ArticleController::class, 'publicIndex']);
    Route::get('/articles/{slug}', [ArticleController::class, 'publicShow']);
    
    // School Profile (Profil Sekolah)
    Route::get('/school-profiles', [SchoolProfileController::class, 'publicIndex']);
    Route::get('/school-profiles/{key}', [SchoolProfileController::class, 'publicShow']);
    
    // Teachers (Data Guru)
    Route::get('/teachers', [TeacherController::class, 'publicIndex']);
    Route::get('/teachers/leadership', [TeacherController::class, 'publicLeadership']);
    Route::get('/teachers/{teacher}', [TeacherController::class, 'publicShow']);
    
    // Schedules (Jadwal Pembelajaran)
    Route::get('/schedules', [ScheduleController::class, 'publicIndex']);
    Route::get('/schedules/classes', [ScheduleController::class, 'publicClasses']);

    // Former Principals
    Route::get('/former-principals', [FormerPrincipalController::class, 'publicIndex']);

    // Gallery (Foto, Reels, YouTube)
    Route::get('/galleries', [GalleryController::class, 'publicIndex']);
    Route::get('/galleries/photos', [GalleryController::class, 'publicPhotos']);
    Route::get('/galleries/reels', [GalleryController::class, 'publicReels']);
    Route::get('/galleries/youtube', [GalleryController::class, 'publicYoutube']);
});

/*
|--------------------------------------------------------------------------
| Auth Routes
|--------------------------------------------------------------------------
*/

Route::post('/login', [LoginController::class, 'store']);
Route::post('/logout', [LoginController::class, 'destroy'])->middleware('auth:sanctum');

Route::middleware(['auth:sanctum'])->get('/user', function (Request $request) {
    return $request->user();
});

/*
|--------------------------------------------------------------------------
| Protected Admin API Routes (Authentication Required)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth:sanctum'])->prefix('admin')->group(function () {
    // Achievements (Jejak Gemilang)
    Route::apiResource('achievements', AchievementController::class);
    
    // Articles (Berita)
    Route::apiResource('articles', ArticleController::class);
    
    // Programs (Jurusan)
    Route::apiResource('programs', ProgramController::class);
    
    // Partners (Mitra)
    Route::apiResource('partners', PartnerController::class);
    
    // Testimonials (Alumni)
    Route::apiResource('testimonials', TestimonialController::class);
    
    // Hero Slides
    Route::apiResource('hero-slides', HeroSlideController::class);
    
    // School Profiles (Profil Sekolah)
    Route::apiResource('school-profiles', SchoolProfileController::class);
    
    // Teachers (Data Guru)
    Route::apiResource('teachers', TeacherController::class);
    
    // Schedules (Jadwal Pembelajaran)
    Route::apiResource('schedules', ScheduleController::class);
    Route::post('schedules/bulk', [ScheduleController::class, 'bulkStore']);

    // Former Principals
    Route::apiResource('former-principals', FormerPrincipalController::class);

    // Gallery (Foto, Reels, YouTube)
    Route::apiResource('galleries', GalleryController::class);
});