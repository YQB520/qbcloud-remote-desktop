<?php

namespace App\Http\Requests;

class PermissionRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'menu_id' => 'required|integer',
            'nickname' => 'required|max:30',
            'name' => "required|regex:{$this->regex['alpha'][0]}|max:100",
        ];
    }

    public function messages()
    {
        return [
            'menu_id.required' => '请选择所属菜单',
            'menu_id.integer' => '所属菜单数据错误',
            'nickname.required' => '请输入权限名称',
            'nickname.max' => '权限名称最多30个字符',
            'name.required' => '请输入权限路由',
            'name.regex' => "权限路由{$this->regex['alpha'][1]}",
            'name.max' => '权限路由最多100个字符',
        ];
    }
}
