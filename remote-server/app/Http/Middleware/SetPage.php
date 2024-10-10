<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SetPage
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse) $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $page = $request->input('page') ? (int)$request->input('page') : 1;
        $size = $request->input('size') ? (int)$request->input('size') : 10;
        $request->offsetSet('page', $page);
        $request->offsetSet('size', $size);
        return $next($request);
    }
}
