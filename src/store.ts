import { defineStore } from 'pinia'
import User from './types/User'

interface State {
  user: User
  notAcceptedBadges: number
  tokenHeader: { headers: { Token: string | null } }
}

export const useStore = defineStore('sadhana', {
  state: (): State => ({
    user: {} as User,
    get notAcceptedBadges() {
      return this.user?.badges?.filter(badge => !badge._joinData.accepted)
        .length
    },
    get tokenHeader() {
      return { headers: { Token: this.user.token } }
    },
  }),
})
