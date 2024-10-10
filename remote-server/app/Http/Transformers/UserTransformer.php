<?php

namespace App\Http\Transformers;

use App\Models\User;

class UserTransformer extends BaseTransformer
{
    public function transform(User $user)
    {
        return [
            'id' => $user->id,
            'bid' => $user->bid,
            'dept' => $user->dept->name ?? null,
            'role_id' => $user->roles->pluck('id'),
            'role' => $user->roles->pluck('nickname'),
            'nickname' => $user->nickname,
            'username' => $user->username,
            'ip' => $user->ip,
            'note' => $user->note,
            'status' => $user->status,
            'theme' => $user->theme,
            'login_at' => $user->login_at,
            'created_at' => self::dealTime($user->created_at),
            'updated_at' => self::dealTime($user->updated_at)
        ];
    }

}
