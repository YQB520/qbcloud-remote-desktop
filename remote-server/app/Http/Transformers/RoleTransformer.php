<?php

namespace App\Http\Transformers;

use App\Models\Role;

class RoleTransformer extends BaseTransformer
{
    public function transform(Role $role)
    {
        return [
            'id' => $role->id,
            'nickname' => $role->nickname,
            'level' => $role->level,
            'created_at' => self::dealTime($role->created_at),
            'updated_at' => self::dealTime($role->updated_at)
        ];
    }
}
