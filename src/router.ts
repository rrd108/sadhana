import { createRouter, createWebHistory } from 'vue-router'
import Day from '@/pages/Day.vue'

const routes = [
  {
    path: '/',
    name: 'Day',
    component: Day,
  },
  // {
  //   path: '/covid',
  //   name: 'Covid',
  //   component: () => import('/src/pages/Covid.vue'),
  // },
]

const router = createRouter({
  history: createWebHistory(),
  routes,
})

export default router
