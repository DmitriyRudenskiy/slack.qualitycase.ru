<?php
namespace App\Http\Middleware;

use Closure;
use RuntimeException;
use InvalidArgumentException;

class CheckToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (empty(env('ACCESS_TOKEN'))) {
            throw new RuntimeException();
        }

        if (empty($request->accessToken) || $request->accessToken != env('ACCESS_TOKEN')) {
            throw new InvalidArgumentException();
        }

        return $next($request);
    }
}