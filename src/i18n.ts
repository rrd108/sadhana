import { nextTick, WritableComputedRef } from "vue";
import { createI18n, I18n } from "vue-i18n";

export const SUPPORT_LOCALES = ["en-US", "hu"];
export let i18n: I18n;

export function setupI18n(options = { locale: "" }) {
  options.locale || (options.locale = findLocale('hu'))
  i18n = createI18n(options);
  setI18nLanguage(i18n, options.locale);
  loadLocaleMessages(i18n, options.locale);
  return i18n;
}

export function setI18nLanguage(i18n: I18n, locale: string) {
  if (i18n.mode === 'legacy') {
    i18n.global.locale = locale
  } else {
    (i18n.global.locale as WritableComputedRef<string>).value = locale
  }
  localStorage.setItem('sadhana.locale', locale)
  /**
   * NOTE:
   * If you need to specify the language setting for headers, such as the `fetch` API, set it here.
   * The following is an example for axios.
   *
   * axios.defaults.headers.common['Accept-Language'] = locale
   */
  document.querySelector("html")!.setAttribute("lang", locale);
}

export async function loadLocaleMessages(i18n: I18n, locale: string) {

  // load locale messages with dynamic import
  const messages = await import(
    /* webpackChunkName: "locale-[request]" */ `./locale/${locale}.json`
  );
  // set locale and locale message
  i18n.global.setLocaleMessage(locale, messages.default);

  return nextTick();
}

export function findLocale(locale: string) {
  // Check param
  if (SUPPORT_LOCALES.indexOf(locale) > -1) {
    return locale
  }
  // if param is thruthy but not suported return -1
  if (locale) {
    return "-1"
  }
  // Check local storage
  if (SUPPORT_LOCALES.indexOf(localStorage.getItem("sadhana.locale") || '') > -1) {
    return localStorage.getItem("sadhana.locale") || '';
  }
  // check browser language prefrence
  for (var lang of navigator.languages) {
    if (SUPPORT_LOCALES.indexOf(lang) > -1) {
      return lang;
    }
  }
  // Default
  return "hu";
}

// locale is optional, if not passed best guess is made
export function setLocale(locale: string) {
  var useLocale = findLocale(locale)//Determine what locale to use
  if (useLocale == "-1") { return -1 }//locale not supported
  loadLocaleMessages(i18n, useLocale)//First load the locale
  setI18nLanguage(i18n, useLocale)// Then change the locale
}
