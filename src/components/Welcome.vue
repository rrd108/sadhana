<script setup lang="ts">
  import { useRoute } from 'vue-router'
  import { useStore } from '../store'
  import Login from '@/pages/Login.vue'

  const Register = () => import('@/pages/Register.vue')
  const ForgotPass = () => import('@/pages/ForgotPass.vue')
  const PassReset = () => import('@/pages/PassReset.vue')

  const route = useRoute()
  const store = useStore()
</script>

<template>
  <div>
    <Login v-if="!store.user.id && !['Register', 'ForgotPass', 'PassReset'].includes(route.name?.toString() || '')" />
    <Register v-if="route.name == 'Register'" />
    <ForgotPass v-if="route.name == 'ForgotPass'" />
    <PassReset v-if="route.name == 'PassReset'" />
  </div>
</template>

<style scoped></style>
