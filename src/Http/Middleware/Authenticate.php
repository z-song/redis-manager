<?php

namespace Encore\RedisManager\Http\Middleware;

use Encore\RedisManager\RedisManager;

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
        return RedisManager::check($request) ? $next($request) : abort(403);
    }
}
