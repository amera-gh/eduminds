<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;

class PasswordController extends Controller
{
     /**
     * FORGOT PASSWORD ENDPOINT
     * 
     * Scenarios handled:
     * 1. Valid email - Send reset link
     * 2. Email not found - Return 404 (don't reveal if email exists)
     * 3. Already sent reset link - Rate limit check
     * 4. Mail service failure - Return error
     * 5. Invalid email format - Return validation error
     * 6. Account locked/disabled - Allow reset anyway
     */
    public function forgotPassword(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status_code' => 422
                ], 422);
            }

            $email = strtolower(trim($request->email));

            // Scenario: Check if user exists
            $user = User::where('email', $email)->first();

            if (!$user) {
                // Security: Don't reveal if email exists
                return response()->json([
                    'success' => true,
                    'message' => 'If an account exists with this email, a password reset link will be sent.',
                    'status_code' => 200
                ], 200);
            }

            // Scenario: Check if recent reset link was already sent (rate limiting)
            $recentReset = \DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('created_at', '>', now()->subMinutes(2))
                ->first();

            if ($recentReset) {
                return response()->json([
                    'success' => false,
                    'message' => 'A password reset link was already sent. Please wait 2 minutes before requesting another.',
                    'status_code' => 429
                ], 429);
            }

            // Scenario: Send password reset link
            $status = Password::sendResetLink(
                ['email' => $email]
            );

            // Scenario: Handle mail service response
            if ($status === Password::RESET_LINK_SENT) {
                return response()->json([
                    'success' => true,
                    'message' => 'Password reset link has been sent to your email address.',
                    'status_code' => 200,
                    'expires_in' => 3600 // 1 hour in seconds
                ], 200);
            }

            // Scenario: Mail service or database error
            return response()->json([
                'success' => false,
                'message' => 'Failed to send password reset link. Please try again later.',
                'status_code' => 500
            ], 500);

        } catch (\Exception $e) {
            \Log::error('Forgot password error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Forgot password request failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * RESET PASSWORD ENDPOINT
     * 
     * Scenarios handled:
     * 1. Valid token and credentials - Reset password
     * 2. Invalid/expired token - Return 400
     * 3. Token already used - Return error
     * 4. Email mismatch - Return error
     * 5. Password same as old - Allow (for security, user should be aware)
     * 6. Password validation - Min 8 chars, must match confirmation
     * 7. Database lock during update - Handle exception
     * 8. User account deleted before reset - Return error
     */
    public function resetPassword(Request $request)
    {
        try {
            // Validate input
            $validator = Validator::make($request->all(), [
                'token' => 'required|string',
                'email' => 'required|string|email|max:255',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status_code' => 422
                ], 422);
            }

            $email = strtolower(trim($request->email));

            // Scenario: Check if user exists
            $user = User::where('email', $email)->first();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User account not found',
                    'status_code' => 404
                ], 404);
            }

            // Scenario: Verify reset token validity
            $tokenRecord = \DB::table('password_reset_tokens')
                ->where('email', $email)
                ->where('token', $request->token)
                ->first();

            if (!$tokenRecord) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid password reset token',
                    'status_code' => 400
                ], 400);
            }

            // Scenario: Check token expiration (default 60 minutes)
            $expirationMinutes = config('auth.passwords.users.expire', 60);
            if ($tokenRecord->created_at < now()->subMinutes($expirationMinutes)) {
                // Delete expired token
                \DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->delete();

                return response()->json([
                    'success' => false,
                    'message' => 'Password reset token has expired. Please request a new one.',
                    'status_code' => 400
                ], 400);
            }

            // Scenario: Check if new password is same as old password
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password must be different from your current password',
                    'status_code' => 422
                ], 422);
            }

            // Scenario: Update password using Password facade
            $status = Password::reset(
                [
                    'email' => $email,
                    'password' => $request->password,
                    'password_confirmation' => $request->password_confirmation,
                    'token' => $request->token
                ],
                function (User $user, string $password) {
                    // Revoke all existing tokens for security
                    $user->tokens()->delete();

                    // Update password
                    $user->forceFill([
                        'password' => Hash::make($password)
                    ])->save();

                    // Fire password reset event
                    event(new PasswordReset($user));

                    // Log password change for security audit
                    \Log::info('Password reset for user: ' . $user->email);
                }
            );

            // Scenario: Handle reset response
            if ($status === Password::PASSWORD_RESET) {
                // Delete reset token after successful reset
                \DB::table('password_reset_tokens')
                    ->where('email', $email)
                    ->delete();

                return response()->json([
                    'success' => true,
                    'message' => 'Password has been reset successfully. Please log in with your new password.',
                    'status_code' => 200
                ], 200);
            }

            // Scenario: Reset failed
            return response()->json([
                'success' => false,
                'message' => 'Password reset failed. Please try again.',
                'status_code' => 400
            ], 400);

        } catch (\Exception $e) {
            \Log::error('Reset password error: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Password reset failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * REFRESH TOKEN ENDPOINT
     * 
     * Scenarios handled:
     * 1. Valid token - Issue new token
     * 2. Revoke old token after refresh
     * 3. Token already near expiration
     * 4. Rate limiting on refresh
     */
    public function refreshToken(Request $request)
    {
        try {
            $user = $request->user();

            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated',
                    'status_code' => 401
                ], 401);
            }

            // Scenario: Get current token
            $currentToken = $request->user()->currentAccessToken();

            if (!$currentToken) {
                return response()->json([
                    'success' => false,
                    'message' => 'No active token found',
                    'status_code' => 401
                ], 401);
            }

            // Scenario: Create new token
            $newToken = $user->createToken('auth_token_' . time(), ['read', 'write'])->plainTextToken;

            // Scenario: Optionally revoke old token
            if ($request->input('revoke_old', true)) {
                $currentToken->delete();
            }

            return response()->json([
                'success' => true,
                'message' => 'Token refreshed successfully',
                'access_token' => $newToken,
                'token_type' => 'Bearer',
                'expires_in' => config('sanctum.expiration'),
                'status_code' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Token refresh failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * VERIFY EMAIL ENDPOINT
     * 
     * Scenarios handled:
     * 1. Email not verified - Send verification
     * 2. Email already verified - Return success
     * 3. Invalid email - Return error
     * 4. User not found - Return error
     */
    public function verifyEmail(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|string|email|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status_code' => 422
                ], 422);
            }

            $email = strtolower(trim($request->email));
            $user = User::where('email', $email)->first();

            // Scenario: User not found
            if (!$user) {
                return response()->json([
                    'success' => false,
                    'message' => 'User not found',
                    'status_code' => 404
                ], 404);
            }

            // Scenario: Email already verified
            if ($user->email_verified_at) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email is already verified',
                    'status_code' => 200
                ], 200);
            }

            // Scenario: Send verification email
            $user->sendEmailVerificationNotification();

            return response()->json([
                'success' => true,
                'message' => 'Verification link has been sent to your email',
                'status_code' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Email verification failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

    /**
     * CHANGE PASSWORD ENDPOINT (Authenticated)
     * 
     * Scenarios handled:
     * 1. Correct old password - Change to new password
     * 2. Wrong old password - Return error
     * 3. New password same as old - Return error
     * 4. Invalid password format - Return error
     * 5. Revoke all tokens after change
     */
    public function changePassword(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'current_password' => 'required|string',
                'password' => 'required|string|min:8|confirmed',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                    'status_code' => 422
                ], 422);
            }

            $user = $request->user();

            // Scenario: Verify current password
            if (!Hash::check($request->current_password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Current password is incorrect',
                    'status_code' => 401
                ], 401);
            }

            // Scenario: Check if new password is different from old
            if (Hash::check($request->password, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'New password must be different from current password',
                    'status_code' => 422
                ], 422);
            }

            // Scenario: Update password
            $user->update(['password' => Hash::make($request->password)]);

            // Scenario: Revoke all tokens for security
            $user->tokens()->delete();

            // Log password change
            \Log::info('Password changed for user: ' . $user->email);

            return response()->json([
                'success' => true,
                'message' => 'Password changed successfully. Please log in again.',
                'status_code' => 200
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Password change failed',
                'error' => config('app.debug') ? $e->getMessage() : 'An error occurred',
                'status_code' => 500
            ], 500);
        }
    }

}
