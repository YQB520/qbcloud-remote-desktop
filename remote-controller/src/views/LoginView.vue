<template>
  <div class="app-login">
    <t-card class="app-login-container" :bordered="false" shadow>
      <div class="app-login-logo">
        <img src="../assets/logo.png" alt="" />
        <h1>社牛远程桌面</h1>
      </div>
      <t-form
        ref="refForm"
        :data="dataForm"
        :rules="rules"
        label-align="top"
        colon
        @submit="submitForm"
      >
        <t-form-item name="username">
          <t-input
            v-model="dataForm.username"
            placeholder="请输入账号"
            size="large"
            clearable
          >
            <template #prefix-icon>
              <user-icon />
            </template>
          </t-input>
        </t-form-item>
        <t-form-item name="password">
          <t-input
            v-model="dataForm.password"
            type="password"
            placeholder="请输入密码"
            size="large"
            clearable
          >
            <template #prefix-icon>
              <lock-on-icon />
            </template>
          </t-input>
        </t-form-item>
        <t-form-item class="app-login-submit">
          <t-button
            :loading="loading"
            theme="primary"
            type="submit"
            size="large"
            block
          >
            立 即 登 录
          </t-button>
        </t-form-item>
      </t-form>
      <div class="app-footer-title">
        Copyright @ 社牛远程桌面 {{ appInfo.version }}.
      </div>
    </t-card>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import { Login } from '@/requests/api'
import { useRouter } from 'vue-router'
import { UserIcon, LockOnIcon } from 'tdesign-icons-vue-next'
import appInfo from '../../package.json'

const rules = {
  username: [
    { required: true, message: '请输入账号', type: 'error', trigger: 'blur' }
  ],
  password: [
    { required: true, message: '请输入密码', type: 'error', trigger: 'blur' }
  ]
}
const router = useRouter()
const loading = ref(false)
const refForm = ref()
const dataForm = reactive({
  username: null,
  password: null
})

const funLogin = async () => {
  loading.value = true
  await Login(dataForm)
    .then((res) => {
      dataForm.password = null
      localStorage.setItem('token', res.token)
      setTimeout(() => {
        router.push('/')
      }, 3000)
    })
    .catch(() => {})
  loading.value = false
}

const submitForm = ({ validateResult }) => {
  if (validateResult === true) {
    funLogin()
  }
}
</script>
<style lang="scss" scoped>
.app-login {
  height: 100vh;
  background-color: var(--td-bg-color-container);
  &-container {
    user-select: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 580px;
    padding: 3rem 2rem;
    .t-card__body {
      padding: 0;
    }
  }
  &-logo {
    display: flex;
    justify-content: center;
    align-items: center;
    margin-bottom: 2rem;
    img {
      width: 38px;
      height: 38px;
    }
    h1 {
      font-size: 28px;
      margin-left: 6px;
    }
  }
  .app-login-submit {
    margin-top: 50px !important;
  }
  .app-footer-title {
    margin-top: 5rem;
    text-align: center;
    color: var(--td-text-color-placeholder);
  }
}
</style>
