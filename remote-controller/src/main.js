import 'tdesign-vue-next/dist/reset.css'
import 'tdesign-vue-next/es/style/index.css'
import 'animate.css'
import './styles/index.scss'

import { createApp } from 'vue'
import App from './App.vue'
import router from './router/index'
import { createPinia } from 'pinia'

const app = createApp(App)
const pinia = createPinia()

app.use(pinia)
app.use(router)
app.mount('#app')