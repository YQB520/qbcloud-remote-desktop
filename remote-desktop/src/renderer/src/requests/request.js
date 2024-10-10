// 拦截器
import axios from 'axios'
import { MessagePlugin } from 'tdesign-vue-next'

let URL = 'https://x.xxx.com/api' // 正式

if (process.env.NODE_ENV === 'development') {
  URL = 'http://10.2.0.38:9522/api'
}

const instance = axios.create({
  baseURL: URL,
  timeout: 60000 * 2
})

instance.interceptors.request.use(
  (config) => {
    const token = localStorage.getItem('token')
    if (token) config.headers.Authorization = `Bearer ${token}`
    return config
  },
  (error) => {
    return Promise.reject(error)
  }
)

const errorItems = (errors) => {
  for (const key in errors) {
    MessagePlugin.error(errors[key][0])
    break
  }
}

let isLoginInvalid = false

const loginFailure = () => {
  if (isLoginInvalid) return
  isLoginInvalid = true
  localStorage.removeItem('token')
  MessagePlugin.warning({
    content: '登录失效',
    onClose: () => {
      isLoginInvalid = false
      window.location.reload()
    }
  })
}

instance.interceptors.response.use(
  (config) => {
    if (config.data.message) {
      MessagePlugin.success(config.data.message)
    }
    return config.data
  },
  async (error) => {
    const { code, response } = error

    if (code === 'ECONNABORTED') {
      MessagePlugin.error('请求超时')
      return Promise.reject(error)
    }

    const { status, data } = response

    switch (status) {
      case 401:
        loginFailure()
        break
      case 403:
      case 406:
        MessagePlugin.warning(data.message)
        break
      case 422:
        errorItems(data.errors)
        break
      case 429:
        MessagePlugin.warning('请勿频繁刷新，稍后再试')
        break
      case 500:
        MessagePlugin.error(data.message)
        break
      default:
        MessagePlugin.error(error.message)
        break
    }
    return Promise.reject(error)
  }
)

export default instance
