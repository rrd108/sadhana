<script setup lang="ts">
  import { useStore } from '../store'
  const store = useStore()
</script>

<template>
  <footer>
    <nav>
      <router-link to="/leaderboard">
        <font-awesome-icon icon="gauge-high" />
      </router-link>
      <router-link to="/"><font-awesome-icon icon="circle-plus" /></router-link>
      <router-link to="/stat">
        <font-awesome-icon icon="chart-pie" />
      </router-link>
      <router-link to="/badges" v-if="store.user.badges.length">
        <font-awesome-icon icon="shield-heart" />
      </router-link>
    </nav>
  </footer>
</template>

<style scoped>
  footer {
    position: fixed;
    bottom: 0;
    width: 100vw;
    height: 10vh;
    font-size: 3rem;
  }
  nav {
    display: flex;
    justify-content: space-evenly;
    background-color: var(--pinky);
    margin: 0 0.5em;
    border-radius: 0.5em;
  }
  a {
    color: var(--dark-purple);
  }
</style>
