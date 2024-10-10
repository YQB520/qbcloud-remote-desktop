<?php

namespace App\Http\Transformers;

use App\Models\Permission;

class PermissionTransformer extends BaseTransformer
{
    public function transform(Permission $permission)
    {
        return [
            'id' => $permission->id,
            'name' => $permission->name,
            'menu_id' => $permission->menu_id,
            'menu_name' => $permission->menu->name ?? null,
            'parent' => $permission->menu->parent->name ?? null,
            'nickname' => $permission->nickname,
            'created_at' => self::dealTime($permission->created_at),
            'updated_at' => self::dealTime($permission->updated_at)
        ];
    }
}
