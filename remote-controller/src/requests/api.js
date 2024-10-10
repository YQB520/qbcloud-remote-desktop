import request from './request'

export const Login = (params) => request.post('/auth/login', params)
export const LoginOut = () => request.post('/auth/logout')

export const EditPassword = (params) => request.post('/user/password', params)
export const ShowUser = () => request.get('/user/show')
export const OnlineClient = (id) => request.get(`/online/${id}`)
export const GetIceServer = () => request.get('/ice')

export const ShowClient = (id) => request.get(`/client/${id}`)
export const GetClient = (params) => request.get('/client', { params })
export const DelClient = (id) => request.delete(`/client/${id}`)
