<?php

namespace App\Http\Requests;

class PermissionSearchRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'menu' => 'nullable|integer',
            'content' => 'nullable|max:30',
        ];
    }

    public function messages()
    {
        return [
            'menu.integer' => '菜单类型错误',
            'content.max' => '其他内容最多30个字符',
        ];
    }
}
