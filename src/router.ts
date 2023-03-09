import { createRouter, createWebHistory } from 'vue-router'
import Day from '@/pages/Day.vue'

const routes = [
  {
    path: '/',
    name: 'Day',
    component: Day,
  },
  {
    path: '/badges',
    name: 'Badges',
    component: () => import('/src/pages/Badges.vue'),
  },
  {
    path: '/welcome',
    name: 'Welcome',
    component: () => import('/src/components/Welcome.vue'),
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
    path: '/pass-reset/:userId/:tempPass',
    name: 'PassReset',
    component: () => import('/src/pages/PassReset.vue'),
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
  {
    path: '/journal/:userId/:week',
    name: 'Journal',
    component: () => import('/src/pages/Journal.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
