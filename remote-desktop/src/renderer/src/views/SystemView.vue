<template>
  <div>
    <t-card :bordered="false">
      <t-alert class="app-alert" theme="warning">
        使用时可以关闭显示器但请勿锁屏、验证码为空则需要本设备允许
        <template #icon>
          <t-icon name="info-circle"></t-icon>
        </template>
      </t-alert>
      <t-row class="app-system">
        <t-col :span="7">
          <div class="app-system-title">本设备识别码</div>
          <div class="app-system-id">
            <t-link theme="primary" size="large" hover="color" @click="copyId">
              {{ client.id.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }}
            </t-link>
          </div>
        </t-col>
        <t-col :span="5">
          <div class="app-system-title">本设备验证码</div>
          <div class="app-system-password">
            <div v-if="showInput === false" class="app-system-password-hide">
              <t-link
                theme="primary"
                size="large"
                hover="color"
                @click="showInput = true"
              >
                {{ '*********'.replace(/(.{3})/g, '$1 ') }}
              </t-link>
              <t-button
                theme="default"
                variant="text"
                shape="circle"
                @click="copyPassword"
              >
                <template #icon>
                  <t-icon name="copy"></t-icon>
                </template>
              </t-button>
            </div>
            <t-input
              v-else
              v-model="client.password"
              placeholder=""
              :maxlength="6"
              autofocus
              borderless
              show-limit-number
              @blur="blurPassword"
            />
          </div>
        </t-col>
      </t-row>
      <t-divider />
      <t-list>
        <t-list-item>
          局域网IP
          <template #action>
            <t-link theme="primary" hover="color">
              {{ system.address }}
            </t-link>
          </template>
        </t-list-item>
        <t-list-item>
          主机名
          <template #action>
            <t-link theme="primary" hover="color">
              {{ system.hostname }}
            </t-link>
          </template>
        </t-list-item>
        <t-list-item>
          操作系统
          <template #action>
            <t-link theme="primary" hover="color">
              {{ system.version }}
            </t-link>
          </template>
        </t-list-item>
        <t-list-item>
          系统类型
          <template #action>
            <t-link theme="primary" hover="color">
              {{ system.arch }}
            </t-link>
          </template>
        </t-list-item>
        <t-list-item>
          系统版本
          <template #action>
            <t-link theme="primary" hover="color">
              {{ system.release }}
            </t-link>
          </template>
        </t-list-item>
      </t-list>
    </t-card>
  </div>
</template>
<script setup>
import { ref } from 'vue'
import { MessagePlugin } from 'tdesign-vue-next'
import { mainStore } from '@renderer/stores/main'
import { storeToRefs } from 'pinia'
import { AddClient, PassClient } from '@renderer/requests/api'

const main = mainStore()
const showInput = ref(false)
const { system, client } = storeToRefs(main)

const handleId = async (data) => {
  const res = await AddClient(data)
  main.setClient(res.data)
}

const handleSystem = async () => {
  const res = await window.eleApi.getSystem()
  main.setSystem(res)
  handleId({
    pc_name: res.hostname,
    max_address: res.mac,
    ip_address: res.address
  })
}

handleSystem()

const blurPassword = () => {
  showInput.value = false
  PassClient({
    id: client.value.id,
    password: client.value.password
  })
}

const copyId = async () => {
  await window.eleApi.copyAction(client.value.id)
  MessagePlugin.success('已复制 识别码')
}

const copyPassword = async () => {
  if (!client.value.password) {
    MessagePlugin.warning('未设置验证码')
    return
  }
  await window.eleApi.copyAction(client.value.password)
  MessagePlugin.success('已复制 验证码')
}
</script>
<style lang="scss" scoped>
.app-alert {
  margin-bottom: 15px;
}
.t-divider {
  margin: 18px 0;
}
.app-system {
  height: 70px;
  a {
    font-size: 28px;
    font-weight: 900;
  }
  &-title {
    font-size: 18px;
  }
  &-id {
    margin-top: 12px;
  }
  &-password {
    margin-top: 10px;
    &-hide {
      display: flex;
      align-items: center;
    }
    a {
      margin-top: 5px;
      margin-right: 1rem;
    }
    input {
      font-size: 28px;
      font-weight: 900;
    }
  }
}
</style>
