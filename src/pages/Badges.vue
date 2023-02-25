<script setup lang="ts">
  import { computed, ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { useToast } from 'vue-toastification'
  import Badge from '../types/Badge'

  const store = useStore()
  const toast = useToast()

  const getImagePath = computed(
    () => (icon: string) =>
      new URL(`/src/assets/${icon}.png`, import.meta.url).toString()
  )

  const selectedBadge = ref({ id: 0 } as Badge)
  const lookDetails = (badge: Badge) => {
    selectedBadge.value = badge
    if (!badge._joinData.accepted) {
    axios
      .patch(
        `${import.meta.env.VITE_APP_API_URL}badges-users/${
          badge._joinData.id
        }.json`,
        { accepted: true },
        store.tokenHeader
      )
      .then(res => {
        toast.success('Jelvény elfogadva')
        badge._joinData.accepted = true
      })
      .catch(err => toast.error('Mentési hiba'))
  }}

  const hide = () => {
    selectedBadge.value = { id: 0 } as Badge
  }
</script>

<template>
  <section>
    <h1>Jelvények</h1>
    <ul>
      <li
        v-for="badge in store.user.badges"
        class="center"
        :class="{ accepted: badge._joinData.accepted }"
        @click="lookDetails(badge)"
      >
        <h2>{{ badge.name }}</h2>
        <img :src="getImagePath(badge.icon)" />
        <h3 v-if="badge.level">{{ badge.level }}. szint</h3>
        <h3 v-if="!badge.level">{{ badge._joinData.created }}</h3>
      </li>
    </ul>

    <div v-if="selectedBadge.id" class="center" @click="hide">
      <h2>{{ selectedBadge.name }}</h2>
      <img :src="getImagePath(selectedBadge.icon)" />
      <h3 v-if="selectedBadge.level">{{ selectedBadge.level }}. szint</h3>
      <h3 v-if="!selectedBadge.level">{{ selectedBadge._joinData.created }}</h3>
      <p>{{ selectedBadge.description }}</p>
      <small>{{ selectedBadge._joinData.created }}</small>
    </div>
  </section>
</template>

<style scoped>
  h1 {
    margin-bottom: 1em;
  }
  ul {
    list-style: none;
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 1em;
  }
  li,
  div {
    color: var(--dark-purple);
    background-color: var(--pinky);
    border-radius: 0.5em;
    padding: 1em;
  }
  li {
    filter: grayscale(75%);
  }
  li.accepted {
    filter: none;
  }
  div {
    position: absolute;
    top: 10vh;
    width: 90vw;
  }
  h2 {
    margin-bottom: 1em;
  }
  img {
    width: 100%;
    background-color: var(--dark-purple);
    border-radius: 50%;
    padding: 1em;
    margin-bottom: 1em;
  }
</style>
