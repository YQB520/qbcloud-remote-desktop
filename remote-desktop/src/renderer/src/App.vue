<template>
  <div>
    <t-head-menu
      v-if="$route.path !== '/accept'"
      class="app-desktop-operate"
      theme="light"
    >
      <div class="app-desktop-operate-logo">
        <t-avatar
          shape="round"
          image="http://10.2.0.38:9522/storage/logo.png"
        ></t-avatar>
        <h1>{{ appInfo.name }} v{{ appInfo.version }}</h1>
      </div>
      <template #operations>
        <div class="app-desktop-operate-menu">
          <t-dropdown trigger="click">
            <t-button title="设置" variant="text" tabindex="-1">
              <template #icon>
                <t-icon size="20" name="view-list" />
              </template>
            </t-button>
            <template #dropdown>
              <t-dropdown-menu>
                <t-dropdown-item
                  value="update"
                  divider
                  @click="settingHandler('update')"
                >
                  <t-button size="small" variant="text" tabindex="-1">
                    检查更新
                    <template #icon>
                      <t-icon name="refresh" />
                    </template>
                  </t-button>
                </t-dropdown-item>
                <t-dropdown-item
                  value="logout"
                  @click="settingHandler('logout')"
                >
                  <t-button size="small" variant="text" tabindex="-1">
                    退出登录
                    <template #icon>
                      <t-icon name="logout" />
                    </template>
                  </t-button>
                </t-dropdown-item>
              </t-dropdown-menu>
            </template>
          </t-dropdown>
          <t-button
            title="最小化"
            variant="text"
            tabindex="-1"
            @click="onDesktop(true)"
          >
            <template #icon>
              <t-icon size="20" name="remove" />
            </template>
          </t-button>
          <t-button
            title="关闭"
            variant="text"
            tabindex="-1"
            @click="onDesktop(false)"
          >
            <template #icon>
              <t-icon size="20" name="close" />
            </template>
          </t-button>
        </div>
      </template>
    </t-head-menu>
    <router-view></router-view>
  </div>
</template>
<script setup>
import { ref } from 'vue'
// import Logo from '../../../resources/logo.png'

const appInfo = ref({})

const appHandle = async () => {
  const res = await window.eleApi.getApp()
  appInfo.value = res
}

appHandle()

const onDesktop = (type) => {
  window.eleApi.operateDesktop(type)
}

const settingHandler = (value) => {
  switch (value) {
    case 'update':
      window.eleApi.updaterAction('check')
      break
    case 'logout':
      break
    default:
      break
  }
}
</script>
