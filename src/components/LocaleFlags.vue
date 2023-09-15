<script setup lang="ts">
import { setLocale } from "../i18n";
</script>

<template>
  <div>
    <figure>
    <img @click="setLocale('en-US')" src="flag-us.svg" />
    <figcaption>English</figcaption>
    </figure>
    <figure>
    <img @click="setLocale('hu')" src="flag-hu.svg" />
    <figcaption>Magyarul</figcaption>
    </figure>

  </div>
</template>

<style scoped>
div {
  display: grid;
  grid-template-columns: auto auto;
  justify-content: center;
  grid-gap: 3em;
  margin-top: 120px;
}

img {
  width: 3em;
}
</style>
