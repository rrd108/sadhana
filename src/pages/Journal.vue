<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRoute } from 'vue-router'
  import { useStore } from '../store'
  import { getDatesInWeek } from '../composables/getDateData'
  import Journal from '../types/Journal'
  import User from '../types/User'

  const route = useRoute()
  const store = useStore()

  const journal = ref([] as Journal[])
  const user = ref({} as User)
  const dates = getDatesInWeek(route.params.week as string)

  const getDateData = (date: Date) =>
    journal.value.find(day => day.date == date.toISOString().slice(0, 10))

  axios
    .get(
      `${import.meta.env.VITE_APP_API_URL}sadhanas/journal/${
        route.params.userId
      }/${route.params.week}.json`,
      store.tokenHeader
    )
    .then(res => {
      journal.value = res.data.sadhanas
      user.value = res.data.user
    })
    .catch(err => console.error(err))
</script>

<template>
  <ul>
    <li v-for="date in dates">
      <h3>
        <span> {{ user.email?.split('@')[0] }}</span>
        <span>{{ date.toISOString().slice(0, 10) }}</span>
      </h3>
      <section>
        <h4>
          <span>Japa</span>
          <span>0-7</span>
          <span>7-14</span>
          <span>14-20</span>
          <span>20-24</span>
        </h4>
        <p>
          <span>{{
            (getDateData(date)?.japaEarly || 0) +
            (getDateData(date)?.japaMorning || 0) +
            (getDateData(date)?.japaAfternoon || 0) +
            (getDateData(date)?.japaNight || 0)
          }}</span>
          <span> {{ getDateData(date)?.japaEarly || '-' }}</span>
          <span> {{ getDateData(date)?.japaMorning || '-' }}</span>
          <span> {{ getDateData(date)?.japaAfternoon || '-' }}</span>
          <span> {{ getDateData(date)?.japaNight || '-' }}</span>
        </p>
      </section>
      <section>
        <h4>
          <span>M</span>
          <span>J</span>
          <span>K</span>
          <span>L</span>
          <span>GA</span>
        </h4>
        <p>
          <span> {{ getDateData(date)?.mangala ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.japa ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.kirtana ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.class ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.gauraarati ? '✔' : '-' }}</span>
        </p>
      </section>
      <section>
        <h4>
          <span></span>
          <span>Olvasás</span>
          <span>Tanulás</span>
          <span>MS</span>
        </h4>
        <p>
          <span></span>
          <span> {{ getDateData(date)?.reading || '-' }}</span>
          <span>{{ getDateData(date)?.study || '-' }}</span>
          <span> {{ getDateData(date)?.murtiseva || '-' }}</span>
        </p>
      </section>
    </li>
  </ul>
</template>

<style scoped>
  li {
    margin-bottom: 1em;
    background-color: var(--pinky);
    border-radius: 0.5em;
    padding: 0.25em 0.5em;
    color: var(--dark-purple);
    text-align: center;
  }

  section {
    margin: 1em 0;
  }
  h3 {
    background-color: var(--medium-purple);
    color: #fff;
    border-radius: 0.5em;
    padding: 0.25em;
    display: flex;
    justify-content: space-around;
  }
  h4,
  p {
    display: grid;
    grid-template-columns: repeat(5, 1fr);
    gap: 1em;
    padding: 0.25em;
  }
  p {
    background-color: var(--pinky-dark);
  }
</style>
