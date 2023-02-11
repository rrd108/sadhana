<script setup lang="ts">
  import { useStore } from './store'
  import { useRoute } from 'vue-router'
  import AppHeader from '@/components/AppHeader.vue'
  import AppFooter from '@/components/AppFooter.vue'
  import Login from '@/pages/Login.vue'
  import Register from '@/pages/Register.vue'
  import ForgotPass from '@/pages/ForgotPass.vue'

  const store = useStore()
  const route = useRoute()
</script>

<template>
  <div class="app">
    <div v-if="!store.user.id">
      <Login v-if="route.path == '/'" />
      <Register v-if="route.path == '/register'" />
      <ForgotPass v-if="route.path == '/forgot-pass'" />
    </div>
    <div v-if="store.user.id">
      <AppHeader />
      <main><router-view /></main>
      <AppFooter />
    </div>
  </div>
</template>

<style scoped>
  .app {
    min-height: 100vh;
    background-color: var(--dark-purple);
    background-image: linear-gradient(
      135deg,
      var(--dark-purple),
      var(--medium-purple)
    );
  }

  main {
    min-height: 80vh;
    padding: 10vh 1em;
  }
</style>
