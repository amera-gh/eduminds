<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SkillController extends Controller
{
    public function index()
    {
        $skills = Skill::all();

        return response()->json($skills);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:skills',
            'min_points' => 'required|integer|min:0',
            'max_points' => 'required|integer|min:0',
            'icon' => 'nullable|string',
        ]);

        // Ensure min_points is less than max_points
        if ($validated['min_points'] >= $validated['max_points']) {
            return response()->json(['message' => 'min_points must be less than max_points.'], 422);
        }

        $skill = Skill::create($validated);

        return response()->json($skill, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $skill = Skill::findOrFail($id);

        return response()->json($skill);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $skill = Skill::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('skills')->ignore($skill->id)],
            'min_points' => 'sometimes|required|integer|min:0',
            'max_points' => 'sometimes|required|integer|min:0',
            'icon' => 'nullable|string',
        ]);

        // Ensure min_points is less than max_points if both are provided
        if (isset($validated['min_points']) && isset($validated['max_points']) && $validated['min_points'] >= $validated['max_points']) {
            return response()->json(['message' => 'min_points must be less than max_points.'], 422);
        }

        $skill->update($validated);

        return response()->json($skill);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $skill = Skill::findOrFail($id);
        $skill->delete();

        return response()->json(null, 204);
    }
}
