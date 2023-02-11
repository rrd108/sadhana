<script setup lang="ts">
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'
  import axios from 'axios'
  import { useStore } from '../store'

  const store = useStore()

  const login = (data: { email: string; pass: string }) =>
    axios
      .post(`${import.meta.env.VITE_APP_API_URL}users/login.json`, {
        email: data.email,
        password: data.pass,
      })
      .then(res => {
        store.user = res.data
      })
      .catch(err => {
        console.error(err)
      })
</script>

<template>
  <section>
    <LoginForm button="Belép" @formSubmit="login" />
    <LoginFooter page="login" />
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
</style>
