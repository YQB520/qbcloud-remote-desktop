<template>
  <div class="app-login">
    <t-card class="app-login-container" :bordered="false" hover-shadow>
      <t-form
        ref="refForm"
        :data="dataForm"
        :rules="rules"
        label-align="top"
        colon
        @submit="submitForm"
      >
        <t-form-item name="username" label="账号">
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
        <t-form-item name="password" label="密码">
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
            variant="outline"
            type="submit"
            size="large"
            block
            ghost
          >
            立 即 登 录
          </t-button>
        </t-form-item>
      </t-form>
    </t-card>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import { Login } from '@renderer/requests/api'
import { useRouter } from 'vue-router'
import { UserIcon, LockOnIcon } from 'tdesign-icons-vue-next'

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
        router.push('/system')
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
<style lang="scss">
.app-login {
  height: 100vh;
  background-color: var(--td-bg-color-container);
  &-container {
    position: fixed;
    top: 100px;
    left: 50%;
    transform: translate(-50%);
    width: 380px;
    padding: 3rem 2rem;
    .t-card__body {
      padding: 0;
    }
  }
  .app-login-submit {
    margin-top: 50px !important;
  }
}
</style>
