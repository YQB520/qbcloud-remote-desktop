<?php


namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BaseRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    protected $regex = [
        'chinese' => ['/\p{Han}/u', '只允许输入中文'],
        'alpha_underline' => ['/^[a-zA-Z_]*$/', '只允许输入字母和下划线'],
        'alpha' => ['/^[a-zA-Z]*$/', '只允许输入字母'],
        'alpha_symbol' => ['/^[a-zA-Z_@#$%&*.!?,;+=-]*$/', '只允许输入字母和部分特殊符号'],
        'alpha_number' => ['/^[0-9a-zA-Z]*$/', '只允许输入字母和数字'],
        'number_symbol' => ['/^[0-9_@#$%&*.!?,;+=-]*$/', '只允许输入数字和部分特殊符号'],
        'alpha_number_symbol' => ['/^[0-9a-zA-Z_@#$%&*.!?,;+=-]*$/', '只允许输入字母、数字和部分特殊符号'],
        'route_path' => ['/^\/[a-zA-Z0-9_]+(\/[a-zA-Z0-9_]+)*(\.[a-zA-Z0-9]+)?$/', '错误'],
        'alpha_oblique' => ['/^[a-zA-Z\/]*$/', '只允许输入字母和斜杆'],
    ];
}
