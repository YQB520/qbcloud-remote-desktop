import regex from './regex'

export const PassRoles = {
  password: [
    { required: true, message: '请输入密码', trigger: 'blur' },
    { min: 6, max: 20, message: '密码必须为6-20个字符', trigger: 'blur' },
    {
      validator: regex.alpha_number_symbol,
      message: '只允许输入字母、数字和部分特殊符号',
      trigger: 'blur'
    }
  ]
}
