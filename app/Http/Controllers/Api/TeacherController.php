<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TeacherController extends Controller
{
    /**
     * Display a listing of teachers.
     */
    public function index(Request $request)
    {
        $query = Teacher::with('program');

        if ($request->has('active_only')) {
            $query->active();
        }

        if ($request->has('position')) {
            $query->byPosition($request->position);
        }

        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $teachers = $query->ordered()->paginate($request->per_page ?? 20);

        return response()->json($teachers);
    }

    /**
     * Store a newly created teacher.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:50|unique:teachers,nip',
            'name' => 'required|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'position' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'program_id' => 'nullable|exists:programs,id',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher = Teacher::create($validated);

        return response()->json([
            'message' => 'Data guru berhasil ditambahkan',
            'data' => $teacher->load('program')
        ], 201);
    }

    /**
     * Display the specified teacher.
     */
    public function show(Teacher $teacher)
    {
        return response()->json($teacher->load('program'));
    }

    /**
     * Update the specified teacher.
     */
    public function update(Request $request, Teacher $teacher)
    {
        $validated = $request->validate([
            'nip' => 'nullable|string|max:50|unique:teachers,nip,' . $teacher->id,
            'name' => 'sometimes|string|max:255',
            'photo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'position' => 'nullable|string|max:100',
            'subject' => 'nullable|string|max:255',
            'education' => 'nullable|string|max:255',
            'email' => 'nullable|email|max:255',
            'phone' => 'nullable|string|max:20',
            'bio' => 'nullable|string',
            'program_id' => 'nullable|exists:programs,id',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('photo')) {
            if ($teacher->photo) {
                Storage::disk('public')->delete($teacher->photo);
            }
            $validated['photo'] = $request->file('photo')->store('teachers', 'public');
        }

        $teacher->update($validated);

        return response()->json([
            'message' => 'Data guru berhasil diperbarui',
            'data' => $teacher->load('program')
        ]);
    }

    /**
     * Remove the specified teacher.
     */
    public function destroy(Teacher $teacher)
    {
        if ($teacher->photo) {
            Storage::disk('public')->delete($teacher->photo);
        }

        $teacher->delete();

        return response()->json([
            'message' => 'Data guru berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active teachers for frontend
     */
    public function publicIndex(Request $request)
    {
        $query = Teacher::with('program')->active()->ordered();

        if ($request->has('position')) {
            $query->byPosition($request->position);
        }

        if ($request->has('program_id')) {
            $query->where('program_id', $request->program_id);
        }

        $teachers = $query->get();

        return response()->json($teachers);
    }

    /**
     * Public endpoint - get teacher detail
     */
    public function publicShow(Teacher $teacher)
    {
        if (!$teacher->is_active) {
            return response()->json(['message' => 'Guru tidak ditemukan'], 404);
        }

        return response()->json($teacher->load('program'));
    }

    /**
     * Public endpoint - get leadership (Kepala Sekolah & Wakasek)
     */
    public function publicLeadership()
    {
        $leadership = Teacher::active()
            ->whereIn('position', ['Kepala Sekolah', 'Wakil Kepala Sekolah', 'Wakasek'])
            ->ordered()
            ->get();

        return response()->json($leadership);
    }
}
