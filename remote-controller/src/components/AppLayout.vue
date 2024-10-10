<template>
  <div class="app-page">
    <div class="app-grid-container">
      <t-card
        v-for="item in clientData"
        :key="item.id"
        class="app-grid-container-item"
        hover-shadow
        @mouseleave="onMouseLeave(item.id)"
      >
        <div>
          <div class="app-client">
            <t-link
              class="app-client-id"
              theme="primary"
              hover="color"
              @click="copyClient(item.id)"
            >
              {{ item.id.replace(/\B(?=(\d{3})+(?!\d))/g, ' ') }}
            </t-link>
            <t-popconfirm
              theme="danger"
              content="您确认删除吗？"
              @confirm="delAction(item.id)"
            >
              <t-button variant="text" theme="danger" shape="circle">
                <template #icon>
                  <t-icon name="delete"></t-icon>
                </template>
              </t-button>
            </t-popconfirm>
          </div>
          <div @mouseenter="onMouseEnter(item.id)">
            <t-list>
              <t-list-item>
                备注
                <template #action>
                  <t-link theme="primary" hover="color">
                    {{ item.note }}
                  </t-link>
                </template>
              </t-list-item>
              <t-list-item>
                主机名
                <template #action>
                  <t-link theme="primary" hover="color">
                    {{ item.pc_name }}
                  </t-link>
                </template>
              </t-list-item>
              <t-list-item>
                局域网IP
                <template #action>
                  <t-link theme="primary" hover="color">
                    {{ item.ip_address }}
                  </t-link>
                </template>
              </t-list-item>
            </t-list>
          </div>
        </div>
        <div v-if="currentClient === item.id" class="app-client-open">
          <t-button
            size="large"
            theme="success"
            shape="circle"
            @click="onConnect(item.id)"
          >
            <template #icon>
              <t-icon name="poweroff"></t-icon>
            </template>
          </t-button>
        </div>
      </t-card>
    </div>
    <t-pagination
      v-model="pagination.page"
      v-model:page-size="pagination.size"
      :total="dataTotol"
      :page-size-options="pageSizeOptions"
      show-jumper
      @change="pageChange"
    />
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { GetClient, DelClient, OnlineClient } from '@/requests/api'
import useClipboard from 'vue-clipboard3'
import { MessagePlugin } from 'tdesign-vue-next'

const { toClipboard } = useClipboard()
const router = useRouter()

const loading = ref(false)
const clientData = ref([])
const pagination = reactive({
  page: 1,
  size: 30
})
const dataTotol = ref(0)
const currentClient = ref()
const pageSizeOptions = [
  { label: '每页 20 个', value: 20 },
  { label: '每页 30 个', value: 30 },
  { label: '每页 50 个', value: 50 },
  { label: '每页 100 个', value: 100 }
]

const getData = async () => {
  const res = await GetClient(pagination)
  if (res) {
    clientData.value = res.data
    pagination.page = res.meta.pagination.current_page
    pagination.size = res.meta.pagination.per_page
    dataTotol.value = res.meta.pagination.total
    loading.value = false
  }
}

getData()

const copyClient = async (value) => {
  await toClipboard(value)
  MessagePlugin.success('已复制 识别码')
}

const onConnect = async (value) => {
  const res = await OnlineClient(value)
  console.log(res.data)
  if (!res.data) {
    MessagePlugin.error('远程设备不在线')
    return false
  }
  const routeUrl = router.resolve({
    path: `/desktop/${value}`
  })
  window.open(routeUrl.href)
}

const onMouseEnter = (value) => {
  currentClient.value = value
}

const onMouseLeave = () => {
  currentClient.value = null
}

const delAction = async (id) => {
  await DelClient(id)
  getData()
}

const pageChange = () => {
  getData()
}
</script>
<style lang="scss" scoped>
.app-page {
  height: 100%;
  min-height: 100vh;
  .t-pagination {
    padding: 20px 20px 50px;
  }
}
.app-grid-container {
  display: grid;
  grid-template-columns: repeat(5, 1fr);
  gap: 1.5rem; /* 间距 */
  width: 100%; /* 容器宽度 */
  padding: 20px; /* 内边距 */
  box-sizing: border-box;
  .app-client {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 1rem;
    &-id {
      font-size: 20px;
      font-weight: 700;
    }
  }
  .t-list-item {
    padding: var(--td-comp-paddingTB-m) 0;
  }
  &-item {
    position: relative;
  }
  .app-client-open {
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(122, 122, 122, 0.2);
  }
}
</style>
