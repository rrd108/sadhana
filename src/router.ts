import { createRouter, createWebHistory } from 'vue-router'
import Day from '@/pages/Day.vue'

const routes = [
  {
    path: '/',
    name: 'Day',
    component: Day,
  },
  {
    path: '/forgot-pass',
    name: 'ForgotPass',
    component: () => import('/src/pages/ForgotPass.vue'),
  },
  {
    path: '/leaderboard',
    name: 'Leaderboard',
    component: () => import('/src/pages/Leaderboard.vue'),
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('/src/pages/Register.vue'),
  },
  {
    path: '/stat',
    name: 'Stat',
    component: () => import('/src/pages/Stat.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
