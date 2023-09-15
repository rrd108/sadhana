<script setup lang="ts">
import { setLocale } from '../i18n'
</script>

<template>
  <div class="flagbox">
  <img @click="setLocale('en-US')" src="flag-us.svg" />
  <img @click="setLocale('hu')" src="flag-hu.svg" />
  </div>
</template>

<style scoped>
img {
  width: 3em;
};

.flagbox {
  display: grid;
  grid-template-columns: auto auto;
  justify-content: space-evenly;
  grid-gap: 3em;
};

</style>
