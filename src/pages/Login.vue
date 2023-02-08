<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'

  const store = useStore()
  const email = ref('')
  const pass = ref('')
  const login = () => {
    axios
      .post(`${import.meta.env.VITE_APP_API_URL}users/login.json`, {
        email: email.value,
        password: pass.value,
      })
      .then(res => {
        store.user = res.data
      })
      .catch(err => {
        console.error(err)
      })
  }
</script>

<template>
  <section>
    <img alt="Sadhana" src="@/assets/logo.png" />
    <form @submit.prevent="login">
      <label for="email">Email</label>
      <input type="email" v-model="email" />
      <label for="password">Password</label>
      <input type="password" v-model="pass" />
      <input type="submit" value="Belép" />
    </form>
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
  img {
    transform: translateY(2.25em);
    position: relative;
    z-index: 1;
  }
  form {
    transform: translateY(-5em);
    padding: 2em;
    padding-top: 5em;
    background-color: #fff;
    color: var(--dark-purple);
    display: flex;
    flex-direction: column;
    border-radius: 0.5em;
    margin: 0 1em;
  }
  label {
    margin: 1em 0 0.25em 0;
  }
</style>
