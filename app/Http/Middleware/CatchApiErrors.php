<?php
declare(strict_types=1);

namespace App\Http\Middleware;

use Illuminate\Session\TokenMismatchException;

class CatchApiErrors
{
    public function handle($request, \Closure $next)
    {
        try {
            return $next($request);
        } catch (\Throwable $err) {
            \Log::error((string) $err);
            return response(json_encode([
                'error' => "something went wrong",
            ]), 500, [
                'Content-Type' => 'application/json',
            ]);
        }
    }
}
