<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AchievementController extends Controller
{
    /**
     * Display a listing of achievements.
     */
    public function index(Request $request)
    {
        $query = Achievement::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        if ($request->has('featured')) {
            $query->featured();
        }

        if ($request->has('category')) {
            $query->where('category', $request->category);
        }

        $achievements = $query->orderBy('created_at', 'desc')->paginate($request->per_page ?? 10);

        return response()->json($achievements);
    }

    /**
     * Store a newly created achievement.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'event_date' => 'nullable|date',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
        ]);

        $validated['is_featured'] = filter_var($request->input('is_featured', false), FILTER_VALIDATE_BOOLEAN);
        $validated['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('achievements', 'public');
        }

        $achievement = Achievement::create($validated);

        return response()->json([
            'message' => 'Prestasi berhasil ditambahkan',
            'data' => $achievement
        ], 201);
    }

    /**
     * Display the specified achievement.
     */
    public function show(Achievement $achievement)
    {
        return response()->json($achievement);
    }

    /**
     * Update the specified achievement.
     */
    public function update(Request $request, Achievement $achievement)
    {
        $validated = $request->validate([
            'title' => 'sometimes|string|max:255',
            'excerpt' => 'nullable|string',
            'content' => 'nullable|string',
            'category' => 'nullable|string|max:100',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'event_date' => 'nullable|date',
            'is_featured' => 'nullable',
            'is_active' => 'nullable',
        ]);

        if ($request->has('is_featured')) {
            $validated['is_featured'] = filter_var($request->input('is_featured'), FILTER_VALIDATE_BOOLEAN);
        }
        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('image')) {
            if ($achievement->image) {
                Storage::disk('public')->delete($achievement->image);
            }
            $validated['image'] = $request->file('image')->store('achievements', 'public');
        }

        $achievement->update($validated);

        return response()->json([
            'message' => 'Prestasi berhasil diperbarui',
            'data' => $achievement
        ]);
    }

    /**
     * Remove the specified achievement.
     */
    public function destroy(Achievement $achievement)
    {
        if ($achievement->image) {
            Storage::disk('public')->delete($achievement->image);
        }

        $achievement->delete();

        return response()->json([
            'message' => 'Prestasi berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active achievements for frontend
     */
    public function publicIndex(Request $request)
    {
        $achievements = Achievement::active()
            ->orderBy('created_at', 'desc')
            ->limit($request->limit ?? 6)
            ->get();

        return response()->json($achievements);
    }
}
