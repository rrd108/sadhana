import { createRouter, createWebHistory } from 'vue-router'
import Day from '@/pages/Day.vue'
import { useStore } from './store'

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
    path: '/forgot-pass',
    name: 'ForgotPass',
    component: () => import('/src/pages/ForgotPass.vue'),
    meta: { noAuth: true },
  },
  {
    path: '/leaderboard',
    name: 'Leaderboard',
    component: () => import('/src/pages/Leaderboard.vue'),
  },
  {
    path: '/login',
    name: 'Login',
    component: () => import('/src/pages/Login.vue'),
    meta: { noAuth: true },
  },
  {
    path: '/pass-reset/:userId/:tempPass',
    name: 'PassReset',
    component: () => import('/src/pages/PassReset.vue'),
    meta: { noAuth: true },
  },
  {
    path: '/register',
    name: 'Register',
    component: () => import('/src/pages/Register.vue'),
    meta: { noAuth: true },
  },
  {
    path: '/settings',
    name: 'Settings',
    component: () => import('/src/pages/Settings.vue'),
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

router.beforeEach((to, from, next) => {
  const store = useStore()
  const isAuthenticated = store.user?.id || false

  if (to.matched.some(route => route.meta.noAuth === undefined)) {
    if (!isAuthenticated) {
      next('/login') // Redirect to the login page
    } else {
      next() // Continue to the intended route
    }
  } else {
    next() // For routes that don't require authentication
  }
})

export default router
