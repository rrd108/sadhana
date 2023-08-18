<script setup lang="ts">
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'
  import { useToast } from 'vue-toastification'

  const router = useRouter()
  const toast = useToast()

  const register = (data: { email: string; pass: string }) =>
    axios
      .post(`${import.meta.env.VITE_APP_API_URL}users/register.json`, {
        email: data.email,
        password: data.pass,
      })
      .then(res => {
        toast.success('Sikeres regisztráció, lépj be!')
        router.push('/')
      })
      .catch(err => {
        console.error(err)
      })
</script>

<template>
  <section>
    <LoginForm button="Regisztráció" @formSubmit="register" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    margin-top: -10vh;
    text-align: center;
  }
</style>
