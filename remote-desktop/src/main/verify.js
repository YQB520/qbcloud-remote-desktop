import { BrowserWindow, screen } from 'electron'
import { is } from '@electron-toolkit/utils'
import { join } from 'path'

const createVerifyWindow = async (win, client) => {
  const point = screen.getCursorScreenPoint()
  const current = screen.getDisplayNearestPoint(point).workAreaSize

  const winVerify = new BrowserWindow({
    show: false,
    frame: false,
    resizable: false,
    width: 380,
    height: 231,
    x: current.width - 380 - 10,
    y: current.height - 231 - 50,
    webPreferences: {
      preload: join(__dirname, '../preload/verify.js'),
      sandbox: false
    }
  })

  winVerify.setSkipTaskbar(true)

  winVerify.setAlwaysOnTop(true)

  winVerify.on('ready-to-show', () => {
    win.flashFrame(true)
    winVerify.webContents.send('send-verify', client)
    winVerify.show()
  })
  const route = '/#/verify'
  if (is.dev && process.env['ELECTRON_RENDERER_URL']) {
    winVerify.loadURL(process.env['ELECTRON_RENDERER_URL'] + route)
  } else {
    winVerify.loadFile(join(__dirname, '../renderer/index.html'), {
      hash: route
    })
  }
}

export default createVerifyWindow
