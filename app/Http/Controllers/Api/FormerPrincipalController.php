<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormerPrincipal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FormerPrincipalController extends Controller
{
    public function index()
    {
        $items = FormerPrincipal::orderBy('order')->get();
        return response()->json($items);
    }

    public function publicIndex()
    {
        $items = FormerPrincipal::orderBy('order')->get();
        return response()->json($items);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'period' => 'nullable|string',
            'years' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'order' => 'nullable|integer'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            $data['photo'] = $request->file('photo')->store('former_principals', 'public');
        }

        $item = FormerPrincipal::create($data);
        return response()->json($item);
    }

    public function show(FormerPrincipal $formerPrincipal)
    {
        return response()->json($formerPrincipal);
    }

    public function update(Request $request, FormerPrincipal $formerPrincipal)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'period' => 'nullable|string',
            'years' => 'nullable|string',
            'photo' => 'nullable|image|max:2048',
            'order' => 'nullable|integer'
        ]);

        $data = $request->all();

        if ($request->hasFile('photo')) {
            if ($formerPrincipal->photo) {
                Storage::disk('public')->delete($formerPrincipal->photo);
            }
            $data['photo'] = $request->file('photo')->store('former_principals', 'public');
        }

        $formerPrincipal->update($data);
        return response()->json($formerPrincipal);
    }

    public function destroy(FormerPrincipal $formerPrincipal)
    {
        if ($formerPrincipal->photo) {
            Storage::disk('public')->delete($formerPrincipal->photo);
        }
        $formerPrincipal->delete();
        return response()->json(['message' => 'Deleted successfully']);
    }
}
