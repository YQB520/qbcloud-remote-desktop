import { createRouter, createWebHashHistory } from 'vue-router'
import LoginView from '@renderer/views/LoginView.vue'
import IndexView from '@renderer/views/IndexView.vue'
import SystemView from '@renderer/views/SystemView.vue'
import ClientView from '@renderer/views/ClientView.vue'
import VerifyView from '@renderer/views/VerifyView.vue'

const routes = [
  {
    name: 'main',
    path: '/',
    component: IndexView,
    children: [
      { path: '/system', component: SystemView },
      { path: '/client', component: ClientView }
    ]
  },
  { path: '/verify', component: VerifyView },
  { path: '/login', component: LoginView },
  {
    path: '/:callAll(.*)',
    redirect: '/'
  }
]

const router = createRouter({
  history: createWebHashHistory(),
  routes
})

// 导航守卫
router.beforeEach((to, from, next) => {
  const token = localStorage.getItem('token')
  if (!token && to.fullPath !== '/login') {
    next('/login')
    return
  }
  next()
})

export default router
