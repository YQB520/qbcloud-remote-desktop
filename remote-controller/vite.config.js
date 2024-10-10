import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'
import path from 'path'
import { TDesignResolver } from 'unplugin-vue-components/resolvers'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'

export default defineConfig({
  server: {
    port: 3000 // 指定端口号为3000
  },
  plugins: [
    vue(),
    AutoImport({
      resolvers: [
        TDesignResolver({
          library: 'vue-next'
        })
      ]
    }),
    Components({
      resolvers: [
        TDesignResolver({
          library: 'vue-next'
        })
      ]
    })
  ],
  resolve: {
    alias: {
      '@': path.resolve(__dirname, './src')
    }
  }
})
