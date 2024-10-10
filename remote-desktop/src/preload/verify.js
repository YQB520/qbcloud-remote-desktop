import { contextBridge, ipcRenderer } from 'electron'

// Custom APIs for renderer
const api = {
  getApp: (callback) => ipcRenderer.invoke('get-app', callback),
  verifyAction: (callback) => ipcRenderer.invoke('verify-action', callback),
  onVerifyClient: (callback) => ipcRenderer.on('send-verify', callback)
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
