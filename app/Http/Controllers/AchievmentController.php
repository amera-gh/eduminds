<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AchievmentController extends Controller
{

    public function index()
    {
        $achievements = Achievement::all();
        return response()->json($achievements);
    }


    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'badge_icon' => 'nullable|string',
            'condition_value' => 'required|integer',
            'xp_reward' => 'required|integer',
        ]);

        $achievement = Achievement::create($validated);

        return response()->json($achievement, 201);
    }


    public function show(string $id)
    {
        $achievement = Achievement::findOrFail($id);
        return response()->json($achievement);
    }


    public function update(Request $request, string $id)
    {
        $achievement = Achievement::findOrFail($id);

        $validated = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255', Rule::unique('achievements')->ignore($achievement->id)],
            'description' => 'sometimes|required|string',
            'badge_icon' => 'nullable|string',
            'condition_value' => 'sometimes|required|integer',
            'xp_reward' => 'sometimes|required|integer',
        ]);

        $achievement->update($validated);

        return response()->json($achievement);
    }

    
    public function destroy(string $id)
    {
        $achievement = Achievement::findOrFail($id);
        $achievement->delete();

        return response()->json(null, 204);
    }
}
