<template>
  <div v-if="status !== true" class="app-progress">
    <t-progress
      v-if="status === 'connecting'"
      class="app-progress-connect"
      theme="plump"
      :percentage="30"
      size="large"
      status="active"
      :color="{ from: '#0052D9', to: '#00A870' }"
    />
    <t-progress
      v-else-if="status === false"
      theme="circle"
      :percentage="progress"
      size="large"
      :label="`${progress}`"
      status="active"
    />
    <t-progress
      v-else
      class="app-progress-tip"
      theme="circle"
      :percentage="100"
      size="large"
      status="warning"
      color="#d54941"
    />
    <t-alert
      v-if="message"
      class="app-progress-alert"
      :theme="status === 'connecting' || status === false ? 'success' : 'error'"
    >
      {{ message }}
      <template #icon>
        <t-icon name="info-circle"></t-icon>
      </template>
    </t-alert>
  </div>
</template>
<script setup>
defineProps({
  status: [Boolean, String],
  progress: Number,
  message: [String, null]
})
</script>
<style lang="scss" scoped>
.app-progress {
  height: 100%;
  width: 100%;
  position: fixed;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  z-index: 1;
  background-color: var(--td-mask-disabled);
  user-select: none;
  .t-progress {
    position: fixed;
    z-index: 2;
    top: 38%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  &-connect {
    width: 60%;
  }
  &-alert {
    position: fixed;
    z-index: 3;
    bottom: 40%;
    left: 50%;
    transform: translate(-50%, -50%);
  }
  &-tip {
    :deep(.t-progress__icon) {
      color: var(--td-error-color);
    }
  }
}
</style>
