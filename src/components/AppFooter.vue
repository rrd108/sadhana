<script setup lang="ts"></script>

<template>
  <footer>
    <nav>
      <router-link to="/"><font-awesome-icon icon="gauge-high" /></router-link>
      <router-link to="/"><font-awesome-icon icon="circle-plus" /></router-link>
      <router-link to="/"><font-awesome-icon icon="chart-pie" /></router-link>
    </nav>
  </footer>
</template>

<style scoped>
  footer {
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
