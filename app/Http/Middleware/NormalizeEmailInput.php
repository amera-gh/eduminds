<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
 
/**
 * Normalize email input to prevent duplicate accounts
 */
class NormalizeEmailInput
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->has('email')) {
            $request->merge([
                'email' => strtolower(trim($request->input('email')))
            ]);
        }
 
        return $next($request);
    }
}