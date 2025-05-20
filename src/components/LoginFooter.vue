<script setup lang="ts">
  import { useRoute } from 'vue-router'
  const route = useRoute()
</script>

<template>
  <small>
    <router-link to="/" v-if="route.path != '/login'">Belépés</router-link>
    <router-link to="/register" v-if="route.path != '/register'">{{ $t('account.register') }}</router-link>
    <router-link to="/forgot-pass" v-if="route.path != '/forgot-pass'">{{ $t('account.forgotPassword') }}</router-link>
  </small>
</template>

<style scoped>
  small {
    margin-top: 12em;
    display: flex;
    justify-content: space-around;
  }
  small a {
    color: #fff;
    text-decoration: none;
  }
</style>
