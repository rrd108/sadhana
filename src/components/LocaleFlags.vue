<script setup lang="ts">
import { setLocale } from "../i18n";
</script>

<template>
  <small>
    <figure @click="setLocale('en-US')">
    <img src="flag-us.svg" />
    <figcaption>English</figcaption>
    </figure>
    <figure @click="setLocale('hu')">
    <img src="flag-hu.svg" />
    <figcaption>Magyar</figcaption>
    </figure>
  </small>
</template>

<style scoped>
small {
  display: grid;
  grid-template-columns: auto auto;
  justify-content: center;
  grid-gap: 0.5em;
  margin-top: 120px;
}

img {
  width: 2em;
}
</style>
