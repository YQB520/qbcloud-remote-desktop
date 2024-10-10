<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPermission
{
    /**
     * 权限检查 前置中间件
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $user = auth('api')->user();
        if ($user->hasRole('super-admin')) {
            return $next($request);
        }
        $action = $request->route()->getName();
        $permission = $user->can($action);
        if (!$permission) return response(['message' => '无权访问'], 403);
        return $next($request);
    }
}
