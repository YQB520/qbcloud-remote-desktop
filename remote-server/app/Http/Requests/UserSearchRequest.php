<?php

namespace App\Http\Requests;

class UserSearchRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'bid' => 'nullable|integer',
            'content' => 'nullable|max:30'
        ];
    }

    public function messages()
    {
        return [
            'bid.integer' => '部门类型错误',
            'content.max' => '内容最多30个字符',
        ];
    }
}
