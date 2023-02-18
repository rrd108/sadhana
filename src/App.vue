<script setup lang="ts">
  import { useStore } from './store'
  import AppHeader from '@/components/AppHeader.vue'
  import AppFooter from '@/components/AppFooter.vue'
  import Welcome from '@/components/Welcome.vue'

  const store = useStore()
</script>

<template>
  <div class="app">
    <Welcome v-if="!store.user.id" />
    <div v-if="store.user.id">
      <AppHeader />
      <main>
        <router-view v-slot="{ Component }">
          <transition name="fade-slide" mode="out-in">
            <component :is="Component" />
          </transition>
        </router-view>
      </main>
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

  .fade-slide-enter-from,
  .fade-slide-leave-to {
    opacity: 0;
  }

  .fade-slide-enter-active,
  .fade-slide-leave-active {
    transition: opacity 350ms;
  }
</style>
