import { resolve } from 'path'
import {
  defineConfig,
  externalizeDepsPlugin,
  bytecodePlugin
} from 'electron-vite'
import vue from '@vitejs/plugin-vue'
import AutoImport from 'unplugin-auto-import/vite'
import Components from 'unplugin-vue-components/vite'
import { TDesignResolver } from 'unplugin-vue-components/resolvers'

export default defineConfig({
  main: {
    plugins: [externalizeDepsPlugin(), bytecodePlugin()]
  },
  preload: {
    plugins: [externalizeDepsPlugin(), bytecodePlugin()],
    build: {
      lib: {
        entry: ['src/preload/index.js', 'src/preload/verify.js']
      }
    }
  },
  renderer: {
    server: {
      port: 5175, // 默认端口
      host: '0.0.0.0' // 允许外部访问
    },
    build: { minify: true },
    resolve: {
      alias: {
        '@renderer': resolve('src/renderer/src')
      }
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
    ]
  }
})
