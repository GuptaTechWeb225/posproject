<?php

namespace App\Http\Middleware;

use Illuminate\Http\Request;
use Closure;


class XssProtectionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $input = array_filter($request->all());

        array_walk_recursive($input, function (&$input) {
            if (is_string($input)) {
                $input = clean($input);
            }
        });

        $request->merge($input);

        return $next($request);
    }
}