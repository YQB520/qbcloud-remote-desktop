<template>
  <div>
    <t-dialog
      v-model:visible="updaterDialog"
      :close-on-overlay-click="false"
      :prevent-scroll-through="false"
      :footer="false"
      :close-btn="false"
      :confirm-btn="null"
      :cancel-btn="null"
      placement="center"
      width="480px"
      class="app-updater"
    >
      <template #header>
        <div>发现新版本正在更新</div>
      </template>
      <t-progress
        theme="plump"
        :color="{ from: '#626aef', to: '#00A870' }"
        :percentage="progress"
        status="active"
        size="large"
      />
    </t-dialog>
  </div>
</template>
<script setup>
import { MessagePlugin } from 'tdesign-vue-next'
import { ref } from 'vue'

const updaterDialog = ref(false)
const progress = ref(0)

window.eleApi.onUpdater((event, { action, data }) => {
  switch (action) {
    case 'available':
      updaterDialog.value = true
      break
    case 'not-available':
      MessagePlugin.success('没有可用更新')
      break
    case 'progress':
      progress.value = data
      break
    case 'done':
      progress.value = 100
      window.eleApi.updaterAction('install')
      break
    default:
      break
  }
})
</script>
<style lang="scss" scoped>
.app-updater {
  :global(.t-dialog__header-content) {
    justify-content: center;
  }
  .t-progress {
    margin-top: 1.5rem;
  }
}
</style>
