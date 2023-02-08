import { createRouter, createWebHistory } from 'vue-router'
import Day from '@/pages/Day.vue'

const routes = [
  {
    path: '/',
    name: 'Day',
    component: Day,
  },
  {
    path: '/stat',
    name: 'Stat',
    component: () => import('/src/pages/Stat.vue'),
  },
  {
    path: '/tab',
    name: 'Tab',
    component: () => import('/src/pages/Tab.vue'),
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
