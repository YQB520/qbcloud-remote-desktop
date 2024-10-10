<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('api.auth', ['except' => ['login']]);
    }

    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), ['username' => 'required', 'password' => 'required']);
        if ($validator->fails()) return response()->json(['message' => '登录失败'], 412);
        $data = [
            'username' => trim($request->input('username')),
            'password' => trim($request->input('password'))
        ];
        $user = User::select('id', 'errors')
            ->where([
                ['status', '=', 1],
                ['errors', '<', 5],
                ['username', '=', $data['username']]
            ])
            ->first();
        if (!$user) return response()->json(['message' => '登录失败'], 412);
        $token = auth('api')->attempt($data);
        if (!$token) {
            $num = 5 - ($user->errors + 1);
            $user->increment('errors');
            return response()->json(['message' => "登录失败，剩余{$num}次"], 412);
        }
        $clientIp = $request->header('CF-Connecting-IP') ?: $request->ip();
        $user->update(['ip' => $clientIp, 'login_at' => now()]);
        return response()->json(['token' => $token, 'message' => '登录成功']);
    }

    public function logout()
    {
        auth('api')->logout();

        return response()->json(['message' => '退出登录成功']);
    }
}
