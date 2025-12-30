<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
    /**
     * Display a listing of schedules.
     */
    public function index(Request $request)
    {
        $query = Schedule::with(['program', 'teacher']);

        if ($request->has('active_only')) {
            $query->active();
        }

        if ($request->has('program_id')) {
            $query->byProgram($request->program_id);
        }

        if ($request->has('class_name')) {
            $query->byClass($request->class_name);
        }

        if ($request->has('day')) {
            $query->byDay($request->day);
        }

        if ($request->has('teacher_id')) {
            $query->where('teacher_id', $request->teacher_id);
        }

        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->paginate($request->per_page ?? 50);

        return response()->json($schedules);
    }

    /**
     * Store a newly created schedule.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'class_name' => 'required|string|max:50',
            'day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'subject' => 'required|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:50',
            'semester' => 'nullable|string|in:Ganjil,Genap',
            'academic_year' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $schedule = Schedule::create($validated);

        return response()->json([
            'message' => 'Jadwal berhasil ditambahkan',
            'data' => $schedule->load(['program', 'teacher'])
        ], 201);
    }

    /**
     * Display the specified schedule.
     */
    public function show(Schedule $schedule)
    {
        return response()->json($schedule->load(['program', 'teacher']));
    }

    /**
     * Update the specified schedule.
     */
    public function update(Request $request, Schedule $schedule)
    {
        $validated = $request->validate([
            'program_id' => 'nullable|exists:programs,id',
            'class_name' => 'sometimes|string|max:50',
            'day' => 'sometimes|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'start_time' => 'sometimes|date_format:H:i',
            'end_time' => 'sometimes|date_format:H:i',
            'subject' => 'sometimes|string|max:255',
            'teacher_id' => 'nullable|exists:teachers,id',
            'room' => 'nullable|string|max:50',
            'semester' => 'nullable|string|in:Ganjil,Genap',
            'academic_year' => 'nullable|string|max:20',
            'is_active' => 'boolean',
        ]);

        $schedule->update($validated);

        return response()->json([
            'message' => 'Jadwal berhasil diperbarui',
            'data' => $schedule->load(['program', 'teacher'])
        ]);
    }

    /**
     * Remove the specified schedule.
     */
    public function destroy(Schedule $schedule)
    {
        $schedule->delete();

        return response()->json([
            'message' => 'Jadwal berhasil dihapus'
        ]);
    }

    /**
     * Bulk store schedules
     */
    public function bulkStore(Request $request)
    {
        $validated = $request->validate([
            'schedules' => 'required|array',
            'schedules.*.program_id' => 'nullable|exists:programs,id',
            'schedules.*.class_name' => 'required|string|max:50',
            'schedules.*.day' => 'required|string|in:Senin,Selasa,Rabu,Kamis,Jumat,Sabtu',
            'schedules.*.start_time' => 'required|date_format:H:i',
            'schedules.*.end_time' => 'required|date_format:H:i',
            'schedules.*.subject' => 'required|string|max:255',
            'schedules.*.teacher_id' => 'nullable|exists:teachers,id',
            'schedules.*.room' => 'nullable|string|max:50',
            'schedules.*.semester' => 'nullable|string|in:Ganjil,Genap',
            'schedules.*.academic_year' => 'nullable|string|max:20',
        ]);

        $created = [];
        foreach ($validated['schedules'] as $scheduleData) {
            $scheduleData['is_active'] = true;
            $created[] = Schedule::create($scheduleData);
        }

        return response()->json([
            'message' => count($created) . ' jadwal berhasil ditambahkan',
            'data' => $created
        ], 201);
    }

    /**
     * Public endpoint - get schedules for frontend
     */
    public function publicIndex(Request $request)
    {
        $query = Schedule::with(['program', 'teacher'])->active();

        if ($request->has('program_id')) {
            $query->byProgram($request->program_id);
        }

        if ($request->has('class_name')) {
            $query->byClass($request->class_name);
        }

        if ($request->has('day')) {
            $query->byDay($request->day);
        }

        $schedules = $query->orderBy('day')
            ->orderBy('start_time')
            ->get();

        // Group by day
        $grouped = $schedules->groupBy('day');

        return response()->json($grouped);
    }

    /**
     * Public endpoint - get available classes
     */
    public function publicClasses()
    {
        $classes = Schedule::active()
            ->select('class_name', 'program_id')
            ->distinct()
            ->with('program:id,name,short_name')
            ->get();

        return response()->json($classes);
    }
}
