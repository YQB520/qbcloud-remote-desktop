<?php

namespace App\Http\Requests;

class ConnectRequest extends BaseRequest
{
    public function rules()
    {
        return [
//            'id' => 'required',
//            'data' => 'required'
        ];
    }

    public function messages()
    {
        return [
//            'id.required' => '请输入主机名'
        ];
    }
}
