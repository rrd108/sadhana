<script setup lang="ts">
  import { computed, ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { GChart } from 'vue-google-charts'
  import { today, todayWeekNumber } from '../composables/getDateData'
  import { useI18n } from 'vue-i18n'

  const store = useStore()
  const { t } = useI18n()
  const emptyData = ['Date', 'Points']
  const sadhanaData = ref({
    japa: [[...emptyData]],
    templeProgram: [[...emptyData]],
    brahmana: [[...emptyData]],
  })

  const weekDataEmptyJapa = ['Date', '-7', '7', '14', '20', '20+']
  const weekDataEmptyProgram = ['Date', '-7', '7', '14', '20', '20+']
  const weekData = ref({
    japa: [[...weekDataEmptyJapa]],
    program: [[]],
  })

  const week = ref(`${today.getFullYear()}-W${todayWeekNumber < 10 ? `0${todayWeekNumber}` : todayWeekNumber}`)

  const getStatWeek = () =>
    axios
      .get(`${import.meta.env.VITE_APP_API_URL}sadhanas/myjournal/${store.user.id}/${week.value}.json`, store.tokenHeader)
      .then(res => {
        weekData.value = {
          japa: [[...weekDataEmptyJapa]],
          program: [[]],
        }
        weekData.value.japa.push(
          ...res.data.sadhanas.map(
            (d: { date: string; japaBeforeMangala: number; japaEarly: number; japaMorning: number; japaAfternoon: number; japaNight: number }) => [
              d.date.substring(5),
              d.japaBeforeMangala,
              d.japaEarly,
              d.japaMorning,
              d.japaAfternoon,
              d.japaNight,
            ]
          )
        )
      })
      .catch(err => console.error(err))

  const getStat = () =>
    axios
      .get(`${import.meta.env.VITE_APP_API_URL}sadhanas/mystat/${week.value}.json`, store.tokenHeader)
      .then(res => {
        sadhanaData.value = {
          japa: [[...emptyData]],
          templeProgram: [[...emptyData]],
          brahmana: [[...emptyData]],
        }
        sadhanaData.value.japa.push(...res.data.map((d: { date: string; japa: number }) => [d.date.substring(5), d.japa]))
        sadhanaData.value.templeProgram.push(...res.data.map((d: { date: string; templeProgram: number }) => [d.date.substring(5), d.templeProgram]))

        sadhanaData.value.brahmana.push(...res.data.map((d: { date: string; brahmana: number }) => [d.date.substring(5), d.brahmana]))
      })
      .catch(err => console.error(err))

  const getStats = () => {
    getStat()
    getStatWeek()
  }
  getStats()

  const weekChange = (direction: string) => {
    const [year, weekNum] = week.value.split('-W').map(Number)
    if (direction == 'decrease') {
      week.value = `${year}-W${(weekNum - 1).toString().padStart(2, '0')}`
    }
    if (direction == 'increase') {
      week.value = `${year}-W${(weekNum + 1).toString().padStart(2, '0')}`
    }
    getStats()
  }

  const options = {
    legend: 'none',
    dataLabels: true,
    hAxis: {
      title: t('stat.day'),
    },
    vAxis: {
      title: t('stat.score'),
    },
  }

  const info = computed(() => ({
    avg: {
      japa:
        sadhanaData.value.japa.reduce((a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])), 0) /
        sadhanaData.value.japa.reduce((count, b) => (!isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count), 0),
      templeProgram:
        sadhanaData.value.templeProgram.reduce((a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])), 0) /
        sadhanaData.value.templeProgram.reduce((count, b) => (!isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count), 0),
      brahmana:
        sadhanaData.value.brahmana.reduce((a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])), 0) /
        sadhanaData.value.brahmana.reduce((count, b) => (!isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count), 0),
    },
    min: {
      japa: Math.min(...sadhanaData.value.japa.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
      templeProgram: Math.min(...sadhanaData.value.templeProgram.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
      brahmana: Math.min(...sadhanaData.value.brahmana.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
    },
    max: {
      japa: Math.max(...sadhanaData.value.japa.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
      templeProgram: Math.max(...sadhanaData.value.templeProgram.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
      brahmana: Math.max(...sadhanaData.value.brahmana.map((d: any) => (isNaN(parseInt(d[1])) ? 0 : d[1]))),
    },
  }))
</script>

<template>
  <section>
    <h1>
      <font-awesome-icon icon="chevron-circle-left" @click="weekChange('decrease')" />
      <input type="week" v-model="week" @change="getStats" />
      <font-awesome-icon icon="chevron-circle-right" @click="weekChange('increase')" />
    </h1>
    <div class="title">
      <h2>{{ $t('temple.japa') }}</h2>
    </div>
    <div class="grid-container">
      <span class="grid-row" v-for="(row, index) in weekData.japa" v-bind:class="{ row0: index == 0 }">
        <span class="grid-column" v-for="(column, index) in row" v-bind:class="{ column0: index == 0 }"
          ><span v-if="Number(column) == 0"></span><span v-else>{{ column }}</span></span
        >
      </span>
    </div>
    <div class="title">
      <h2>{{ $t('temple.japa') }}</h2>
      <h3>
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.min.japa) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.avg.japa) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.max.japa) }}
      </h3>
    </div>
    <GChart type="AreaChart" :data="sadhanaData.japa" :options="options" />

    <div class="title">
      <h2>{{ $t('temple.program') }}</h2>
      <h3>
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.min.templeProgram) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.avg.templeProgram) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.max.templeProgram) }}
      </h3>
    </div>
    <GChart type="AreaChart" :data="sadhanaData.templeProgram" :options="options" />

    <div class="title">
      <h2>{{ $t('bramin.activities') }}</h2>
      <h3>
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.min.brahmana) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.avg.brahmana) }}
        /
        {{ Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(info.max.brahmana) }}
      </h3>
    </div>
    <GChart type="AreaChart" :data="sadhanaData.brahmana" :options="options" />
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
  .title {
    margin-top: 1em;
    display: flex;
    justify-content: space-between;
    align-items: center;
  }
  h3 {
    font-weight: normal;
    font-size: 1rem;
  }
  .table {
    color: black;
  }
</style>
<style>
  .grid-row {
    grid-template-columns: repeat(6, auto);
    display: grid;
  }
  .grid-column {
    min-width: 3em;
    text-align: center;
  }
  .row0 {
    border-bottom-style: solid;
    border-width: thin;
  }
  .column0 {
    border-right-style: solid;
    border-width: thin;
  }
</style>
