<script setup lang="ts">
import { ref } from "vue";
const props = defineProps({
  button: { type: String, required: true },
  showPass: { type: Boolean, default: true },
  passConfirm: { type: Boolean, default: false },
});

const emit = defineEmits<{
  (e: "formSubmit", data: {}): void;
}>();

const email = ref("");
const pass = ref("");
const pass_confirm = ref("");

const login = () =>
  emit("formSubmit", { email: email.value, pass: pass.value });
</script>

<template>
    <img alt="Sadhana" src="@/assets/logo.png" />
    <form @submit.prevent="login">
      <label for="email" v-if="!passConfirm">{{ $t("account.email") }}</label>
      <input type="email" v-if="!passConfirm" v-model="email" />

      <label for="password" v-if="showPass">{{ $t("account.password") }}</label>
      <input type="password" v-if="showPass" v-model="pass" />

      <label for="pass_confirm" v-if="passConfirm">{{
        $t("account.passwordConfirm")
      }}</label>
      <input type="password" v-if="passConfirm" v-model="pass_confirm" />

      <input type="submit" :value="button" />
    </form>
</template>

<style scoped>
img {
  position: absolute;
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  width: 250px; /* Need a specific value to work */
  z-index: 1;
}
form {
  padding: 2em;
  padding-top: 5em;
  background-color: #fff;
  color: var(--dark-purple);
  display: flex;
  flex-direction: column;
  border-radius: 0.5em;
  position: relative;
  top: 120px;
  left: 0;
  right: 0;
  margin-left: auto;
  margin-right: auto;
  width: 40em; /* Need a specific value to work */
  max-width: 90vw;
}
label {
  margin: 1em 0 0.25em 0;
}
</style>
