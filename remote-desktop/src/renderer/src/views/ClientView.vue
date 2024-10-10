<template>
  <div>
    <t-table
      row-key="id"
      :data="clientData"
      :columns="tableColumn"
      :pagination="pagination"
      :loading="loading"
      height="calc(100vh - 176px)"
      hover
    >
      <template #header-operate>
        <t-button size="small" theme="warning" @click="testButton">
          刷新
        </t-button>
      </template>
      <template #id="{ row }">
        <t-link theme="primary" hover="color" @click="copyId(row)">
          {{ row.id }}
        </t-link>
      </template>
      <template #operate="{ row }">
        <t-space>
          <t-popconfirm
            theme="danger"
            content="您确认删除吗？"
            placement="top-right"
            @confirm="delAction(row)"
          >
            <t-link variant="text" theme="danger"> 删除 </t-link>
          </t-popconfirm>
        </t-space>
      </template>
    </t-table>
  </div>
</template>
<script setup>
import { ref, reactive } from 'vue'
import { useRouter } from 'vue-router'
import { MessagePlugin } from 'tdesign-vue-next'
import { GetClient, DelClient } from '@renderer/requests/api'

const loading = ref(false)
const clientData = ref([])
const pagination = ref({
  size: 'small',
  current: 1,
  pageSize: 30,
  total: 0,
  showPageSize: false,
  onChange: (value) => {
    pagination.value.current = value.current
    pagination.value.pageSize = value.pageSize
    getData()
  }
})

const tableColumn = [
  { colKey: 'id', title: '识别码', width: 100 },
  { colKey: 'password', title: '验证码', ellipsis: true },
  { colKey: 'note', title: '备注', ellipsis: true },
  { colKey: 'pc_name', title: '主机名', ellipsis: true },
  { colKey: 'ip_address', title: '局域网IP', ellipsis: true },
  { colKey: 'operate', title: 'header-operate', align: 'center', width: 70 }
]

const getData = async () => {
  loading.value = true
  const res = await GetClient({
    page: pagination.value.current,
    size: pagination.value.pageSize
  })
  clientData.value = res.data
  pagination.value.current = res.meta.pagination.current_page
  pagination.value.pageSize = res.meta.pagination.per_page
  pagination.value.total = res.meta.pagination.total
  loading.value = false
}

getData()

const delAction = async (value) => {
  await DelClient(value.id)
  getData()
}

const copyId = async (value) => {
  await window.eleApi.copyAction(value.id)
  MessagePlugin.success('已复制 识别码')
}

const testButton = () => {
  getData()
  getData()
  getData()
}
</script>
