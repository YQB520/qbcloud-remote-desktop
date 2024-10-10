import { app, Menu, Tray } from 'electron'
import { join } from 'path'

const createTray = (win) => {
  const tray = new Tray(join(__dirname, '../../resources/logo.png'))

  const contextMenu = Menu.buildFromTemplate([
    {
      label: '显示主窗口',
      click: () => win.show()
    },
    {
      label: '重启应用',
      click: () => app.relaunch()
    },
    {
      type: 'separator'
    },
    {
      label: '退出',
      role: 'quit'
    }
  ])

  tray.setToolTip(app.getName())
  tray.setContextMenu(contextMenu)

  tray.on('click', () => {
    if (win.isVisible()) {
      win.hide()
      return
    }
    win.show()
  })
}

export default createTray
