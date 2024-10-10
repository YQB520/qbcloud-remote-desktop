import { createRouter, createWebHistory } from 'vue-router'
import LoginView from '@/views/LoginView.vue'
import IndexView from '@/views/IndexView.vue'
import DesktopView from '@/views/DesktopView.vue'

const routes = [
  {
    name: 'main',
    path: '/',
    component: IndexView,
    children: [
      // { path: '/connect', component: () => import('@/views/DesktopView.vue') }
    ]
  },
  { path: '/desktop/:id', component: DesktopView },
  { path: '/login', component: LoginView },
  {
    path: '/:callAll(.*)',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

// 导航守卫
router.beforeEach((to, from, next) => {
  // let token = to.query.token
  // if (to.query.token) {
  //   localStorage.setItem('token', token)
  // }
  const token = localStorage.getItem('token')
  if (!token && to.fullPath !== '/login') {
    next('/login')
    return
  }
  next()
})

export default router
