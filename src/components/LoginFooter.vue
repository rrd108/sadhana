<script setup lang="ts">
  import { useRoute } from 'vue-router'
  const route = useRoute()
</script>

<template>
  <small>
    <router-link to="/" v-if="route.path != '/login'">Belépés</router-link>
    <router-link to="/register" v-if="route.path != '/register'"> Regisztráció </router-link>
    <router-link to="/forgot-pass" v-if="route.path != '/forgot-pass'"> Elfelejtett jelszó </router-link>
  </small>
</template>

<style scoped>
  small {
    display: flex;
    justify-content: space-evenly;
  }
  small a {
    color: #fff;
    text-decoration: none;
  }
</style>
