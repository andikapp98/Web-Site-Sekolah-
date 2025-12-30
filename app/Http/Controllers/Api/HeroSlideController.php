<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class HeroSlideController extends Controller
{
    /**
     * Display a listing of hero slides.
     */
    public function index(Request $request)
    {
        $query = HeroSlide::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        $slides = $query->ordered()->paginate($request->per_page ?? 10);

        return response()->json($slides);
    }

    /**
     * Store a newly created hero slide.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $slide = HeroSlide::create($validated);

        return response()->json([
            'message' => 'Hero Slide berhasil ditambahkan',
            'data' => $slide
        ], 201);
    }

    /**
     * Display the specified hero slide.
     */
    public function show(HeroSlide $heroSlide)
    {
        return response()->json($heroSlide);
    }

    /**
     * Update the specified hero slide.
     */
    public function update(Request $request, HeroSlide $heroSlide)
    {
        $validated = $request->validate([
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:5120',
            'button_text' => 'nullable|string|max:100',
            'button_link' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($heroSlide->image) {
                Storage::disk('public')->delete($heroSlide->image);
            }
            $validated['image'] = $request->file('image')->store('hero-slides', 'public');
        }

        $heroSlide->update($validated);

        return response()->json([
            'message' => 'Hero Slide berhasil diperbarui',
            'data' => $heroSlide
        ]);
    }

    /**
     * Remove the specified hero slide.
     */
    public function destroy(HeroSlide $heroSlide)
    {
        if ($heroSlide->image) {
            Storage::disk('public')->delete($heroSlide->image);
        }

        $heroSlide->delete();

        return response()->json([
            'message' => 'Hero Slide berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active hero slides for frontend
     */
    public function publicIndex()
    {
        $slides = HeroSlide::active()->ordered()->get();

        return response()->json($slides);
    }
}
