<script setup lang="ts">
  import { i18n, SUPPORT_LOCALES, setI18nLanguage, loadLocaleMessages} from '../i18n'
  const changeLocale = (event) => {
    const eventLocale = event.target.value

    // use locale if eventLocale is not in SUPPORT_LOCALES
    if (!SUPPORT_LOCALES.includes(eventLocale)) {
      return next(`/${locale}`)
    }
    // load locale messages
    if (!i18n.global.availableLocales.includes(eventLocale)) {
      loadLocaleMessages(i18n, eventLocale)
    }

    // set i18n language
    setI18nLanguage(i18n, eventLocale)

    localStorage.setItem('sadhana.locale',eventLocale)
  }

</script>
<template>
  <div class="locale-changer">
    <select v-model="$i18n.locale" @change="changeLocale($event)">
      <option v-for="locale in SUPPORT_LOCALES" :key="`locale-${locale}`" :value="locale">{{ locale }}</option>
    </select>
  </div>
</template>
