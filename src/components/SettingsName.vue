<script setup lang="ts">
  import axios from 'axios'
  import { useStore } from '../store'

  const store = useStore()

  const saveName = () =>
    axios
      .patch(
        `${import.meta.env.VITE_APP_API_URL}users/${store.user.id}.json`,
        { name: store.user.name },
        store.tokenHeader
      )
      .catch(err => console.error(err))
</script>

<template>
  <h2>Név</h2>
  <p class="info" v-if="!store.user.name">Hiányzik a neved, kérlek add meg!</p>
  <p>
    Ezzel a névvel kerülsz fel a listákra és a Śrīla Śivarāma Swāmi Mahārāja
    számára szóló listára.
  </p>
  <label>
    Neved:
    <input
      type="text"
      v-model="store.user.name"
      @blur="saveName"
      placeholder="Neved"
    />
  </label>
</template>

<style scoped></style>
