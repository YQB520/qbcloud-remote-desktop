<template>
  <div v-show="props.status === true" class="app-desktop-operate">
    <t-popup trigger="click" :visible="menuVisible">
      <t-button
        class="app-desktop-operate-button"
        size="large"
        shape="circle"
        theme="success"
        tabindex="-1"
        :style="styles"
        @click.stop="onToggle"
        @mousedown.stop="onMousedown"
      >
        <template #icon><t-icon name="brightness" /></template>
      </t-button>
      <template #content>
        <div @click.stop>
          <t-head-menu theme="dark" value="1">
            <t-menu-item
              v-for="item in display"
              :key="item.id"
              :value="item.id"
              @click="$emit('change', item.id)"
            >
              {{ item.name }}
            </t-menu-item>
          </t-head-menu>
        </div>
      </template>
    </t-popup>
  </div>
</template>
<script setup>
import { onMounted, onUnmounted, reactive, ref, computed } from 'vue'

const props = defineProps({
  status: [Boolean, String],
  display: Array
})

defineEmits(['change'])

const menuVisible = ref(false)
let domButton
let isDragging = false

const relative = reactive({
  x: 0,
  y: 0
})

const domElement = reactive({
  top: 0,
  left: 0
})

const styles = computed(() => {
  if (domElement.top !== 0 && domElement.left !== 0) {
    localStorage.setItem('top', domElement.top)
    localStorage.setItem('left', domElement.left)
  }
  return {
    top: `${domElement.top}px`,
    left: `${domElement.left}px`
  }
})

const onToggle = () => {
  if (isDragging) return
  menuVisible.value = !menuVisible.value
}

const onMousemove = (event) => {
  isDragging = true
  menuVisible.value = false
  let realX = event.clientX - relative.x < 0 ? 0 : event.clientX - relative.x
  let realY = event.clientY - relative.y < 0 ? 0 : event.clientY - relative.y
  if (realX + domButton.offsetWidth > document.body.offsetWidth) {
    realX = document.body.offsetWidth - domButton.offsetWidth
  }
  if (realY + domButton.offsetHeight > document.body.offsetHeight) {
    realY = document.body.offsetHeight - domButton.offsetHeight
  }
  domElement.left = realX
  domElement.top = realY
}

const onMouseup = () => {
  window.removeEventListener('mousemove', onMousemove)
  window.removeEventListener('mouseup', onMouseup)
}

const onMousedown = (event) => {
  isDragging = false
  relative.x = event.clientX - domButton.offsetLeft
  relative.x = event.clientY - domButton.offsetTop
  window.addEventListener('mousemove', onMousemove)
  window.addEventListener('mouseup', onMouseup)
}

const resetPosition = () => {
  if (props.status === true) {
    menuVisible.value = false
    if (domButton.offsetLeft < 0) {
      domElement.left = 0
    } else if (
      domButton.offsetLeft + domButton.offsetWidth >
      document.body.offsetWidth
    ) {
      domElement.left = document.body.offsetWidth - domButton.offsetWidth
    }
    if (domButton.offsetTop < 0) {
      domElement.top = 0
    } else if (
      domButton.offsetTop + domButton.offsetHeight >
      document.body.offsetHeight
    ) {
      domElement.top = document.body.offsetHeight - domButton.offsetHeight
    }
  }
}

onMounted(() => {
  let top = localStorage.getItem('top')
  let left = localStorage.getItem('left')
  domButton = document.querySelector('.app-desktop-operate-button')
  if (!top || !left) {
    domElement.top = document.body.clientHeight - 150
    domElement.left = document.body.clientWidth - 50
  } else {
    domElement.top = top
    domElement.left = left
  }
  document.addEventListener('click', (event) => {
    if (props.status === true) {
      event.preventDefault()
      event.stopPropagation()
      menuVisible.value = false
    }
  })
  window.addEventListener('resize', resetPosition)
})

onUnmounted(() => {
  window.removeEventListener('resize', resetPosition)
})
</script>
<style lang="scss">
.app-desktop-operate {
  &-button {
    position: fixed !important;
    z-index: 100;
    opacity: 0.6;
  }
}
</style>
