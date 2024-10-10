<template>
  <div>
    <t-layout>
      <t-header>
        <t-head-menu theme="dark" value="device">
          <template #logo>
            <div class="app-logo-box">
              <img height="28" src="../assets/logo.png" alt="logo" />
              <h1>社牛远程桌面</h1>
            </div>
          </template>
          <t-menu-item value="device"> 远程设备 </t-menu-item>
          <template #operations>
            <div>
              <t-button variant="text" shape="square" @click="psVisible = true">
                <template #icon>
                  <t-icon name="edit-2" />
                </template>
              </t-button>
              <t-button variant="text" shape="square" @click="logoutAction">
                <template #icon>
                  <t-icon name="poweroff" />
                </template>
              </t-button>
            </div>
          </template>
        </t-head-menu>
      </t-header>
      <t-content>
        <router-view v-slot="{ Component }">
          <keep-alive>
            <component
              :is="$route.path === '/' ? AppLayout : Component"
            ></component>
          </keep-alive>
        </router-view>
      </t-content>
    </t-layout>
    <t-dialog
      v-model:visible="psVisible"
      header="修改密码"
      width="38%"
      :on-confirm="onConfirm"
    >
      <t-input
        v-model="password"
        type="password"
        size="large"
        placeholder="请输入密码"
      >
        <template #prefix-icon>
          <t-icon name="lock-on"></t-icon>
        </template>
      </t-input>
    </t-dialog>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { mainStore } from '@/stores/main'
import { storeToRefs } from 'pinia'
import AppLayout from '@/components/AppLayout.vue'
import { DialogPlugin, MessagePlugin } from 'tdesign-vue-next'
import { EditPassword, LoginOut } from '@/requests/api'

const main = mainStore()
// const { person } = storeToRefs(main)

// main.setPerson()

const psVisible = ref(false)
const password = ref()

const logoutAction = () => {
  const confirmDia = DialogPlugin({
    header: '温馨提示',
    body: '您确认继续退出登录吗？',
    theme: 'warning',
    onConfirm: async () => {
      confirmDia.hide()
      const res = await LoginOut()
      if (res) {
        localStorage.clear()
        window.location.reload()
      }
    }
  })
}

const onConfirm = async () => {
  if (!password.value) {
    MessagePlugin.error('请输入密码')
    return false
  }
  if (password.value.length < 6 || password.value.length > 20) {
    MessagePlugin.error('密码必须为6-20个字符')
    return false
  }
  const check = /^[0-9a-zA-Z_@#$%&*.!?,;+=-]*$/.test(password.value)
  if (!check) {
    MessagePlugin.error('只允许输入字母、数字和部分特殊符号')
    return false
  }
  await EditPassword({ password: password.value })
  password.value = null
  psVisible.value = false
}
</script>
<style lang="scss" scoped>
.app-logo-box {
  display: flex;
  align-items: center;
  h1 {
    font-size: 18px;
    letter-spacing: 3px;
    margin-left: 6px;
  }
}
</style>
