<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { GChart } from 'vue-google-charts'

  const store = useStore()

  const sadhanaData = ref({
    japa: [['Date', 'Points']],
    templeProgram: [['Date', 'Points']],
    brahmana: [['Date', 'Points']],
  })
  axios
    .get(
      `${import.meta.env.VITE_APP_API_URL}sadhanas/stat/20230206-20230212.json`, // TODO hardcoded
      store.tokenHeader
    )
    .then(res => {
      sadhanaData.value.japa.push(
        ...res.data.map((d: { date: string; japa: number }) => [
          d.date.substring(5),
          d.japa,
        ])
      )

      sadhanaData.value.templeProgram.push(
        ...res.data.map((d: { date: string; templeProgram: number }) => [
          d.date.substring(5),
          d.templeProgram,
        ])
      )

      sadhanaData.value.brahmana.push(
        ...res.data.map((d: { date: string; brahmana: number }) => [
          d.date.substring(5),
          d.brahmana,
        ])
      )
    })
    .catch(err => console.error(err))

  const options = {
    legend: 'none',
    dataLabels: true,
    hAxis: {
      title: 'Nap',
    },
    vAxis: {
      title: 'Pontszám',
    },
  }
</script>

<template>
  <h1>2023. február 6 -12.</h1>
  <h2>Japa</h2>
  <GChart type="AreaChart" :data="sadhanaData.japa" :options="options" />

  <h2>Templomi program</h2>
  <GChart
    type="AreaChart"
    :data="sadhanaData.templeProgram"
    :options="options"
  />

  <h2>Brahmana</h2>
  <GChart type="AreaChart" :data="sadhanaData.brahmana" :options="options" />
</template>

<style scoped>
  h2 {
    margin-top: 1em;
  }
</style>
