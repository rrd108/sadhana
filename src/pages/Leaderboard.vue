<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useRouter } from 'vue-router'
  import { useStore } from '../store'
  import { today, todayWeekNumber } from '../composables/getDateData'

  const store = useStore()
  const router = useRouter()

  const week = ref(
    `${today.getFullYear()}-W${
      todayWeekNumber < 10 ? `0${todayWeekNumber}` : todayWeekNumber
    }`
  )

  const list = ref([{ userName: '', userEmail: '', user_id: '', points: 0 }])
  const getStat = () =>
    axios
      .get(
        `${import.meta.env.VITE_APP_API_URL}sadhanas/liststat/${
          week.value
        }.json`,
        store.tokenHeader
      )
      .then(res => (list.value = res.data))
      .catch(err => console.error(err))

  getStat()

  const weekChange = (direction: string) => {
    const [year, weekNum] = week.value.split('-W').map(Number)
    if (direction == 'decrease') {
      week.value = `${year}-W${(weekNum - 1).toString().padStart(2, '0')}`
    }
    if (direction == 'increase') {
      week.value = `${year}-W${(weekNum + 1).toString().padStart(2, '0')}`
    }
    getStat()
  }

  const showJournal = (user: { user_id: string }) => {
    if (
      store.user.id == user.user_id ||
      store.user.counsulees.find(c => c == user.user_id)
    ) {
      router.push(`/journal/${user.user_id}/${week.value}`)
    }
  }
</script>

<template>
  <section>
    <h1>
      <font-awesome-icon
        icon="chevron-circle-left"
        @click="weekChange('decrease')"
      />
      <input type="week" v-model="week" @change="getStat" />
      <font-awesome-icon
        icon="chevron-circle-right"
        @click="weekChange('increase')"
      />
    </h1>
    <ul>
      <li
        v-for="(user, i) in list"
        :class="{
          me: user.userEmail == store.user.email,
          counsulee: store.user.counsulees.find(c => c == user.user_id),
        }"
        @click="showJournal(user)"
      >
        <span>{{ i + 1 }}</span>
        <span>{{ user.userName ?? user.userEmail.split('@')[0] }}</span>
        <span class="right">{{ Math.round(user.points) }}</span>
      </li>
    </ul>
  </section>
</template>

<style scoped>
  h1 {
    margin-bottom: 2rem;
    display: flex;
    justify-content: center;
    align-items: center;
    gap: 1em;
    color: var(--pinky);
  }
  li {
    display: grid;
    grid-template-columns: 1fr 4fr 1fr;
    margin-bottom: 0.75em;
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
  .counsulee {
    background-color: var(--medium-purple);
    color: var(--pinky);
  }
</style>
