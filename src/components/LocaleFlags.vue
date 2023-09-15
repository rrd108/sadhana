<script setup lang="ts">
import { setLocale } from "../i18n";
</script>

<template>
  <div>
    <img @click="setLocale('en-US')" src="flag-us.svg" />
    <img @click="setLocale('hu')" src="flag-hu.svg" />
  </div>
</template>

<style scoped>
div {
  display: grid;
  grid-template-columns: auto auto;
  justify-content: center;
  grid-gap: 3em;
}

img {
  width: 3em;
}
</style>
