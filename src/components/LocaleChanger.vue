<script setup lang="ts">
  import { SUPPORT_LOCALES, setLocale } from '../i18n'
</script>
<template>
  <div class="locale-changer">
    <select v-model="$i18n.locale" @change="(e: Event) => setLocale((e.target as HTMLSelectElement).value)">
      <option v-for="locale in SUPPORT_LOCALES" :key="`locale-${locale}`" :value="locale">
        {{ locale }}
      </option>
    </select>
  </div>
</template>
