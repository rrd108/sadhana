import { createApp } from "vue";
import "./style.css";
import App from "./App.vue";
import { createPinia } from "pinia";
import router from "./router";

import Toast, { PluginOptions } from "vue-toastification";
import "vue-toastification/dist/index.css";

import { setupI18n } from "./i18n";

import { library } from "@fortawesome/fontawesome-svg-core";
import { FontAwesomeIcon } from "@fortawesome/vue-fontawesome";
import {
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCircleMinus,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart,
} from "@fortawesome/free-solid-svg-icons";

library.add(
  faChartPie,
  faChevronCircleLeft,
  faChevronCircleRight,
  faCircleMinus,
  faCirclePlus,
  faGaugeHigh,
  faGears,
  faShieldHeart
);

const i18n = setupI18n({
  legacy: false, // you must set `false`, to use Composition API
  fallbackLocale: "hu",
});

const pinia = createPinia();

createApp(App)
  .component("font-awesome-icon", FontAwesomeIcon)
  .use(router)
  .use(pinia)
  .use(Toast)
  .use(i18n)
  .mount("#app");
