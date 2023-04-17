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
        <small v-if="store.notAcceptedBadges">
          {{ store.notAcceptedBadges }}
        </small>
        <font-awesome-icon icon="shield-heart" />
      </router-link>
      <router-link to="/settings">
        <font-awesome-icon icon="gears" />
      </router-link>
      <span id="indicator"></span>
    </nav>
  </footer>
</template>

<style scoped>
  footer {
    position: fixed;
    bottom: 0;
    width: 100vw;
    height: 10vh;

    --linkWidth: 2.5rem;
    --linkMargin: 0.25em;
    --indicatorWidth: 0.5rem;
  }
  nav {
    position: relative;
    display: flex;
    background-color: var(--pinky);
    margin: 0 auto;
    border-radius: 0.5em;
    font-size: 2.75rem;
    width: calc(5 * var(--linkWidth) + 5 * 2 * var(--linkMargin));
  }
  a,
  #indicator {
    width: var(--linkWidth);
    margin: 0 var(--linkMargin);
    font-size: 2.5rem;
  }
  a {
    color: var(--dark-purple);
    position: relative;
    text-align: center;
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

  #indicator {
    position: absolute;
    z-index: -1;
    top: -0.75rem;
    left: calc((var(--linkWidth) - var(--indicatorWidth)) / 2);
    background-color: var(--pinky);
    width: var(--indicatorWidth);
    height: var(--indicatorWidth);
    border-radius: 50%;
    transition: transform 350ms ease-in-out;
  }
  a:nth-child(1).router-link-exact-active ~ #indicator {
    transform: translateX(calc(0 * var(--linkWidth) + 0 * var(--linkMargin)));
  }
  a:nth-child(2).router-link-exact-active ~ #indicator {
    transform: translateX(calc(var(--linkWidth) + 2 * var(--linkMargin)));
  }
  a:nth-child(3).router-link-exact-active ~ #indicator {
    transform: translateX(calc(2 * var(--linkWidth) + 4 * var(--linkMargin)));
  }
  a:nth-child(4).router-link-exact-active ~ #indicator {
    transform: translateX(calc(3 * var(--linkWidth) + 6 * var(--linkMargin)));
  }
  a:nth-child(5).router-link-exact-active ~ #indicator {
    transform: translateX(calc(4 * var(--linkWidth) + 8 * var(--linkMargin)));
  }
</style>
