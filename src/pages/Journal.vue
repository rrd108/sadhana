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

  const getDateData = (date: Date) => journal.value.find(day => day.date == date.toLocaleDateString())

  const action = route.params.userId == store.user.id ? 'myjournal' : 'journal'

  axios
    .get(
      `${import.meta.env.VITE_APP_API_URL}sadhanas/${action}/${route.params.userId}/${route.params.week}.json`,
      store.tokenHeader
    )
    .then(res => {
      journal.value = res.data.sadhanas.map((day: Journal) => ({
        ...day,
        date: new Date(day.date).toLocaleDateString(),
      }))
      user.value = res.data.user
    })
    .catch(err => console.error(err))
</script>

<template>
  <ul>
    <li v-for="date in dates">
      <h3>
        <span> {{ user.email?.split('@')[0] }}</span>
        <span>{{ date.toLocaleDateString() }}</span>
      </h3>
      <section>
        <h4>
          <span>Japa</span>
          <span>M</span>
          <span>7</span>
          <span>14</span>
          <span>20</span>
          <span>20+</span>
        </h4>
        <p>
          <span>{{
            (getDateData(date)?.japaBeforeMangala || 0) +
            (getDateData(date)?.japaEarly || 0) +
            (getDateData(date)?.japaMorning || 0) +
            (getDateData(date)?.japaAfternoon || 0) +
            (getDateData(date)?.japaNight || 0)
          }}</span>
          <span> {{ getDateData(date)?.japaBeforeMangala || '-' }}</span>
          <span> {{ getDateData(date)?.japaEarly || '-' }}</span>
          <span> {{ getDateData(date)?.japaMorning || '-' }}</span>
          <span> {{ getDateData(date)?.japaAfternoon || '-' }}</span>
          <span> {{ getDateData(date)?.japaNight || '-' }}</span>
        </p>
      </section>
      <section>
        <h4>
          <span></span>
          <span>M</span>
          <span>J</span>
          <span>GP</span>
          <span>L</span>
          <span>GA</span>
        </h4>
        <p>
          <span></span>
          <span> {{ getDateData(date)?.mangala ? '✔' : getDateData(date)?.homeMangala ? '☗' : '-' }}</span>
          <span>{{ getDateData(date)?.japa ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.kirtana ? '✔' : getDateData(date)?.homeGuruPuja ? '☗' : '-' }}</span>
          <span>{{ getDateData(date)?.class ? '✔' : '-' }}</span>
          <span>{{ getDateData(date)?.gauraarati ? '✔' : getDateData(date)?.homeGauraArati ? '☗' : '-' }}</span>
        </p>
      </section>
      <section>
        <h4>
          <span>MS</span>
          <span>Olv</span>
          <span>Tan</span>
          <span>LH</span>
          <span>E</span>
          <span>GY</span>
        </h4>
        <p>
          <span> {{ getDateData(date)?.murtiseva || '-' }}</span>
          <span> {{ getDateData(date)?.reading || '-' }}</span>
          <span>{{ getDateData(date)?.study || '-' }}</span>
          <span> {{ getDateData(date)?.listening || '-' }}</span>
          <span> {{ getDateData(date)?.other || '-' }}</span>
          <span> {{ getDateData(date)?.gayatri || '-' }}</span>
        </p>
      </section>
    </li>
  </ul>
</template>

<style scoped>
  ul {
    list-style: none;
  }
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
    grid-template-columns: repeat(6, 1fr);
    gap: 1em;
    padding: 0.25em;
  }
  p {
    background-color: var(--pinky-dark);
    color: var(--dark-purple);
  }
</style>
