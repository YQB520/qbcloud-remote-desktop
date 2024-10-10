<?php

namespace App\Http\Requests;

class UserPasswordRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'password' => "required|regex:{$this->regex['alpha_number_symbol'][0]}|between:6,20",
        ];
    }

    public function messages()
    {
        return [
            'password.required' => '请输入密码',
            'password.between' => '密码只允许6-20个字符',
            'password.regex' => "密码{$this->regex['alpha_number_symbol'][1]}",
        ];
    }
}
