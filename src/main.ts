import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'

import Toast, { PluginOptions } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart,
} from '@fortawesome/free-solid-svg-icons'

library.add(
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart
)

const pinia = createPinia()

createApp(App)
  .component('font-awesome-icon', FontAwesomeIcon)
  .use(router)
  .use(pinia)
  .use(Toast)
  .mount('#app')
