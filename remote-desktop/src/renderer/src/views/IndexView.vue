<template>
  <div class="app-main">
    <t-layout>
      <t-aside class="app-menu">
        <t-menu
          v-model="menuActive"
          theme="light"
          width="180px"
          @change="menuChange"
        >
          <template #logo>
            <div class="app-user">
              <t-avatar size="38px">
                <template #icon>
                  <t-icon name="user" />
                </template>
              </t-avatar>
              <div class="app-user-info">
                <div>娃哈哈娃哈哈娃哈哈娃哈哈娃哈哈</div>
                <div>旗舰版</div>
              </div>
            </div>
          </template>
          <t-menu-item value="/system">
            <template #icon>
              <t-icon name="swap" />
            </template>
            远程控制
          </t-menu-item>
          <t-menu-item value="/client">
            <template #icon>
              <t-icon name="server" />
            </template>
            远程设备
          </t-menu-item>
        </t-menu>
      </t-aside>
      <t-layout>
        <t-header class="app-header">
          <t-input
            v-model="connectId"
            class="app-header-item"
            placeholder="远程设备识别码"
            :format="strFormat"
            :maxlength="9"
            align="center"
            show-limit-number
          />
          <t-button class="app-header-item" theme="success" @click="onConnect">
            快速连接
          </t-button>
        </t-header>
        <t-content class="app-content">
          <router-view v-slot="{ Component }">
            <keep-alive>
              <component :is="Component"></component>
            </keep-alive>
          </router-view>
        </t-content>
      </t-layout>
    </t-layout>
    <updater-view />
  </div>
</template>
<script setup>
import { ref, onMounted, watch } from 'vue'
import { useRouter } from 'vue-router'
import { MessagePlugin } from 'tdesign-vue-next'
import { mainStore } from '@renderer/stores/main'
import { storeToRefs } from 'pinia'
import io from 'socket.io-client'
import Echo from 'laravel-echo'
import UpdaterView from '@renderer/components/UpdaterView.vue'
import { OnlineClient, GetIceServer } from '@renderer/requests/api'

window.io = io
const main = mainStore()
const router = useRouter()
const menuActive = ref('/system')
const connectId = ref()
const socket = ref({})
const { client } = storeToRefs(main)
const desktops = ref([])
const streamMaps = new Map()
const peerMaps = new Map()

const menuChange = (value) => {
  router.push(value)
}

const strFormat = (value) => {
  if (value) {
    return value.replace(/(\d)(?=(?:\d{3})+$)/g, '$1 ')
  }
  return value
}

const handleSerialize = (data, type = false) => {
  if (type) return JSON.parse(data)
  return JSON.stringify(data)
}

const getScreenStream = async () => {
  const sources = await window.eleApi.getDesktop(true)
  for (let i = 0; i < sources.length; i++) {
    await window.eleApi.getDesktop(i)
    const stream = await navigator.mediaDevices.getDisplayMedia({
      video: true
      // frameRate: 300 // 视频轨道的帧速率，以每秒帧数为单位
    })
    streamMaps.set(stream.id, stream)
    desktops.value.push({
      id: stream.id,
      name: sources[i].name,
      width: sources[i].width,
      height: sources[i].height
    })
  }
}

const handleOffer = async (peer, ctrlValue) => {
  const offer = await peer.createOffer()
  await peer.setLocalDescription(offer)
  ctrlValue.offer = handleSerialize(offer)
  socket.value.whisper('offer', ctrlValue)
}

const handleAnswer = async (ctrlValue) => {
  if (ctrlValue.socket_id && ctrlValue.answer) {
    const peer = peerMaps.get(ctrlValue.socket_id)
    await peer.setRemoteDescription(handleSerialize(ctrlValue.answer, true))
  }
}

const sendCandidate = (event, ctrlValue) => {
  if (event.candidate) {
    ctrlValue.candidate = handleSerialize(event.candidate)
    socket.value.whisper('candidate', ctrlValue)
  }
}

const setCandidate = (value) => {
  if (value.socket_id && value.candidate) {
    const peer = peerMaps.get(value.socket_id)
    peer.addIceCandidate(handleSerialize(value.candidate, true))
  }
}

const handleRTCChange = (value) => {
  const peer = peerMaps.get(value.socket_id)
  switch (peer.connectionState) {
    case 'new':
    case 'connecting':
      // 连线中...
      break
    case 'connected':
      // isConnect.value = true
      break
    case 'disconnected':
      MessagePlugin.error('连线已中断')
      break
    case 'closed':
      MessagePlugin.error('连线已关闭')
      break
    case 'failed':
      MessagePlugin.error('连线失败')
      break
    default:
      MessagePlugin.error('连线未知错误')
      break
  }
}

const createPeer = async (ctrlValue) => {
  console.log(ctrlValue)
  const peer = new RTCPeerConnection({ iceServers: ctrlValue.ice })
  peerMaps.set(ctrlValue.socket_id, peer)
  desktops.value.forEach((item) => {
    const streams = streamMaps.get(item.id)
    for (const track of streams.getTracks()) {
      peer.addTrack(track, streams)
    }
  })
  peer.onconnectionstatechange = () => {
    handleRTCChange(ctrlValue)
  }
  peer.onicecandidate = (event) => {
    sendCandidate(event, ctrlValue)
  }
  const desktopChannel = peer.createDataChannel('desktop')
  const mouseChannel = peer.createDataChannel('mouse')
  const keyboardChannel = peer.createDataChannel('keyboard')
  desktopChannel.onopen = () => {
    desktopChannel.send(handleSerialize(desktops.value))
  }
  mouseChannel.onmessage = (value) => {
    window.eleApi.handleMouse(handleSerialize(value.data, true))
  }
  keyboardChannel.onmessage = (value) => {
    window.eleApi.handleKeyboard(handleSerialize(value.data, true))
  }
  await handleOffer(peer, ctrlValue)
}

const onConnect = async () => {
  if (!connectId.value) {
    MessagePlugin.error('请输入远程设备识别码')
    return false
  }
  if (connectId.value === client.value.id) {
    MessagePlugin.error('不允许远程本设备')
    return false
  }
  const res = await OnlineClient(connectId.value)
  if (!res.data) {
    MessagePlugin.error('远程设备不在线')
    return false
  }
  window.eleApi.startConnect({
    id: connectId.value,
    token: localStorage.getItem('token')
  })
}

const handleVerify = (value) => {
  // 验证连线
  if (!client.value.password) {
    window.eleApi.createAccept(value)
    return
  }
  if (value.password.toString() === client.value.password) {
    createPeer(value)
    return
  }
  // 验证码错误
  value.status = 'password'
  socket.value.whisper('verify', value)
}

window.eleApi.onAccredit((event, value) => {
  // status === true false timeout password verify
  if (value.status === true) {
    createPeer(value)
    return
  }
  socket.value.whisper('verify', value)
})

const onSocket = (clientId) => {
  const echo = new Echo({
    broadcaster: 'socket.io',
    host: 'http://10.2.0.38:9522',
    bearerToken: localStorage.getItem('token'),
    auth: { headers: { App: true } }
  })
  socket.value = echo.join(`client.presence.${clientId}`)
  socket.value
    .joining((user) => {
      console.log(user)
    })
    .leaving((user) => {
      console.log(user)
    })
    .listenForWhisper('verify', (data) => {
      handleVerify(data)
    })
    .listenForWhisper('offer', (data) => {
      createPeer(data)
    })
    .listenForWhisper('answer', (data) => {
      handleAnswer(data)
    })
    .listenForWhisper('candidate', (data) => {
      setCandidate(data)
    })
}

const watchCallback = async (clientId) => {
  if (clientId) {
    await getScreenStream()
    onSocket(clientId)
  }
}

watch(() => client.value.id, watchCallback)

onMounted(() => {})

router.push('/system')
</script>
<style lang="scss" scoped>
.app-main {
  height: calc(100vh - 32px);
  margin-top: 32px;

  .app-menu {
    width: 180px;
    height: calc(100vh - 32px);

    .app-user {
      display: flex;
      align-items: center;
      margin-left: 12px;

      &-info {
        margin-left: 6px;
        font-size: 15px;
        line-height: 18px;
        color: var(--td-text-color-secondary);
        width: 116px;

        div {
          text-overflow: ellipsis;
          white-space: nowrap;
          overflow: hidden;
        }

        div:last-child {
          font-size: 12px;
        }
      }
    }
  }

  .app-header {
    display: flex;
    align-items: center;
    justify-content: space-around;
    padding: 0 30px;
    box-sizing: border-box;

    &-item {
      min-width: 120px;
    }
  }

  .app-header-item + .app-header-item {
    margin-left: 20px;
  }

  .app-content {
    padding: 12px;
    box-sizing: border-box;
  }
}
</style>
