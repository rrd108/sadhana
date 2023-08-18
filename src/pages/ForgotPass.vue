<script setup lang="ts">
  import axios from 'axios'
  import { useToast } from 'vue-toastification'
  import { useRouter } from 'vue-router'
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'

  const router = useRouter()
  const toast = useToast()

  const forgotpass = (data: { email: string }) =>
    axios
      .post(`${import.meta.env.VITE_APP_API_URL}users/forgotpass.json`, {
        email: data.email,
      })
      .then(res => {
        toast.success('Emailben elküldtük, hogy tudsz bejelentkezni!')
        router.push('/')
      })
      .catch(err => {
        console.error(err)
      })
</script>

<template>
  <section>
    <LoginForm button="Jelszó emlékeztető" :showPass="false" @formSubmit="forgotpass" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    margin-top: -10vh;
    text-align: center;
  }
</style>
