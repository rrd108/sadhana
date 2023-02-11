<script setup lang="ts">
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'
</script>

<template>
  <section>
    <LoginForm button="Jelszó emlékeztető" :showPass="false" />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
</style>
