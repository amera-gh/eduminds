<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProfileController extends Controller
{
    public function completeProfile(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                throw new \Exception('Unauthenticated');
            }

            // Check if profile already completed
            if (Student::where('user_id', $user->id)->exists()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Profile already completed',
                ], 409);
            }

            // Validate input
            $validated = $request->validate([
                'name' => 'required|string|min:2|max:255',
                'gender' => 'required|in:male,female',
                'birth_date' => 'nullable|date|before:today',
                'grade_id' => 'required|exists:grades,id',
                'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',

                // Skills — must belong to the chosen grade
                'skills' => 'nullable|array',
                'skills.*' => [
                    'integer',
                    'exists:skills,id',
                    function ($attr, $value, $fail) use ($request) {
                        $belongs = \App\Models\Skill::where('id', $value)
                            ->where('grade_id', $request->input('grade_id'))
                            ->exists();
                        if (!$belongs) {
                            $fail("Skill #{$value} does not belong to the selected grade.");
                        }
                    },
                ],

                // Learning topics
                'learning_topics' => 'nullable|array',
                'learning_topics.*' => 'integer|exists:learning_topics,id',
            ]);

            DB::beginTransaction();

            try {
                // ── 1. Avatar upload ──────────────────────────────────────
                $avatar = null;
                if ($request->hasFile('avatar')) {
                    $avatar = $request->file('avatar')
                        ->store('students/avatars', 'public');
                }

                // ── 2. Create student ─────────────────────────────────────
                $student = Student::create([
                    'user_id' => $user->id,
                    'name' => $validated['name'],
                    'gender' => $validated['gender'],
                    'birth_date' => $validated['birth_date'] ?? null,
                    'current_grade_id' => $validated['grade_id'],
                    'avatar' => $avatar,
                ]);

                // ── 3. Create initial game profile ────────────────────────
                $student->studentprofile()->create([
                    'current_level_id' => 1,
                    'current_points' => 0,
                    'longest_streak' => 0,
                    'total_games_played' => 0,
                ]);

                // ── 4. Seed skill progress rows ───────────────────────────
                if (!empty($validated['skills'])) {
                    $skillRows = collect($validated['skills'])->map(fn($skillId) => [
                        'student_id' => $student->id,
                        'skill_id' => $skillId,
                        'status' => 'not_started',
                        'score' => 0,
                        'attempts_count' => 0,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ])->toArray();

                    \App\Models\StudentSkillProgress::insert($skillRows);
                }

                // ── 5. Seed learning goals (priority = array index) ───────
                if (!empty($validated['learning_topics'])) {
                    $goalRows = collect($validated['learning_topics'])
                        ->values()
                        ->map(fn($topicId, $index) => [
                            'student_id' => $student->id,
                            'learning_topic_id' => $topicId,
                            'priority' => $index,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ])->toArray();

                    \App\Models\StudentLearningTopic::insert($goalRows);
                }

                DB::commit();

            } catch (\Exception $e) {
                DB::rollBack();

                if ($avatar ?? false) {
                    \Storage::disk('public')->delete($avatar);
                }

                throw $e;
            }

            // ── Load all relations for response ───────────────────────────
            $student->load([
                'grade',
                'studentprofile',
                'skillProgress.skill',
                'learningTopics',
            ]);
            $user->setRelation('student', $student);

            return response()->json([
                'success' => true,
                'message' => 'Profile completed successfully',
                'data' => [
                    'user' => new UserResource($user),
                    'profile_completed' => true,
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Complete profile error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to complete profile',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }
}
