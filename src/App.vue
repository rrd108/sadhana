<script setup lang="ts">
  import { useStore } from './store'
  import AppHeader from '@/components/AppHeader.vue'
  import AppFooter from '@/components/AppFooter.vue'

  const store = useStore()
</script>

<template>
  <div class="app">
    <AppHeader v-if="store.user.id" />
    <main>
      <router-view v-slot="{ Component }">
        <transition name="fade-slide" mode="out-in">
          <component :is="Component" />
        </transition>
      </router-view>
    </main>
    <AppFooter v-if="store.user.id" />
  </div>
</template>

<style scoped>
  .app {
    min-height: 100vh;
    background-color: var(--dark-purple);
    background-image: linear-gradient(135deg, var(--dark-purple), var(--medium-purple));
  }

  main {
    min-height: 80vh;
    max-width: 42em;
    margin: 0 auto;
    padding-left: 1em;
    padding-right: 1em;
  }

  .locale-changer {
    position: absolute;
    top: 10px;
    right: 10px;
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
