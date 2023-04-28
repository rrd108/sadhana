<script setup lang="ts">
  import SettingsNotifications from '../components/SettingsNotifications.vue'
  import SettingsAccess from '../components/SettingsAccess.vue'
</script>

<template>
  <section>
    <h1>Beállítások</h1>
    <SettingsNotifications />
    <hr />
    <SettingsAccess />
  </section>
</template>

<style scoped>
  h1 {
    margin: 1em 0;
  }
  hr {
    margin: 1.5em 3em;
    height: 0.1em;
    background-color: var(--pinky);
    opacity: 0.25;
  }
</style>
