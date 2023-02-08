import { defineStore } from 'pinia'
import User from './types/User'

interface State {
  user: User
}

export const useStore = defineStore('sadhana', {
  state: (): State => ({
    user: {} as User,
  }),
})
