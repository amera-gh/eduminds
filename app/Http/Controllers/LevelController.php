<?php

namespace App\Http\Controllers;

use App\Models\Level;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LevelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $levels = Level::all();
        return response()->json($levels);
    }

    
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:levels',
            'min_points' => 'required|integer|min:0',
            'max_points' => 'required|integer|min:0',
            'icon' => 'nullable|string',
        ]);

        // Ensure min_points is less than max_points
        if ($validated['min_points'] >= $validated['max_points']) {
            return response()->json(['message' => 'min_points must be less than max_points.'], 422);
        }

        $level = Level::create($validated);

        return response()->json($level, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $level = Level::findOrFail($id);
        return response()->json($level);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $level = Level::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('levels')->ignore($level->id)],
            'min_points' => 'sometimes|required|integer|min:0',
            'max_points' => 'sometimes|required|integer|min:0',
            'icon' => 'nullable|string',
        ]);

        // Ensure min_points is less than max_points if both are provided
        if (isset($validated['min_points']) && isset($validated['max_points']) && $validated['min_points'] >= $validated['max_points']) {
            return response()->json(['message' => 'min_points must be less than max_points.'], 422);
        }

        $level->update($validated);

        return response()->json($level);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $level = Level::findOrFail($id);
        $level->delete();

        return response()->json(null, 204);
    }
}
