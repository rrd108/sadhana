import { defineStore } from 'pinia'
import User from './types/User'

interface State {
  user: User
  tokenHeader: { headers: { Token: string | null } }
}

export const useStore = defineStore('sadhana', {
  state: (): State => ({
    user: {} as User,
    get tokenHeader() {
      return { headers: { Token: this.user.token } }
    },
  }),
})
