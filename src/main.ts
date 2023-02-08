import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faChartPie,
  faCirclePlus,
  faGaugeHigh,
} from '@fortawesome/free-solid-svg-icons'

library.add(faChartPie, faCirclePlus, faGaugeHigh)

const pinia = createPinia()

createApp(App)
  .component('font-awesome-icon', FontAwesomeIcon)
  .use(router)
  .use(pinia)
  .mount('#app')
