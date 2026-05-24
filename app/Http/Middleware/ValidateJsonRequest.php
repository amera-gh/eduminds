<?php
 
namespace App\Http\Middleware;
 
use Closure;
use Illuminate\Http\Request;
 
/**
 * Validate JSON request and handle malformed JSON
 */
class ValidateJsonRequest
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->isJson()) {
            $json = $request->getContent();
            json_decode($json);
            
            if (json_last_error() !== JSON_ERROR_NONE) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid JSON format',
                    'error' => json_last_error_msg(),
                ], 400);
            }
        }
 
        return $next($request);
    }
}
 