<script setup lang="ts">
  import SettingsNotifications from '../components/SettingsNotifications.vue'
  import SettingsName from '../components/SettingsName.vue'
  import SettingsAccess from '../components/SettingsAccess.vue'
  import SettingsInfo from '../components/SettingsInfo.vue'
</script>

<template>
  <section>
    <h1>Beállítások</h1>
    <SettingsName />
    <hr />
    <SettingsNotifications />
    <hr />
    <SettingsAccess />
    <hr />
    <SettingsInfo />
  </section>
</template>

<style scoped>
  h1 {
    margin: 1em 0;
  }
</style>
