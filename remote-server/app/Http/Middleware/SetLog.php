<?php

namespace App\Http\Middleware;

use App\Models\Log;
use Closure;
use Illuminate\Http\Request;

class SetLog
{
    /**
     * 操作日志 后置中间件
     *
     * @param Request $request
     * @param Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);
        $array = $request->all();
        $array['method'] = $request->method();
        $array['request_url'] = $request->url();
        Log::create([
            'uid' => auth('api')->user()->uid,
            'route' => $request->route()->getName(),
            'data_json' => $array
        ]);
//        if (env('APP_ENV') === 'production') {
//            Log::create([
//                'uid' => auth('api')->user()->uid,
//                'route' => $request->route()->getName(),
//                'data_json' => $array
//            ]);
//        }
        return $response;
    }
}
