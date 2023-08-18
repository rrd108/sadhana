<script setup lang="ts">
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { useToast } from 'vue-toastification'
  import router from '../router'

  const store = useStore()
  const toast = useToast()

  const login = (data: { email: string; pass: string }) =>
    axios
      .post(`${import.meta.env.VITE_APP_API_URL}users/login.json`, {
        email: data.email,
        password: data.pass,
      })
      .then(res => {
        store.user = res.data
        router.push('/')
      })
      .catch(err => {
        toast.error(`Sikertelen belépés ${err.code}`)
      })
</script>

<template>
  <section>
    <LoginForm button="Belép" @formSubmit="login" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    margin-top: -10vh;
    text-align: center;
  }
</style>
