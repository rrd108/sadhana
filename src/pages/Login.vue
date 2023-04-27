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
        store.user.counsellors = JSON.parse(res.data.counsellors)
        router.push('/')
      })
      .catch(err => {
        toast.error(err.response.data.message)
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
    text-align: center;
  }
</style>
