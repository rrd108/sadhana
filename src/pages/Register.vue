<script setup lang="ts">
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'
</script>

<template>
  <section>
    <LoginForm button="Regisztráció" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
</style>
