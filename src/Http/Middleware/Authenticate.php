<?php

namespace Encore\RedisManager\Http\Middleware;

use Encore\RedisManager\RedisManager;

class Authenticate
{
    public function handle($request, $next)
    {
        return RedisManager::check($request) ? $next($request) : abort(403);
    }
}
