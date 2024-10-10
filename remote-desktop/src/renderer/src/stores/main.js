import { defineStore } from 'pinia'

export const mainStore = defineStore('main', {
  state: () => {
    return {
      app: {},
      system: {},
      client: {
        id: '',
        password: null
      }
    }
  },
  actions: {
    setApp(data) {
      this.system = data
    },
    setSystem(data) {
      this.system = data
    },
    setClient(data) {
      this.client = data
    }
  }
})
