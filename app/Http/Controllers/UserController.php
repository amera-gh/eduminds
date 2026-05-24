<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::all();

        return $users;
    }

    public function store(Request $request)
    {

        $validated = $request->validate([
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'gender' => 'required|in:male,female',
            'birth_date' => 'required',
            'avatar' => 'string|max:255',
        ]);

        $user = new User;

        $user->email = $validated['email'];
        $user->password = Hash::make($validated['password']);
        $user->save();

        $student = new Student;
        $student->user_id = $user->id;
        $student->gender = $validated['gender'];
        $student->birth_date = $validated['birth_date'];
        $student->avatar = $validated['avatar'];
        $student->save();

        return response()->json([$user, $student], 201);
    }

    public function show(string $id)
    {
        $user = User::findOrFail($id);

        return response()->json($user);
    }

    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $validated = $request->validate([
            'email' => ['sometimes', 'required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'sometimes|required|string|min:8',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        }

        $user->update($validated);

        return response()->json($user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return response()->json(null, 204);
    }
}
