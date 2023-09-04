import { createApp } from 'vue'
import './style.css'
import App from './App.vue'
import { createPinia } from 'pinia'
import router from './router'

import Toast, { PluginOptions } from 'vue-toastification'
import 'vue-toastification/dist/index.css'

import { createI18n } from 'vue-i18n'
import hu from './locale/hu.json'
import enUS from './locale/en-US.json'

import { library } from '@fortawesome/fontawesome-svg-core'
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import {
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCircleMinus,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart,
} from '@fortawesome/free-solid-svg-icons'

library.add(
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCircleMinus,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart
)

const i18n = createI18n({
	locale: 'hu', //default to hunarian locale
	messages: {
          'hu': hu,
          'en-US': enUS
	}
})

const pinia = createPinia()

createApp(App)
  .component('font-awesome-icon', FontAwesomeIcon)
  .use(router)
  .use(pinia)
  .use(Toast)
  .use(i18n)
  .mount('#app')
