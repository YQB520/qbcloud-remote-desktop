<?php

namespace App\Http\Requests;

class RoleRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'nickname' => 'required|max:30',
            'level' => 'required|integer|between:1,8',
        ];
    }

    public function messages()
    {
        return [
            'nickname.required' => '请输入角色名称',
            'nickname.max' => '角色名称最多30个字符',
            'level.required' => '请选择角色等级',
            'level.integer' => '角色等级类型必须为数字',
            'level.between' => '角色等级类型数字为1-8',
        ];
    }
}
