<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GalleryController extends Controller
{
    // ============ PUBLIC ENDPOINTS ============
    
    public function publicIndex(Request $request)
    {
        $type = $request->get('type');
        
        $query = Gallery::active()->orderBy('order')->orderBy('created_at', 'desc');
        
        if ($type) {
            $query->where('type', $type);
        }
        
        return response()->json($query->get());
    }

    public function publicPhotos()
    {
        return response()->json(
            Gallery::active()->photos()->orderBy('order')->orderBy('created_at', 'desc')->get()
        );
    }

    public function publicReels()
    {
        return response()->json(
            Gallery::active()->reels()->orderBy('order')->orderBy('created_at', 'desc')->get()
        );
    }

    public function publicYoutube()
    {
        return response()->json(
            Gallery::active()->youtube()->orderBy('order')->orderBy('created_at', 'desc')->get()
        );
    }

    // ============ ADMIN ENDPOINTS ============
    
    public function index(Request $request)
    {
        $query = Gallery::orderBy('type')->orderBy('order')->orderBy('created_at', 'desc');
        
        if ($request->has('type')) {
            $query->where('type', $request->type);
        }
        
        return response()->json($query->paginate($request->get('per_page', 50)));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:photo,reels,youtube',
            'image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('galleries', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('galleries/thumbnails', 'public');
        }

        $validated['is_active'] = $request->boolean('is_active', true);

        $gallery = Gallery::create($validated);

        return response()->json($gallery, 201);
    }

    public function show(Gallery $gallery)
    {
        return response()->json($gallery);
    }

    public function update(Request $request, Gallery $gallery)
    {
        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'sometimes|required|in:photo,reels,youtube',
            'image' => 'nullable|image|max:5120',
            'video_url' => 'nullable|string|max:500',
            'thumbnail' => 'nullable|image|max:2048',
            'order' => 'nullable|integer',
            'is_active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('image')) {
            if ($gallery->image) {
                Storage::disk('public')->delete($gallery->image);
            }
            $validated['image'] = $request->file('image')->store('galleries', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($gallery->thumbnail) {
                Storage::disk('public')->delete($gallery->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('galleries/thumbnails', 'public');
        }

        if ($request->has('is_active')) {
            $validated['is_active'] = $request->boolean('is_active');
        }

        $gallery->update($validated);

        return response()->json($gallery);
    }

    public function destroy(Gallery $gallery)
    {
        if ($gallery->image) {
            Storage::disk('public')->delete($gallery->image);
        }
        if ($gallery->thumbnail) {
            Storage::disk('public')->delete($gallery->thumbnail);
        }

        $gallery->delete();

        return response()->json(['message' => 'Deleted successfully']);
    }
}
