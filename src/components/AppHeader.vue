<script setup lang="ts">
  import { useStore } from '../store'
  const store = useStore()
</script>

<template>
  <header>
    <img alt="Sadhana" src="@/assets/logo.png" />
    <span> {{ store.user.email.split('@')[0] }}</span>
  </header>
</template>

<style scoped>
  header {
    position: fixed;
    top: 0;
    height: 10vh;
    display: grid;
    grid-template-columns: 1fr 5fr;
    align-items: center;
    justify-items: center;
    font-size: 2rem;
    font-weight: bold;
    background-color: var(--dark-purple);
    z-index: 1;
  }
  img {
    height: 10vh;
  }
  span {
    transform: translateX(-5vh);
  }
</style>
