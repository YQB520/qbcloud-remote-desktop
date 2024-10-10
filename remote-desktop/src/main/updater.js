import { app, ipcMain, dialog } from 'electron'
import { is } from '@electron-toolkit/utils'
import { autoUpdater } from 'electron-updater'
import { join } from 'path'
import fs from 'fs-extra'

const updateHandle = (win) => {
  // 删除更新缓存
  fs.removeSync(
    join(
      autoUpdater.app.baseCachePath,
      'sheniu-remote-desktop-updater',
      'pending'
    )
  )

  ipcMain.on('updater', (event, action) => {
    if (is.dev) return false
    switch (action) {
      case 'check':
        autoUpdater.checkForUpdates()
        break
      case 'install':
        autoUpdater.quitAndInstall()
        break
      default:
        break
    }
  })

  // 监听'error'事件
  autoUpdater.on('error', () => {
    dialog.showMessageBoxSync({ type: 'error', message: '更新失败' })
    app.quit()
  })

  // 发现有新版本时触发
  autoUpdater.on('update-available', () => {
    win.webContents.send('updater', { action: 'available' })
  })

  // 发现没有可用更新时触发
  autoUpdater.on('update-not-available', () => {
    win.webContents.send('updater', { action: 'not-available' })
  })

  // 新版本下载进度
  autoUpdater.on('download-progress', (progressObj) => {
    win.webContents.send('updater', {
      action: 'progress',
      data: parseInt(progressObj.percent)
    })
  })

  // 新版本下载完成时触发
  autoUpdater.on('update-downloaded', () => {
    win.webContents.send('updater', { action: 'done' })
  })
}

export default updateHandle
