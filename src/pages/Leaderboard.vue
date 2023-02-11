<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import dayjs from 'dayjs'
  import weekOfYear from 'dayjs/plugin/weekOfYear'

  dayjs.extend(weekOfYear)

  const store = useStore()

  const currentDate = dayjs()
  const weekNumber = currentDate.week()
  const week = ref(
    `${currentDate.format('YYYY')}-W${
      weekNumber < 10 ? `0${weekNumber}` : weekNumber
    }`
  )

  const list = ref([{ user: '', points: 0 }])
  axios
    .get(
      `${import.meta.env.VITE_APP_API_URL}sadhanas/liststat/${week.value}.json`,
      store.tokenHeader
    )
    .then(res => (list.value = res.data))
    .catch(err => console.error(err))
</script>

<template>
  <h1><input type="week" v-model="week" /></h1>
  <ul>
    <li
      v-for="(user, i) in list"
      :class="{ me: user.user == store.user.email }"
    >
      <span>{{ i + 1 }}</span>
      <span>{{ user.user.split('@')[0] }}</span>
      <span class="right">{{ user.points }}</span>
    </li>
  </ul>
</template>

<style scoped>
  h1 {
    margin-bottom: 2rem;
  }
  li {
    display: grid;
    grid-template-columns: 1fr 4fr 1fr;
    margin-bottom: 0.5em;
    background-color: var(--pinky);
    border-radius: 0.5em;
    padding: 0.25em 0.5em;
    color: var(--dark-purple);
  }
  .right {
    text-align: right;
  }
  .me {
    background-color: #fff;
    color: #000;
  }
</style>
