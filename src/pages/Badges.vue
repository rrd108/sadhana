<script setup lang="ts">
  import { computed, ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { useToast } from 'vue-toastification'
  import Badge from '../types/Badge'
  const store = useStore()
  const toast = useToast()

  const getImagePath = computed(() => (icon: string) => new URL(`/src/assets/${icon}.png`, import.meta.url).toString())

  const selectedBadge = ref({ id: 0 } as Badge)
  const lookDetails = (badge: Badge) => {
    selectedBadge.value = badge

    console.log('TODO get history')

    if (!badge.accepted) {
      axios
        .patch(
          `${import.meta.env.VITE_APP_API_URL}badges-users/${badge.badgesUsersId}.json`,
          { accepted: true },
          store.tokenHeader
        )
        .then(res => {
          toast.success('Jelvény elfogadva')
          badge.accepted = true
        })
        .catch(err => toast.error('Mentési hiba'))
    }
  }

  const hide = () => {
    selectedBadge.value = { id: 0 } as Badge
  }
</script>

<template>
  <section>
    <h1>{{$t('badge.badges')}}</h1>
    <ul>
      <li
        v-for="badge in store.user.badges"
        class="center"
        :class="{ accepted: badge.accepted }"
        @click="lookDetails(badge)"
      >
        <h2>{{ $t('badge.'+badge.icon+'.name') }}</h2>
        <h3 v-if="badge.level">{{ badge.level }}. szint</h3>
        <img :src="getImagePath(badge.icon)" />
        <small>{{ badge.gained }}</small>
      </li>
    </ul>

    <div id="overlay" v-if="selectedBadge.id"></div>
    <dialog v-if="selectedBadge.id" class="center" @click="hide">
      <h2>{{ $t('badge.'+selectedBadge.icon+'.name') }}</h2>
      <img :src="getImagePath(selectedBadge.icon)" />
      <h3 v-if="selectedBadge.level">{{ selectedBadge.level }}. szint</h3>
      <h3 v-if="!selectedBadge.level">{{ selectedBadge.gained }}</h3>
      <p>{{$t('badge.'+selectedBadge.icon+'.description').replace('*',selectedBadge.goal)}}</p>
      <small>{{ selectedBadge.gained }}</small>
    </dialog>
  </section>
</template>

<style scoped>
  h1 {
    margin-bottom: 1em;
  }
  h2 {
    margin-bottom: 0.5em;
  }
  ul {
    list-style: none;
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 1em;
  }
  dialog {
    top: 10vh;
    width: 90vw;
    margin-inline: auto;
    display: block;
    background-color: var(--pinky-dark);
    border: none;
    z-index: 1;
  }
  #overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
  }
  li,
  dialog {
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
  li small {
    font-size: 0.75em;
  }
  h3 {
    font-size: 0.8rem;
  }
  img {
    width: 100%;
    background-color: var(--dark-purple);
    border-radius: 50%;
    padding: 0.25em;
    margin: 0.5em 0;
  }
  p {
    color: var(--dark-purple);
  }
</style>
