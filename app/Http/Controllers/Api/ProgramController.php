<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProgramController extends Controller
{
    /**
     * Display a listing of programs.
     */
    public function index(Request $request)
    {
        $query = Program::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        $programs = $query->ordered()->paginate($request->per_page ?? 10);

        return response()->json($programs);
    }

    /**
     * Store a newly created program.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'short_name' => 'nullable|string|max:20',
            'description' => 'required|string',
            'full_description' => 'nullable|string',
            'career_opportunities' => 'nullable|string',
            'competencies' => 'nullable',
            'facilities' => 'nullable',
            'equipment' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'career_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'workshop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|file|mimes:mp4,gif|max:10240',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        // Handle JSON arrays
        if (isset($validated['competencies']) && is_string($validated['competencies'])) {
            $validated['competencies'] = json_decode($validated['competencies'], true);
        }
        if (isset($validated['facilities']) && is_string($validated['facilities'])) {
            $validated['facilities'] = json_decode($validated['facilities'], true);
        }
        if (isset($validated['equipment']) && is_string($validated['equipment'])) {
            $validated['equipment'] = json_decode($validated['equipment'], true);
        }

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        if ($request->hasFile('career_image')) {
            $validated['career_image'] = $request->file('career_image')->store('programs/career', 'public');
        }

        if ($request->hasFile('workshop_image')) {
            $validated['workshop_image'] = $request->file('workshop_image')->store('programs/workshop', 'public');
        }

        if ($request->hasFile('video')) {
            $validated['video'] = $request->file('video')->store('programs/videos', 'public');
        }

        if ($request->hasFile('icon')) {
            $validated['icon'] = $request->file('icon')->store('programs/icons', 'public');
        }

        $program = Program::create($validated);

        return response()->json([
            'message' => 'Program Keahlian berhasil ditambahkan',
            'data' => $program
        ], 201);
    }

    /**
     * Display the specified program.
     */
    public function show(Program $program)
    {
        return response()->json($program);
    }

    /**
     * Update the specified program.
     */
    public function update(Request $request, Program $program)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'short_name' => 'nullable|string|max:20',
            'description' => 'sometimes|string',
            'full_description' => 'nullable|string',
            'career_opportunities' => 'nullable|string',
            'competencies' => 'nullable',
            'facilities' => 'nullable',
            'equipment' => 'nullable',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'career_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'workshop_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'video' => 'nullable|file|mimes:mp4,gif|max:10240',
            'icon' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'order' => 'nullable|integer',
            'is_active' => 'boolean',
        ]);

        if (isset($validated['name'])) {
            $validated['slug'] = Str::slug($validated['name']);
        }

        // Handle JSON arrays
        if (isset($validated['competencies']) && is_string($validated['competencies'])) {
            $validated['competencies'] = json_decode($validated['competencies'], true);
        }
        if (isset($validated['facilities']) && is_string($validated['facilities'])) {
            $validated['facilities'] = json_decode($validated['facilities'], true);
        }
        if (isset($validated['equipment']) && is_string($validated['equipment'])) {
            $validated['equipment'] = json_decode($validated['equipment'], true);
        }

        if ($request->hasFile('image')) {
            if ($program->image) {
                Storage::disk('public')->delete($program->image);
            }
            $validated['image'] = $request->file('image')->store('programs', 'public');
        }

        if ($request->hasFile('career_image')) {
            if ($program->career_image) {
                Storage::disk('public')->delete($program->career_image);
            }
            $validated['career_image'] = $request->file('career_image')->store('programs/career', 'public');
        }

        if ($request->hasFile('workshop_image')) {
            if ($program->workshop_image) {
                Storage::disk('public')->delete($program->workshop_image);
            }
            $validated['workshop_image'] = $request->file('workshop_image')->store('programs/workshop', 'public');
        }

        if ($request->hasFile('video')) {
            if ($program->video) {
                Storage::disk('public')->delete($program->video);
            }
            $validated['video'] = $request->file('video')->store('programs/videos', 'public');
        }

        if ($request->hasFile('icon')) {
            if ($program->icon) {
                Storage::disk('public')->delete($program->icon);
            }
            $validated['icon'] = $request->file('icon')->store('programs/icons', 'public');
        }

        $program->update($validated);

        return response()->json([
            'message' => 'Program Keahlian berhasil diperbarui',
            'data' => $program
        ]);
    }

    /**
     * Remove the specified program.
     */
    public function destroy(Program $program)
    {
        if ($program->image) {
            Storage::disk('public')->delete($program->image);
        }
        if ($program->career_image) {
            Storage::disk('public')->delete($program->career_image);
        }
        if ($program->workshop_image) {
            Storage::disk('public')->delete($program->workshop_image);
        }
        if ($program->icon) {
            Storage::disk('public')->delete($program->icon);
        }

        $program->delete();

        return response()->json([
            'message' => 'Program Keahlian berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active programs for frontend
     */
    public function publicIndex()
    {
        $programs = Program::active()->ordered()->get();

        return response()->json($programs);
    }

    /**
     * Public endpoint - get single program by slug
     */
    public function publicShow($slug)
    {
        $program = Program::where('slug', $slug)->active()->firstOrFail();
        
        return response()->json($program);
    }
}
