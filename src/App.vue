<script setup lang="ts">
  import { useStore } from './store'
  import AppHeader from '@/components/AppHeader.vue'
  import AppFooter from '@/components/AppFooter.vue'
  import Login from '@/pages/Login.vue'

  const store = useStore()
</script>

<template>
  <div class="app">
    <Login v-if="!store.user.id" />
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
