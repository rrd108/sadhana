<script setup lang="ts">
  const storeLocale = (event) => localStorage.setItem('sadhana.locale',event.target.value)
</script>
<template>
  <div class="locale-changer">
    <select v-model="$i18n.locale" @change="storeLocale($event)">
      <option v-for="locale in $i18n.availableLocales" :key="`locale-${locale}`" :value="locale">{{ locale }}</option>
    </select>
  </div>
</template>
