<script setup lang="ts">
  import { ref } from 'vue'

  const props = defineProps({
    button: { type: String, required: true },
    showPass: { type: Boolean, default: true },
  })

  const emit = defineEmits<{
    (e: 'formSubmit', data: {}): void
  }>()

  const email = ref('')
  const pass = ref('')
  const login = () =>
    emit('formSubmit', { email: email.value, pass: pass.value })
</script>

<template>
  <img alt="Sadhana" src="@/assets/logo.png" />
  <form @submit.prevent="login">
    <label for="email">Email</label>
    <input type="email" v-model="email" />
    <label for="password" v-if="showPass">Password</label>
    <input type="password" v-if="showPass" v-model="pass" />
    <input type="submit" :value="button" />
  </form>
</template>

<style scoped>
  img {
    transform: translateY(2.25em);
    position: relative;
    z-index: 1;
  }
  form {
    transform: translateY(-5em);
    padding: 2em;
    padding-top: 5em;
    background-color: #fff;
    color: var(--dark-purple);
    display: flex;
    flex-direction: column;
    border-radius: 0.5em;
    margin: 0 1em;
  }
  label {
    margin: 1em 0 0.25em 0;
  }
</style>
