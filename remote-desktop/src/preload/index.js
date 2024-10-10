import { contextBridge, ipcRenderer } from 'electron'

// Custom APIs for renderer
const api = {
  getApp: (callback) => ipcRenderer.invoke('get-app', callback),
  operateDesktop: (callback) => ipcRenderer.invoke('operate-desktop', callback),
  getSystem: (callback) => ipcRenderer.invoke('get-system', callback),
  getDesktop: (callback) => ipcRenderer.invoke('get-desktop', callback),
  copyAction: (callback) => ipcRenderer.invoke('copy-action', callback),
  startConnect: (callback) => ipcRenderer.invoke('start-connect', callback),
  updaterAction: (data) => ipcRenderer.send('updater', data),
  onUpdater: (callback) => ipcRenderer.on('updater', callback),
  handleKeyboard: (callback) => ipcRenderer.invoke('handle-keyboard', callback),
  handleMouse: (callback) => ipcRenderer.invoke('handle-mouse', callback),
  createAccept: (callback) => ipcRenderer.invoke('create-verify', callback),
  onAccredit: (callback) => ipcRenderer.on('on-accredit', callback)
}

// Use `contextBridge` APIs to expose Electron APIs to
// renderer only if context isolation is enabled, otherwise
// just add to the DOM global.
if (process.contextIsolated) {
  try {
    contextBridge.exposeInMainWorld('eleApi', api)
  } catch (error) {
    console.error(error)
  }
} else {
  window.eleApi = api
}
