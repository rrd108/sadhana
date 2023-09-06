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

  const week = ref(
    `${today.getFullYear()}-W${
      todayWeekNumber < 10 ? `0${todayWeekNumber}` : todayWeekNumber
    }`
  )

  const getStat = () =>
    axios
      .get(
        `${import.meta.env.VITE_APP_API_URL}sadhanas/mystat/${week.value}.json`,
        store.tokenHeader
      )
      .then(res => {
        sadhanaData.value = {
          japa: [[...emptyData]],
          templeProgram: [[...emptyData]],
          brahmana: [[...emptyData]],
        }
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

  getStat()

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
        sadhanaData.value.japa.reduce(
          (a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])),
          0
        ) /
        sadhanaData.value.japa.reduce(
          (count, b) =>
            !isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count,
          0
        ),
      templeProgram:
        sadhanaData.value.templeProgram.reduce(
          (a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])),
          0
        ) /
        sadhanaData.value.templeProgram.reduce(
          (count, b) =>
            !isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count,
          0
        ),
      brahmana:
        sadhanaData.value.brahmana.reduce(
          (a, b) => (isNaN(parseInt(b[1])) ? 0 : a + parseFloat(b[1])),
          0
        ) /
        sadhanaData.value.brahmana.reduce(
          (count, b) =>
            !isNaN(parseInt(b[1])) && parseInt(b[1]) > 0 ? count + 1 : count,
          0
        ),
    },
    min: {
      japa: Math.min(
        ...sadhanaData.value.japa.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
      templeProgram: Math.min(
        ...sadhanaData.value.templeProgram.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
      brahmana: Math.min(
        ...sadhanaData.value.brahmana.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
    },
    max: {
      japa: Math.max(
        ...sadhanaData.value.japa.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
      templeProgram: Math.max(
        ...sadhanaData.value.templeProgram.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
      brahmana: Math.max(
        ...sadhanaData.value.brahmana.map((d: any) =>
          isNaN(parseInt(d[1])) ? 0 : d[1]
        )
      ),
    },
  }))
</script>

<template>
  <section>
    <h1><input type="week" v-model="week" @change="getStat" /></h1>
    <div class="title">
      <h2>{{$t('temple.japa')}}</h2>
      <h3>
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.min.japa
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.avg.japa
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.max.japa
          )
        }}
      </h3>
    </div>
    <GChart type="AreaChart" :data="sadhanaData.japa" :options="options" />

    <div class="title">
      <h2>{{$t('temple.program')}}</h2>
      <h3>
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.min.templeProgram
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.avg.templeProgram
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.max.templeProgram
          )
        }}
      </h3>
    </div>
    <GChart
      type="AreaChart"
      :data="sadhanaData.templeProgram"
      :options="options"
    />

    <div class="title">
      <h2>{{$t('bramin.activities')}}</h2>
      <h3>
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.min.brahmana
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.avg.brahmana
          )
        }}
        /
        {{
          Intl.NumberFormat(undefined, { maximumFractionDigits: 1 }).format(
            info.max.brahmana
          )
        }}
      </h3>
    </div>
    <GChart type="AreaChart" :data="sadhanaData.brahmana" :options="options" />
  </section>
</template>

<style scoped>
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
</style>
