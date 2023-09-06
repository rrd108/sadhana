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
  <h2>{{$t('settings.name')}}</h2>
  <p class="info" v-if="!store.user.name">{{$t('settings.name_missing')}}</p>
  <p>{{$t('settings.name_info')}}</p>
  <label>
    {{$t('settings.name_label')}}:
    <input
      type="text"
      v-model="store.user.name"
      @blur="saveName"
      :placeholder="$t('settings.name_label')"
    />
  </label>
</template>

<style scoped></style>
