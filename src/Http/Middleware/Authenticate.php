<?php

namespace Poowf\Otter\Http\Middleware;

use Poowf\Otter\Otter;

class Authenticate
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return \Illuminate\Http\Response|null
     */
    public function handle($request, $next)
    {
        return Otter::check($request) ? $next($request) : abort(403);
    }
}