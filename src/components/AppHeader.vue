<script setup lang="ts">
  import { useStore } from '../store'
  const store = useStore()
</script>

<template>
  <header>
    <img alt="Sadhana" src="@/assets/logo.png" />
    <span> {{ store.user.name ?? store.user.email.split('@')[0] }}</span>
  </header>
</template>

<style scoped>
  header {
    position: fixed;
    top: 0;
    height: 10vh;
    width: 100vw;
    display: grid;
    grid-template-columns: 1fr 4fr 1fr;
    align-items: center;
    justify-items: center;
    font-weight: bold;
    background-color: var(--dark-purple);
    z-index: 1;
  }
  img {
    height: 10vh;
  }
  span {
    text-align: center;
    font-size: 1.2rem;
  }
</style>
