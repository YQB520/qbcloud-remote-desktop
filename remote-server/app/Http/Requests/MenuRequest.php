<?php

namespace App\Http\Requests;

class MenuRequest extends BaseRequest
{
    public function rules()
    {
        return [
            'pid' => 'required|integer',
            'name' => 'required|max:30',
            'path' => "required|regex:{$this->regex['route_path'][0]}|max:30",
            'file' => "required|regex:{$this->regex['alpha'][0]}|max:30",
            'icon' => 'nullable|max:30',
            'sort' => 'nullable|integer|max:255'
        ];
    }

    public function messages()
    {
        return [
            'pid.required' => '请选择父级菜单',
            'pid.integer' => '父级菜单数据错误',
            'name.required' => '请输入菜单名称',
            'name.max' => '菜单名称最多30个字符',
            'path.required' => '请输入菜单路径',
            'path.regex' => "菜单路径{$this->regex['route_path'][1]}",
            'path.max' => '菜单路径最多30个字符',
            'file.required' => '请输入菜单文件名',
            'file.regex' => "菜单文件名{$this->regex['alpha'][1]}",
            'file.max' => '菜单文件名最多30个字符',
            'icon.max' => '菜单图标最多30个字符',
            'sort.integer' => '菜单排序只允许输入数字',
            'sort.max' => '菜单排序数字最大255',
        ];
    }
}
