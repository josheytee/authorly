<?php

namespace App\Traits;

use Inertia\Inertia;
use Illuminate\Http\Request;

trait HandlesResponse
{
    /**
     * Handle a successful response for both API and Inertia requests.
     *
     * @param Request $request
     * @param mixed $data
     * @param string|null $view
     * @param string $message
     * @return mixed
     */
    public function respondWithSuccess(Request $request, $data = [], string $view = null, string $message = 'Operation successful!')
    {
        // Check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'data' => $data,
            ], 200);
        }

        // Inertia response for web (non-API) requests
        return Inertia::render($view ?? 'Dashboard', [
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Handle a failed response for both API and Inertia requests.
     *
     * @param Request $request
     * @param array $errors
     * @param string|null $view
     * @param string $message
     * @return mixed
     */
    public function respondWithError(Request $request, array $errors = [], string $view = null, string $message = 'Operation failed!')
    {
        // Check if it's an API request
        if ($request->wantsJson()) {
            return response()->json([
                'message' => $message,
                'errors' => $errors,
            ], 422);
        }

        // Inertia response for web (non-API) requests
        return Inertia::render($view ?? 'Error', [
            'message' => $message,
            'errors' => $errors,
        ]);
    }

    /**
     * Handle a redirect for Inertia or API responses with custom status codes.
     *
     * @param Request $request
     * @param string $route
     * @param mixed $data
     * @param int $statusCode
     * @return mixed
     */
    public function respondWithRedirect(Request $request, string $route, $data = [], int $statusCode = 302)
    {
        // Handle API request with redirect URL
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Redirecting',
                'data' => $data,
                'redirect' => route($route),
            ], $statusCode);
        }

        // Inertia redirect
        return redirect()->route($route)->with($data);
    }

    /**
     * Respond with a JSON message and set an HTTPOnly cookie.
     *
     * @param string $message
     * @param string $token
     * @param int $minutes
     * @return \Illuminate\Http\JsonResponse
     */
    public function respondWithCookie(string $message, string $token, int $minutes = 120)
    {
        return response()->json(['message' => $message])
            ->cookie('auth_token', $token, $minutes, null, null, false, true); // HTTPOnly cookie
    }
}
