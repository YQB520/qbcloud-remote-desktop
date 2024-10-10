import { app, BrowserWindow, Menu, powerSaveBlocker } from 'electron'
import { join } from 'path'
import { electronApp, optimizer, is } from '@electron-toolkit/utils'
import updateHandle from './updater'
import handleEvent from './event'
import createTray from './tray'

const createWindow = () => {
  const mainWindow = new BrowserWindow({
    width: 800,
    height: 550,
    show: false,
    center: true,
    frame: false,
    resizable: false,
    maximizable: false,
    shadow: true,
    icon: join(__dirname, '../../resources/logo.png'),
    webPreferences: {
      preload: join(__dirname, '../preload/index.js'),
      sandbox: false
    }
  })

  mainWindow.webContents.openDevTools()

  mainWindow.on('ready-to-show', () => {
    mainWindow.show()
  })

  createTray(mainWindow)

  updateHandle(mainWindow)

  handleEvent(mainWindow)

  if (is.dev && process.env['ELECTRON_RENDERER_URL']) {
    mainWindow.loadURL(process.env['ELECTRON_RENDERER_URL'])
  } else {
    mainWindow.loadFile(join(__dirname, '../renderer/index.html'))
  }
}

// This method will be called when Electron has finished
// initialization and is ready to create browser windows.
// Some APIs can only be used after this event occurs.
app.whenReady().then(() => {
  powerSaveBlocker.start('prevent-app-suspension')
  powerSaveBlocker.start('prevent-display-sleep')

  Menu.setApplicationMenu(null)

  app.setName('社牛远程桌面')

  // Set app user model id for windows
  electronApp.setAppUserModelId('com.sheniu.remote.desktop.app')

  // Default open or close DevTools by F12 in development
  // and ignore CommandOrControl + R in production.
  // see https://github.com/alex8088/electron-toolkit/tree/master/packages/utils
  app.on('browser-window-created', (_, window) => {
    optimizer.watchWindowShortcuts(window)
  })

  createWindow()

  app.on('activate', function () {
    // On macOS it's common to re-create a window in the app when the
    // dock icon is clicked and there are no other windows open.
    if (BrowserWindow.getAllWindows().length === 0) {
      createWindow()
    }
  })
})

app.on('window-all-closed', () => {
  if (process.platform !== 'darwin') {
    app.quit()
  }
})
