import {
  app,
  BrowserWindow,
  ipcMain,
  clipboard,
  screen,
  session,
  desktopCapturer
} from 'electron'
import { join } from 'path'
import os from 'os'
import { is } from '@electron-toolkit/utils'
import { keyboard, mouse, Point, straightTo } from '@scanood/nut-js' // straightTo
import { MouseMap, KeyMap } from './keymap'
import createVerifyWindow from './verify'

const handleEvent = (win) => {
  let sourceStream = null
  mouse.config.autoDelayMs = 0
  mouse.config.mouseSpeed = 0

  session.defaultSession.setDisplayMediaRequestHandler((request, callback) => {
    if (sourceStream) {
      callback({ video: sourceStream })
      return
    }
    callback()
  })

  ipcMain.handle('get-app', () => {
    return {
      version: app.getVersion(),
      name: app.getName()
    }
  })

  ipcMain.handle('operate-desktop', (event, type) => {
    if (type) {
      win.minimize()
      return
    }
    win.hide()
  })

  ipcMain.handle('get-system', () => {
    const networkInterfaces = os.networkInterfaces()
    const network = networkInterfaces[Object.keys(networkInterfaces)[0]][0]
    return {
      address: network.address,
      netmask: network.netmask,
      arch: os.arch(),
      hostname: os.hostname(),
      mac: network.mac,
      version: os.version(),
      release: os.release()
    }
  })

  ipcMain.handle('get-desktop', async (event, index) => {
    const sources = await desktopCapturer.getSources({ types: ['screen'] })
    if (index === true) {
      const displays = screen.getAllDisplays()
      const desktops = []
      sources.forEach((item) => {
        const display = displays.find(
          (value) => item.display_id === value.id.toString()
        )
        desktops.push({
          id: item.id,
          display_id: item.display_id,
          name: item.name,
          width: display.size.width,
          height: display.size.height
        })
      })
      return desktops
    }
    sourceStream = sources[index]
  })

  ipcMain.handle('copy-action', async (event, data) => {
    clipboard.writeText(data.toString())
  })

  ipcMain.handle('start-connect', async (event, data) => {
    const winClient = new BrowserWindow({
      width: 800,
      height: 600,
      center: true,
      icon: join(__dirname, '../../resources/logo.png'),
      webPreferences: {
        sandbox: false
      }
    })
    winClient.maximize()
    let remoteUrl = 'https://remote.busicloud.shop'
    if (is.dev) {
      remoteUrl = 'http://10.2.0.38:3000'
    }
    winClient.loadURL(`${remoteUrl}/desktop/${data.id}`)
    winClient.webContents.openDevTools()
  })

  ipcMain.handle('handle-keyboard', async (event, { type, code }) => {
    const key = KeyMap.get(code)
    if (type === 'keydown') {
      await keyboard.pressKey(key)
    } else if (type === 'keyup') {
      await keyboard.releaseKey(key)
    }
  })

  ipcMain.handle('handle-mouse', async (event, data) => {
    switch (data.type) {
      case 'active': {
        // const key = KeyMap.get('Escape')
        // await keyboard.pressKey(key)
        // await keyboard.releaseKey(key)
        // // mouse.doubleClick(MouseMap.get(0))
        // console.log('active')
        break
      }
      case 'mousewheel': {
        if (data.value < 0) {
          mouse.scrollUp(Math.abs(data.value))
          break
        }
        mouse.scrollDown(data.value)
        break
      }
      case 'mousedown':
        mouse.pressButton(MouseMap.get(data.value))
        break
      case 'mouseup':
        mouse.releaseButton(MouseMap.get(data.value))
        break
      default: {
        if (data.width && data.height) {
          const position = new Point(data.width, data.height)
          mouse.move(straightTo(position))
        }
        break
      }
    }
  })

  ipcMain.handle('create-verify', async (event, client) => {
    createVerifyWindow(win, client)
  })

  ipcMain.handle('verify-action', (event, client) => {
    win.flashFrame(false)
    win.webContents.send('on-accredit', client)
    event.sender.close()
  })
}

export default handleEvent
