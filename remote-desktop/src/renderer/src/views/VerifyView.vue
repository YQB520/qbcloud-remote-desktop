<template>
  <div class="app-accept">
    <t-card
      :title="appInfo.name"
      :bordered="false"
      header-bordered
      hover-shadow
    >
      <div>
        <h3 class="app-accept-title">{{ client.nickname }}</h3>
        <div class="app-accept-body">正在请求控制你的电脑......</div>
        <t-space class="app-accept-button">
          <t-button theme="success" @click="onAccept"> 接受 </t-button>
          <t-button theme="danger" variant="outline" @click="onRefuse">
            拒绝 ( {{ timeNumber }}s )
          </t-button>
        </t-space>
      </div>
      <template #actions>
        <t-button variant="text" shape="circle" @click="onRefuse">
          <template #icon>
            <t-icon name="close"></t-icon>
          </template>
        </t-button>
      </template>
    </t-card>
  </div>
</template>
<script setup>
import { ref } from 'vue'

const appInfo = ref({})
const client = ref({})
const timeNumber = ref(90)
let timer

const appHandle = async () => {
  const res = await window.eleApi.getApp()
  appInfo.value = res
}

appHandle()

const onAccept = () => {
  clearInterval(timer)
  client.value.status = true
  window.eleApi.verifyAction(JSON.parse(JSON.stringify(client.value)))
}

const onRefuse = () => {
  clearInterval(timer)
  client.value.status = false
  window.eleApi.verifyAction(JSON.parse(JSON.stringify(client.value)))
}

const onTimeout = () => {
  clearInterval(timer)
  client.value.status = 'timeout'
  window.eleApi.verifyAction(JSON.parse(JSON.stringify(client.value)))
}

window.eleApi.onVerifyClient((event, value) => {
  client.value = value
  console.log(client.value)
  timer = setInterval(() => {
    timeNumber.value--
    if (timeNumber.value <= 0) {
      onTimeout()
    }
  }, 1000)
})
</script>
<style lang="scss" scoped>
.app-accept {
  text-align: center;
  height: 100vh;
  background-color: var(--td-bg-color-container);
  :deep(.t-card__header) {
    padding: 5px 12px;
    -webkit-app-region: drag;
    .t-button {
      -webkit-app-region: no-drag;
    }
  }
  &-title {
    font-size: 16px;
  }
  &-body {
    font-size: 16px;
    margin-bottom: 2rem;
  }
  &-button {
    margin-bottom: 1rem;
    .t-button {
      width: 100px;
    }
  }
}
</style>
