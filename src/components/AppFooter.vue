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
        <small>{{ store.notAcceptedBadges }}</small>
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
    position: relative;
  }
  small {
    position: absolute;
    top: -0.5rem;
    right: -0.25rem;
    z-index: 1;
    background-color: #f00;
    color: #fff;
    border-radius: 50%;
    width: 1.5rem;
    height: 1.5rem;
    font-size: 1.2rem;
    text-align: center;
    font-weight: bold;
  }
</style>
