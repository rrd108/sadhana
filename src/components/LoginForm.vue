<script setup lang="ts">
  import { ref } from 'vue'
  const props = defineProps({
    showPass: { type: Boolean, default: true },
    passConfirm: { type: Boolean, default: false },
  })

  const emit = defineEmits<{
    (e: 'formSubmit', data: {}): void
  }>()

  const email = ref('')
  const pass = ref('')
  const pass_confirm = ref('')

  const login = () =>
    emit('formSubmit', { email: email.value, pass: pass.value })
</script>

<template>
  <img alt="Sadhana" src="@/assets/logo.png" />
  <form @submit.prevent="login">
    <label for="email" v-if="!passConfirm">{{ $t('account.email') }}</label>
    <input type="email" v-if="!passConfirm" v-model="email" />

    <label for="password" v-if="showPass">{{ $t('account.password') }}</label>
    <input type="password" v-if="showPass" v-model="pass" />

    <label for="pass_confirm" v-if="passconfirm">{{ $t('account.passwordConfirm') }}</label>
    <input type="password" v-if="passConfirm" v-model="pass_confirm" />

    <input type="submit" :value="$t('button.login')" />
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
