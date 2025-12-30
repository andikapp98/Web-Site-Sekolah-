<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PartnerController extends Controller
{
    /**
     * Display a listing of partners.
     */
    public function index(Request $request)
    {
        $query = Partner::query();

        if ($request->has('active_only')) {
            $query->active();
        }

        $partners = $query->ordered()->paginate($request->per_page ?? 20);

        return response()->json($partners);
    }

    /**
     * Store a newly created partner.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        $validated['is_active'] = filter_var($request->input('is_active', true), FILTER_VALIDATE_BOOLEAN);

        if ($request->hasFile('logo')) {
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner = Partner::create($validated);

        return response()->json([
            'message' => 'Mitra berhasil ditambahkan',
            'data' => $partner
        ], 201);
    }

    /**
     * Display the specified partner.
     */
    public function show(Partner $partner)
    {
        return response()->json($partner);
    }

    /**
     * Update the specified partner.
     */
    public function update(Request $request, Partner $partner)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:1024',
            'website' => 'nullable|url|max:255',
            'description' => 'nullable|string',
            'order' => 'nullable|integer',
            'is_active' => 'nullable',
        ]);

        if ($request->has('is_active')) {
            $validated['is_active'] = filter_var($request->input('is_active'), FILTER_VALIDATE_BOOLEAN);
        }

        if ($request->hasFile('logo')) {
            if ($partner->logo) {
                Storage::disk('public')->delete($partner->logo);
            }
            $validated['logo'] = $request->file('logo')->store('partners', 'public');
        }

        $partner->update($validated);

        return response()->json([
            'message' => 'Mitra berhasil diperbarui',
            'data' => $partner
        ]);
    }

    /**
     * Remove the specified partner.
     */
    public function destroy(Partner $partner)
    {
        if ($partner->logo) {
            Storage::disk('public')->delete($partner->logo);
        }

        $partner->delete();

        return response()->json([
            'message' => 'Mitra berhasil dihapus'
        ]);
    }

    /**
     * Public endpoint - get active partners for frontend
     */
    public function publicIndex()
    {
        $partners = Partner::active()->ordered()->get();

        return response()->json($partners);
    }
}
