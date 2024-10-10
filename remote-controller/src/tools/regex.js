// 正则校验对象
export const regex = {
  password: (value) => {
    this.alpha_number_symbol(value)
    // regex.alpha_number_symbol(value)
  },
  chinese: (value) => {
    // '只允许输入中文'
    return /^[\u4E00-\u9FA5]*$/.test(value)
  },
  route_path: (value) => {
    // '路径'
    return /^\/[a-zA-Z0-9_]+(\/[a-zA-Z0-9_]+)*(\.[a-zA-Z0-9]+)?$/.test(value)
  },
  alpha_underline: (value) => {
    // '只允许输入字母和下划线'
    return /^[a-zA-Z_]*$/.test(value)
  },
  number: (value) => {
    // '只允许输入数字'
    if (!value) return true
    return /^[0-9]*$/.test(value)
  },
  alpha: (value) => {
    // '只允许输入字母'
    return /^[a-zA-Z]*$/.test(value)
  },
  alpha_symbol: (value) => {
    // '只允许输入字母和部分特殊符号'
    return /^[a-zA-Z_@#$%&*.!?,;+=-]*$/.test(value)
  },
  alpha_number: (value) => {
    // '只允许输入字母和数字'
    return /^[0-9a-zA-Z]*$/.test(value)
  },
  number_symbol: (value) => {
    // '只允许输入数字和部分特殊符号'
    return /^[0-9_@#$%&*.!?,;+=-]*$/.test(value)
  },
  alpha_number_symbol: (value) => {
    // '只允许输入字母、数字和部分特殊符号'
    return /^[0-9a-zA-Z_@#$%&*.!?,;+=-]*$/.test(value)
  },
  domain: (value) => {
    // '请正确输入域名'
    return /^[a-zA-Z0-9][-a-zA-Z0-9]{0,62}(\.[a-zA-Z0-9][-a-zA-Z0-9]{0,62})+\.?/.test(
      value
    )
  },
  mobile: (value) => {
    // '请输入正确的手机号'
    return /^((0\d{2,3}-\d{7,8})|(1[3584]\d{9}))$/.test(value)
  },
  positiveNumber: (value) => {
    // '只能输入正实数并保留2位小数'
    if (!value) return true
    return /^[0-9]+(.[0-9]{2})?$/.test(value)
  },
  blankSpace: (value) => {
    // '不能输入空格符号'
    if (!value) return true
    return /^[^\s]*$/.test(value)
  }
}

export default regex
