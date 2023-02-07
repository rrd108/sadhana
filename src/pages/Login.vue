<script setup lang="ts">
  const login = () => {
    console.log('login')
  }
</script>

<template>
  <section>
    <img alt="Sadhana" src="@/assets/logo.png" />
    <form @submit.prevent="login">
      <label for="email">Email</label>
      <input type="email" />
      <label for="password">Password</label>
      <input type="password" />
      <input type="submit" value="Belép" />
    </form>
  </section>
</template>

<style scoped>
  section {
    text-align: center;
    margin-top: -5em;
  }
  img {
    transform: translateY(7.25em);
  }
  form {
    padding: 2em;
    padding-top: 5em;
    background-color: #fff;
    color: var(--dark-purple);
    display: flex;
    flex-direction: column;
    border-radius: 0.5em;
  }
  label {
    margin: 1em 0 0.25em 0;
  }
</style>
