<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Models\Student;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Register a new user
     */
    public function register(Request $request)
    {
        try {
            // Validate input - let Laravel handle validation
            $validated = $request->validate([
                'email' => 'required|string|email|max:255|unique:users,email',
                'password' => 'required|string|min:8|confirmed',
            ]);

            // Create new user
            $user = User::create([
                'email' => strtolower(trim($validated['email'])),
                'password' => Hash::make($validated['password']),
            ]);

            // Generate API token
            $token = $user->createToken('auth_token', ['read', 'write'])->plainTextToken;

            return response()->json([
                'success' => true,
                'message' => 'Registration successful',
                'data' => [
                    'user' => new UserResource($user),
                    'profile_completed' => false,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Registration error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Registration failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

    /**
     * Login user with email and password
     */
    public function login(Request $request)
    {
        try {
            // Validate input
            $validated = $request->validate([
                'email' => 'required|string|email',
                'password' => 'required|string|min:8',
            ]);

            // Find user by email
            $user = User::where('email', strtolower(trim($validated['email'])))->first();

            // User not found or password incorrect
            if (!$user || !Hash::check($validated['password'], $user->password)) {
                \Log::warning('Failed login attempt for email: ' . $validated['email']);

                return response()->json([
                    'success' => false,
                    'message' => 'Invalid credentials',
                ], 401);
            }

            // Check if account is disabled
            if ($user->is_active === false) {
                return response()->json([
                    'success' => false,
                    'message' => 'Your account has been disabled',
                ], 403);
            }

            // Revoke old tokens if requested (prevent multiple simultaneous sessions)
            if ($request->boolean('revoke_old_tokens')) {
                $user->tokens()->delete();
            }

            // Update last login timestamp
            $user->update(['last_login_at' => now()]);

            // Generate new token
            $token = $user->createToken('auth_token_' . now()->timestamp, ['read', 'write'])->plainTextToken;

            // Refresh user to get updated last_login_at
            $user->refresh();

            // Query student with ALL relationships (grade uses current_grade_id FK)
            $student = Student::where('user_id', $user->id)
                ->with([
                    'grade',            // BelongsTo Grade via current_grade_id
                    'studentprofile',
                    'skillProgress.skill',
                    'learningTopics',
                ])
                ->first();

            // Inject student into user so UserResource can access it
            $user->setRelation('student', $student);

            return response()->json([
                'success' => true,
                'message' => 'Login successful',
                'data' => [
                    'user' => new UserResource($user),
                    'profile_completed' => $student !== null,
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                ]
            ], 200);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $e->errors(),
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Login error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Login failed',
                'error' => config('app.debug') ? $e->getMessage() : 'Server error',
            ], 500);
        }
    }
    public function logout(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                throw new \Exception('Unauthenticated');
            }

            // Scenario: Check if user exists
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'status_code' => 401
                ], 401);
            }

            // Scenario: Logout current token only or all tokens
            $logoutAll = $request->input('logout_all', false);

            if ($logoutAll) {
                // Revoke all tokens (logout from all devices)
                $tokenCount = $user->tokens()->count();
                $user->tokens()->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Logged out from all devices successfully',
                    'tokens_revoked' => $tokenCount,
                    'status_code' => 200
                ], 200);
            } else {
                // Revoke only current token
                $currentToken = $request->user()->currentAccessToken();

                if ($currentToken) {
                    $currentToken->delete();
                }

                return response()->json([
                    'success' => true,
                    'message' => 'Logged out successfully',
                    'status_code' => 200
                ], 200);
            }

        } catch (\Exception $e) {
            \Log::error('Logout error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Logout failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

     /**
     * Get current user profile
     */
    public function me(Request $request)
    {
        try {
            $user = auth()->user();

            if (!$user) {
                throw new \Exception('Unauthenticated');
            }

            // Query student with ALL relationships
            $student = Student::where('user_id', $user->id)
                ->with([
                    'grade',
                    'studentprofile',
                    'skillProgress.skill',
                    'learningGoals.topic',
                ])
                ->first();

            $user->setRelation('student', $student);

            return response()->json([
                'success' => true,
                'message' => 'User profile retrieved',
                'data' => [
                    'user' => new UserResource($user),
                    'profile_completed' => $student !== null,
                ]
            ], 200);

        } catch (\Exception $e) {
            \Log::error('Get profile error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve profile',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
            ], 500);
        }
    }

}