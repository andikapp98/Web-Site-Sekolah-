<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\SchoolProfile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SchoolProfileController extends Controller
{
    /**
     * Display a listing of school profiles.
     */
    public function index(Request $request)
    {
        $query = SchoolProfile::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        $profiles = $query->get();

        return response()->json($profiles);
    }

    /**
     * Store a newly created school profile.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'key' => 'required|string|max:100|unique:school_profiles,key',
            'title' => 'nullable|string|max:255',
            'content' => 'required|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'subtitle' => 'nullable|string|max:255',
            'metadata' => 'nullable',
            'is_active' => 'nullable',
        ]);

        // Handle metadata if it's a JSON string
        if (isset($validated['metadata']) && is_string($validated['metadata'])) {
            $validated['metadata'] = json_decode($validated['metadata'], true);
        }
        
        // Handle is_active
        $validated['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('school-profiles', 'public');
        }

        $profile = SchoolProfile::create($validated);

        return response()->json([
            'message' => 'Profil sekolah berhasil ditambahkan',
            'data' => $profile
        ], 201);
    }

    /**
     * Display the specified school profile.
     */
    public function show(SchoolProfile $schoolProfile)
    {
        return response()->json($schoolProfile);
    }

    /**
     * Update the specified school profile.
     */
    public function update(Request $request, SchoolProfile $schoolProfile)
    {
        $validated = $request->validate([
            'key' => 'sometimes|string|max:100|unique:school_profiles,key,' . $schoolProfile->id,
            'title' => 'nullable|string|max:255',
            'content' => 'sometimes|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'subtitle' => 'nullable|string|max:255',
            'metadata' => 'nullable',
            'is_active' => 'nullable',
        ]);

        // Handle metadata if it's a JSON string
        if (isset($validated['metadata']) && is_string($validated['metadata'])) {
            $validated['metadata'] = json_decode($validated['metadata'], true);
        }
        
        // Handle is_active
        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            if ($schoolProfile->image) {
                Storage::disk('public')->delete($schoolProfile->image);
            }
            $validated['image'] = $request->file('image')->store('school-profiles', 'public');
        }

        $schoolProfile->update($validated);

        return response()->json([
            'message' => 'Profil sekolah berhasil diperbarui',
            'data' => $schoolProfile
        ]);
    }

    /**
     * Remove the specified school profile.
     */
    public function destroy(SchoolProfile $schoolProfile)
    {
        if ($schoolProfile->image) {
            Storage::disk('public')->delete($schoolProfile->image);
        }

        $schoolProfile->delete();

        return response()->json([
            'message' => 'Profil sekolah berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get all active school profiles
     */
    public function publicIndex()
    {
        $profiles = SchoolProfile::active()->get()->keyBy('key');

        return response()->json($profiles);
    }

    /**
     * Public endpoint - get specific profile by key
     */
    public function publicShow($key)
    {
        $profile = SchoolProfile::getByKey($key);
        
        if (!$profile) {
            return response()->json(['message' => 'Profil tidak ditemukan'], 404);
        }

        return response()->json($profile);
    }
}
