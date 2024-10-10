import request from './request'

export const Login = (params) => request.post('/auth/login', params)
export const LoginOut = () => request.post('/auth/logout')
export const OnlineClient = (id) => request.get(`/online/${id}`)
export const GetIceServer = () => request.get('/ice')

export const GetClient = (params) => request.get('/client', { params })
export const AddClient = (params) => request.post('/client', params)
export const DelClient = (id) => request.delete(`/client/${id}`)
export const PassClient = (params) =>
  request.post(`/client/${params.id}`, params)
