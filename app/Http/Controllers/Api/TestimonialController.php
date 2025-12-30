<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TestimonialController extends Controller
{
    /**
     * Display a listing of testimonials.
     */
    public function index(Request $request)
    {
        $query = Testimonial::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        $testimonials = $query->ordered()->paginate($request->per_page ?? 10);

        return response()->json($testimonials);
    }

    /**
     * Store a newly created testimonial.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'jurusan' => 'required|string|max:100',
            'graduation_year' => 'nullable|string|max:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quote' => 'required|string',
            'current_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial = Testimonial::create($validated);

        return response()->json([
            'message' => 'Testimonial berhasil ditambahkan',
            'data' => $testimonial
        ], 201);
    }

    /**
     * Display the specified testimonial.
     */
    public function show(Testimonial $testimonial)
    {
        return response()->json($testimonial);
    }

    /**
     * Update the specified testimonial.
     */
    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'jurusan' => 'sometimes|string|max:100',
            'graduation_year' => 'nullable|string|max:4',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'quote' => 'sometimes|string',
            'current_position' => 'nullable|string|max:255',
            'company' => 'nullable|string|max:255',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if ($request->hasFile('photo')) {
            if ($testimonial->photo) {
                Storage::disk('public')->delete($testimonial->photo);
            }
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $testimonial->update($validated);

        return response()->json([
            'message' => 'Testimonial berhasil diperbarui',
            'data' => $testimonial
        ]);
    }

    /**
     * Remove the specified testimonial.
     */
    public function destroy(Testimonial $testimonial)
    {
        if ($testimonial->photo) {
            Storage::disk('public')->delete($testimonial->photo);
        }

        $testimonial->delete();

        return response()->json([
            'message' => 'Testimonial berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active testimonials for frontend
     */
    public function publicIndex()
    {
        $testimonials = Testimonial::active()->ordered()->get();

        return response()->json($testimonials);
    }
}
