<?php

namespace App\Http\Requests;

class ClientRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'pc_name' => 'required|max:100',
            'max_address' => 'required|max:45',
            'ip_address' => 'nullable|max:45'
        ];
    }

    public function messages()
    {
        return [
            'pc_name.required' => '请输入主机名',
            'pc_name.max' => '主机名错误',
            'max_address.required' => '请输入MAC地址',
            'max_address.max' => 'MAC地址错误',
            'ip_address.max' => 'IP地址错误'
        ];
    }
}
