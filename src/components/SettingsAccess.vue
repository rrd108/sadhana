<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import User from '../types/User'
  import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

  const store = useStore()
  const users = ref([] as User[])
  const userEmail = ref('')

  axios
    .get(`${import.meta.env.VITE_APP_API_URL}users.json`, store.tokenHeader)
    .then(res => (users.value = res.data.users))
    .catch(err => console.error(err))

  const addCounsellor = () => {
    console.log(users.value.find(user => user.email == userEmail.value)?.id)
  }
</script>

<template>
  <h2>Hozzáférések</h2>
  <p>
    Ha szeretnéd, hogy a tanácsadód, barátod láthassa a sadhanád részleteit,
    akkor azt itt engedélyezheted.
  </p>
  <p>Jelenleg rajtad kívűl senkinek sincs hozzáférése.</p>
  <label for="counsellor">Lássa még a részleteket:</label>
  <fieldset>
    <input
      id="counsellor"
      type="email"
      list="users"
      placeholder="Email cím"
      v-model="userEmail"
    />
    <datalist id="users">
      <option v-for="user in users" :key="user.id" :value="user.email" />
    </datalist>
    <button @click="addCounsellor">
      <font-awesome-icon icon="circle-plus" />
    </button>
  </fieldset>
</template>

<style scoped>
  fieldset {
    display: flex;
    align-items: center;
    border: none;
  }
  button {
    background: none;
    border: none;
    color: var(--pinky);
    font-size: 1.5em;
    height: 2em;
    width: 2em;
  }
</style>
