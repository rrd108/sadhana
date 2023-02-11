<script setup lang="ts">
  import axios from 'axios'
  import { useRoute, useRouter } from 'vue-router'
  import { useToast } from 'vue-toastification'
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'

  const route = useRoute()
  const router = useRouter()
  const toast = useToast()

  const passReset = (data: { pass: string }) => {
    axios
      .patch(`${import.meta.env.VITE_APP_API_URL}users/passreset.json`, {
        id: route.params.userId,
        tempPass: route.params.tempPass,
        pass: data.pass,
      })
      .then(res => {
        toast.success('A jelszó frissítve, jelentkezz be!')
        router.push('/')
      })
      .catch(err => {
        console.error(err)
      })
  }
</script>

<template>
  <section>
    <LoginForm button="Jelszó módosítás" passConfirm @formSubmit="passReset" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
</style>
