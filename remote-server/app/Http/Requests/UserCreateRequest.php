<?php

namespace App\Http\Requests;

class UserCreateRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'nickname' => 'required|between:2,30',
            'username' => "required|regex:{$this->regex['alpha_number'][0]}|between:5,30",
            'password' => "required|regex:{$this->regex['alpha_number_symbol'][0]}|between:6,20",
            'bid' => 'required|integer',
            'role_id' => 'required|array',
            'note' => 'nullable|max:30',
        ];
    }

    public function messages()
    {
        return [
            'nickname.required' => '请输入昵称',
            'nickname.between' => '昵称只允许2-30个字符',
            'username.required' => '请输入用户名',
            'username.between' => '用户名只允许5-30个字符',
            'username.regex' => "用户名{$this->regex['alpha_number'][1]}",
            'password.required' => '请输入密码',
            'password.between' => '密码只允许6-20个字符',
            'password.regex' => "密码{$this->regex['alpha_number_symbol'][1]}",
            'bid.required' => '请选择所属部门',
            'bid.integer' => '所属部门数据错误',
            'role_id.required' => '请选择所属角色',
            'role_id.array' => '所属角色数据错误',
            'note.max' => '备注最多30个字符',
        ];
    }
}
