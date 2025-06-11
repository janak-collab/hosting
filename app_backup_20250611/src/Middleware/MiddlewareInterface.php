<?php
namespace App\Middleware;

interface MiddlewareInterface {
    /**
     * Handle the middleware logic
     * 
     * @param array $request Request data
     * @param callable $next Next middleware
     * @return mixed
     */
    public function handle($request, $next);
}
