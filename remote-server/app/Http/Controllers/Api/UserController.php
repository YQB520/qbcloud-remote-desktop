<?php

namespace App\Http\Controllers\Api;

use App\Http\Requests\UserPasswordRequest;
use App\Models\User;

class UserController extends ApiController
{
    public function index()
    {

    }

    public function show()
    {
        $user = auth('api')->user();
        return $this->RD([
            'id' => $user->id,
            'nickname' => $user->nickname,
            'username' => $user->username
        ]);
    }

    public function password(UserPasswordRequest $request)
    {
        $password = $request->input('password');
        User::where('id', $this->user->id)->update(['password' => bcrypt($password)]);
        return $this->ET();
    }
}
