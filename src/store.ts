import { defineStore } from 'pinia'
import User from './types/User'

export const useStore = defineStore('sadhana', {
  state: () => ({
    user: {} as User,
  }),
})
