<script setup lang="ts">
  import LoginForm from '@/components/LoginForm.vue'
  import LoginFooter from '@/components/LoginFooter.vue'

  const forgotpass = () => {
    console.log('forgotpass')
  }
</script>

<template>
  <section>
    <LoginForm
      button="Jelszó emlékeztető"
      :showPass="false"
      @formSubmit="forgotpass"
    />
    <LoginFooter />
  </section>
</template>

<style scoped>
  section {
    text-align: center;
  }
</style>
