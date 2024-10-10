<template>
  <div class="app-desktop-container">
    <video
      ref="videoRef"
      class="app-desktop-video"
      tabindex="-1"
      autoplay
      muted
      @keydown.prevent="onKeyboard"
      @keyup.prevent="onKeyboard"
      @mousewheel.prevent="onMouseWheel"
      @mousemove="onMouseMove"
      @mousedown="onMouseClick"
      @mouseup="onMouseClick"
    ></video>
    <app-operate-menu
      :status="statusConnect"
      :display="desktops"
      @change="desktopChange"
    />
    <app-desktop-loading
      :progress="timeProgress"
      :status="statusConnect"
      :message="messageConnect"
    />
  </div>
</template>

<script setup>
import { onMounted, reactive, ref } from 'vue'
import { useRoute } from 'vue-router'
import { MessagePlugin } from 'tdesign-vue-next'
import Echo from 'laravel-echo'
import io from 'socket.io-client'
import _throttle from 'lodash/throttle'
import AppOperateMenu from '@/components/AppOperateMenu.vue'
import AppDesktopLoading from '@/components/AppDesktopLoading.vue'
import { ShowUser, ShowClient, GetIceServer } from '@/requests/api'

window.io = io
window.oncontextmenu = () => {
  return false
}
window.addEventListener('beforeunload', (event) => {
  event.preventDefault()
  event.returnValue = ''
})

const route = useRoute()
const echo = ref()
const socket = ref({})
const client = ref({})
const person = ref({})
const socketId = ref()
const videoRef = ref()
const peer = ref({})
const statusConnect = ref(false)
const messageConnect = ref(null)
const rtcChannel = reactive({
  desktop: null,
  mouse: null,
  keyboard: null
})
const timeProgress = ref(99)
const desktops = ref([])
const streamMaps = new Map()
const currentDesktop = ref({})
const iceConfig = ref([
  { urls: 'stun:stun.xten.com:3478' },
  { urls: 'stun:stunserver.org:3478' },
  { urls: 'stun:stun.l.google.com:19302' },
  { urls: 'stun:stun1.l.google.com:19302' },
  { urls: 'stun:stun2.l.google.com:19302' },
  { urls: 'stun:stun3.l.google.com:19302' },
  { urls: 'stun:stun4.l.google.com:19302' }
])

const getInit = async () => {
  let res = await ShowClient(route.params.id)
  client.value = res.data
  res = await ShowUser()
  person.value = res.data
  res = await GetIceServer()
  if (res.data) {
    iceConfig.value.push(res.data.iceServers)
  }
}

const handleSerialize = (data, type = false) => {
  if (type) return JSON.parse(data)
  return JSON.stringify(data)
}

const handleOffer = async (remoteValue) => {
  timeProgress.value = 60
  await peer.value.setRemoteDescription(
    handleSerialize(remoteValue.offer, true)
  )
  const answer = await peer.value.createAnswer()
  await peer.value.setLocalDescription(answer)
  socket.value.whisper('answer', {
    socket_id: socketId.value,
    answer: handleSerialize(answer)
  })
}

const sendCandidate = (event) => {
  if (event.candidate) {
    timeProgress.value = 80
    socket.value.whisper('candidate', {
      socket_id: socketId.value,
      candidate: handleSerialize(event.candidate)
    })
  }
}

const setCandidate = (remoteValue) => {
  peer.value.addIceCandidate(handleSerialize(remoteValue.candidate, true))
}

const desktopChange = (value) => {
  let stream = null
  if (value) {
    stream = streamMaps.get(value)
  } else {
    for (const [key, value] of streamMaps) {
      stream = value
      break
    }
  }
  videoRef.value.srcObject = stream
  videoRef.value.onloadedmetadata = () => {
    videoRef.value.play()
  }
  const currentIndex = desktops.value.findIndex((item) => item.id === stream.id)
  currentDesktop.value = desktops.value[currentIndex]
  currentDesktop.value.offsetX = 0
  for (let i = 0; i < desktops.value.length; i++) {
    if (i === currentIndex) break
    currentDesktop.value.offsetX += desktops.value[i].width
  }
}

const handleRTCChange = () => {
  switch (peer.value.connectionState) {
    case 'new':
    case 'connecting':
      timeProgress.value = 30
      break
    case 'connected':
      timeProgress.value = 100
      statusConnect.value = true
      messageConnect.value = null
      break
    case 'disconnected':
      videoRef.value.srcObject = null
      statusConnect.value = 'error'
      messageConnect.value = '连线已中断'
      break
    case 'closed':
      videoRef.value.srcObject = null
      statusConnect.value = 'error'
      messageConnect.value = '连线已关闭'
      break
    case 'failed':
      videoRef.value.srcObject = null
      statusConnect.value = 'error'
      messageConnect.value = '连线失败'
      break
    default:
      videoRef.value.srcObject = null
      statusConnect.value = 'error'
      messageConnect.value = '连线未知错误'
      break
  }
}

const onDesktopEvent = (data) => {
  desktops.value = data
  desktopChange()
}
const onKeyboardEvent = () => {}

const createPeer = () => {
  statusConnect.value = 'connecting'
  timeProgress.value = 10
  messageConnect.value = '正在连线中......'
  peer.value = new RTCPeerConnection({ iceServers: iceConfig.value })
  peer.value.onconnectionstatechange = () => {
    handleRTCChange()
  }
  peer.value.onicecandidate = (event) => {
    sendCandidate(event)
  }
  peer.value.onaddstream = (event) => {
    streamMaps.set(event.stream.id, event.stream)
  }
  peer.value.ondatachannel = (event) => {
    rtcChannel[event.channel.label] = event.channel
    event.channel.onmessage = (value) => {
      const data = handleSerialize(value.data, true)
      switch (event.channel.label) {
        case 'desktop':
          onDesktopEvent(data)
          break
        case 'keyboard':
          onKeyboardEvent(data)
          break
        default:
          break
      }
    }
  }
}

const handleVerify = (remoteValue) => {
  timeProgress.value = 100
  statusConnect.value = 'error'
  switch (remoteValue.status) {
    case false:
      messageConnect.value = '本次连线有内鬼，对方终止连线'
      break
    case 'timeout':
      messageConnect.value = '本次连线对方长时间未应答，已终止连线'
      break
    case 'password':
      messageConnect.value = '本次连线验证码错误，已终止连线'
      break
    default:
      messageConnect.value = '本次连线未知错误'
      break
  }
}

const socketConnect = () => {
  let timer = null
  socket.value = echo.value.join(`client.presence.${client.value.id}`)
  socket.value
    .here(() => {
      // 加入成功后发起连线
      messageConnect.value = '正在验证设备或等待对方接受连线'
      socketId.value = echo.value.socketId()
      socket.value.whisper('verify', {
        socket_id: socketId.value,
        nickname: person.value.nickname,
        password: client.value.password,
        status: 'verify',
        ice: iceConfig.value
      })
      timer = setInterval(() => {
        timeProgress.value--
        if (timeProgress.value <= 0) {
          clearInterval(timer)
          timeProgress.value = 100
          statusConnect.value = 'error'
          messageConnect.value = '远程设备不在线 / 对方长时间未应答'
          echo.value.leave(client.value.id)
        }
      }, 1000)
    })
    .listenForWhisper('verify', (data) => {
      if (data.socket_id === socketId.value) {
        clearInterval(timer)
        handleVerify(data)
      }
    })
    .listenForWhisper('offer', (data) => {
      if (data.socket_id === socketId.value) {
        clearInterval(timer)
        createPeer(data)
        handleOffer(data)
      }
    })
    .listenForWhisper('candidate', (data) => {
      if (data.socket_id === socketId.value) {
        setCandidate(data)
      }
    })
}

const onKeyboard = (e) => {
  if (statusConnect.value === true) {
    rtcChannel.keyboard.send(handleSerialize({ type: e.type, code: e.code }))
  }
}

const onMouseMove = _throttle((e) => {
  if (statusConnect.value === true) {
    const current = currentDesktop.value
    const width =
      (current.width / e.target.clientWidth) * e.offsetX + current.offsetX
    const height = (current.height / e.target.clientHeight) * e.offsetY
    rtcChannel.mouse.send(
      handleSerialize({
        type: e.type,
        width: width,
        height: height
      })
    )
  }
}, 28)

const onMouseWheel = (e) => {
  if (statusConnect.value === true) {
    rtcChannel.mouse.send(handleSerialize({ type: e.type, value: e.deltaY }))
  }
}

const onMouseClick = (e) => {
  if (statusConnect.value === true) {
    rtcChannel.mouse.send(handleSerialize({ type: e.type, value: e.button }))
  }
}

onMounted(async () => {
  await getInit()
  echo.value = new Echo({
    broadcaster: 'socket.io',
    host: 'http://10.2.0.38:9522',
    bearerToken: localStorage.getItem('token')
  })
  socketConnect()
})
</script>
<style lang="scss" scoped>
.app-desktop-container {
  width: 100%;
  min-height: 100vh;
  position: relative;
  .app-desktop-video {
    cursor: none;
    width: 100%;
    height: 100%;
    border: none;
    margin: 0;
    padding: 0;
  }
}
</style>
