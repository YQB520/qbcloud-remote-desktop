<template>
  <div class="app-form-dialog">
    <t-dialog
      v-model:visible="visible"
      :close-on-overlay-click="false"
      :prevent-scroll-through="false"
      :close-btn="false"
      :confirm-btn="null"
      :width="width"
      top="80px"
    >
      <template #header>
        <div>{{ title }}</div>
        <t-button shape="circle" variant="text" @click="visible = false">
          <template #icon><t-icon name="multiply" size="30px" /></template>
        </t-button>
      </template>
      <template #footer>
        <t-space>
          <t-button
            variant="outline"
            theme="default"
            size="large"
            @click="visible = false"
          >
            取 消
          </t-button>
          <t-button
            theme="primary"
            size="large"
            :loading="loading"
            @click="submitAction"
          >
            确 定
          </t-button>
        </t-space>
      </template>
      <div>
        <slot> </slot>
      </div>
    </t-dialog>
  </div>
</template>
<script setup>
import { ref } from 'vue'

const visible = defineModel('visible')

const loading = ref(false)

const props = defineProps({
  width: {
    type: String,
    default: '31vw'
  },
  title: {
    type: [String, Number],
    required: true
  },
  confirm: {
    type: Function,
    default() {}
  }
})

const submitAction = async () => {
  loading.value = true
  let result = false
  try {
    result = await props.confirm()
  } catch (_err) {}
  loading.value = false
  if (result === false) return
  visible.value = false
}
</script>
