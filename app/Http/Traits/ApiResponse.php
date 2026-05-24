<?php

namespace App\Http\Traits;

trait ApiResponse
{
     /**
     * Return a success response
     */
    protected function success($data = null, $message = 'Success', $code = 200)
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $code);
    }

    /**
     * Return an error response
     */
    protected function error($message = 'Error', $errors = null, $code = 400)
    {
        return response()->json([
            'success' => false,
            'message' => $message,
            'errors' => $errors,
        ], $code);
    }

    /**
     * Return validation error response
     */
    protected function validationError($errors)
    {
        return $this->error('Validation failed', $errors, 422);
    }

    /**
     * Return unauthorized error
     */
    protected function unauthorized($message = 'Unauthorized')
    {
        return $this->error($message, null, 401);
    }

    /**
     * Return not found error
     */
    protected function notFound($message = 'Resource not found')
    {
        return $this->error($message, null, 404);
    }

    /**
     * Return conflict error
     */
    protected function conflict($message = 'Conflict')
    {
        return $this->error($message, null, 409);
    }
}
