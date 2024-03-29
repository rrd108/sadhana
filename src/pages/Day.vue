<script setup lang="ts">
  import { computed, ref, watch } from 'vue'
  import { useIdle } from '@vueuse/core'
  import axios from 'axios'
  import { POSITION, useToast } from 'vue-toastification'
  import { useStore } from '../store'
  import { onBeforeRouteLeave } from 'vue-router'
  import SadhanaFields from '../types/SadhanaFields'

  const store = useStore()

  let sadhanaConfig = {
    japaBeforeMangala: 0,
    japaEarly: 0,
    japaMorning: 0,
    japaAfternoon: 0,
    japaNight: 0,
    mangala: 0,
    japa: 0,
    kirtana: 0,
    class: 0,
    gauraarati: 0,
    reading: 0,
    study: 0,
    listening: 0,
    other: 0,
    murtiseva: 0,
    gayatri: 0,
    homeMangala: 0,
    homeGuruPuja: 0,
    homeGauraArati: 0,
  } as SadhanaFields
  axios
    .get(`${import.meta.env.VITE_APP_API_URL}sadhanas/getConfig.json`, store.tokenHeader)
    .then(res => (sadhanaConfig = res.data))
    .catch(err => console.error(err))

  const date = ref(new Date().toISOString().substring(0, 10))
  const emptyBhakti = {
    id: 0,
    japaBeforeMangala: 0,
    japaEarly: 0,
    japaMorning: 0,
    japaAfternoon: 0,
    japaNight: 0,
    mangala: false,
    japa: false,
    kirtana: false,
    class: false,
    gauraarati: false,
    reading: 0,
    study: 0,
    listening: 0,
    other: 0,
    murtiseva: 0,
    gayatri: 0,
    homeMangala: false,
    homeGuruPuja: false,
    homeGauraArati: false,
  }
  const bhakti = ref(emptyBhakti)

  const dateChanged = ref(false)
  let initialData = {}
  const getSadhana = () =>
    axios
      .get(`${import.meta.env.VITE_APP_API_URL}sadhanas/${date.value.replace(/\-/g, '')}.json`, store.tokenHeader)
      .then(res => {
        bhakti.value = res.data ?? { ...emptyBhakti }
        dateChanged.value = true
        initialData = { ...bhakti.value }
      })
      .catch(err => console.error(err))

  getSadhana()

  const totalJapa = computed(() => {
    return (
      Number(bhakti.value.japaBeforeMangala) +
      Number(bhakti.value.japaEarly) +
      Number(bhakti.value.japaMorning) +
      Number(bhakti.value.japaAfternoon) +
      Number(bhakti.value.japaNight)
    )
  })

  const points = computed(
    () =>
      Number(bhakti.value.japaBeforeMangala) * sadhanaConfig.japaBeforeMangala +
      Number(bhakti.value.japaEarly) * sadhanaConfig.japaEarly +
      Number(bhakti.value.japaMorning) * sadhanaConfig.japaMorning +
      Number(bhakti.value.japaAfternoon) * sadhanaConfig.japaAfternoon +
      Number(bhakti.value.japaNight) * sadhanaConfig.japaNight +
      Number(bhakti.value.mangala) * sadhanaConfig.mangala +
      Number(bhakti.value.japa) * sadhanaConfig.japa +
      Number(bhakti.value.kirtana) * sadhanaConfig.kirtana +
      Number(bhakti.value.class) * sadhanaConfig.class +
      Number(bhakti.value.gauraarati) * sadhanaConfig.gauraarati +
      Number(bhakti.value.reading) * sadhanaConfig.reading +
      Number(bhakti.value.study) * sadhanaConfig.study +
      Number(bhakti.value.listening) * sadhanaConfig.listening +
      Number(bhakti.value.other) * sadhanaConfig.other +
      Number(bhakti.value.murtiseva) * sadhanaConfig.murtiseva +
      Number(bhakti.value.gayatri) * sadhanaConfig.gayatri +
      Number(bhakti.value.homeMangala) * sadhanaConfig.homeMangala +
      Number(bhakti.value.homeGuruPuja) * sadhanaConfig.homeGuruPuja +
      Number(bhakti.value.homeGauraArati) * sadhanaConfig.homeGauraArati
  )

  const pointsChanged = ref(false)
  watch(points, () => (pointsChanged.value = true))

  const toast = useToast()
  const { idle } = useIdle(3000) // set idle time to 3 seconds
  watch(idle, isIdle => {
    if (JSON.stringify(initialData) == JSON.stringify(bhakti.value)) {
      dateChanged.value = pointsChanged.value = false
      return
    }

    if (isIdle && points.value) {
      if (pointsChanged.value || dateChanged.value) {
        saveData()
      }
    }
  })

  const saveData = () => {
    pointsChanged.value = false
    dateChanged.value = false

    const method = bhakti.value.id ? 'patch' : 'post'
    const id = bhakti.value.id ? `/${bhakti.value.id}` : ''
    axios[method](
      `${import.meta.env.VITE_APP_API_URL}sadhanas${id}.json`,
      { date: date.value, ...bhakti.value },
      store.tokenHeader
    )
      .then(res => {
        bhakti.value = res.data
        toast.success('Mentve')
      })
      .catch(err => {
        console.error(err)
        toast.error('Mentési hiba')
      })
  }

  const checkMax = () => {
    if (bhakti.value.murtiseva > 120) {
      toast.warning('Ugye ez mind szabadidős murti-seva volt?', {
        timeout: 10000,
        position: POSITION.BOTTOM_CENTER,
      })
    }
  }

  onBeforeRouteLeave((to, from, next) => {
    if (JSON.stringify(initialData) != JSON.stringify(bhakti.value) && (pointsChanged.value || dateChanged.value)) {
      saveData()
    }
    next()
  })
</script>

<template>
  <section>
    <header class="rows center">
      <h1>{{ points }}</h1>
      <div>
        <label for="date">{{$t('date')}}</label>
        <input type="date" v-model="date" @change="getSadhana" class="wide" />
      </div>
    </header>

    <div class="rows">
      <h2>{{$t('temple.japa')}}</h2>
      <h2 class="center">{{ totalJapa }}</h2>
    </div>

    <div class="rows four">
      <p>{{$t('japa.beforeMangala')}}</p>
      <input type="number" v-model="bhakti.japaBeforeMangala" />

      <p>{{$t('japa.afternoon')}}</p>
      <input type="number" v-model="bhakti.japaAfternoon" />

      <p>{{$t('japa.early')}}</p>
      <input type="number" v-model="bhakti.japaEarly" />

      <p>{{$t('japa.night')}}</p>
      <input type="number" v-model="bhakti.japaNight" />

      <p>{{$t('japa.morning')}}</p>
      <input type="number" v-model="bhakti.japaMorning" />
    </div>
    <hr />

    <div class="rows">
      <h2 class="title">{{$t('temple.program')}}</h2>

      <div class="radio">
        <input type="checkbox" id="mangala" v-model="bhakti.mangala" />
        <label for="mangala">{{$t('temple.mangala')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="japa" v-model="bhakti.japa" />
        <label for="japa">{{$t('temple.japa')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="kirtana" v-model="bhakti.kirtana" />
        <label for="kirtana">{{$t('temple.kirtana')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="class" v-model="bhakti.class" />
        <label for="class">{{$t('temple.lecture')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="gauraarati" v-model="bhakti.gauraarati" />
        <label for="gauraarati">{{$t('temple.gauraarati')}}</label>
      </div>
    </div>
    <hr />

    <div class="rows">
      <h2 class="title">{{$t('home')}}</h2>

      <div class="radio">
        <input type="checkbox" id="homeMangala" v-model="bhakti.homeMangala" />
        <label for="homeMangala">{{$t('temple.mangala')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="homeGuruPuja" v-model="bhakti.homeGuruPuja" />
        <label for="homeGuruPuja">{{$t('temple.gurupuja')}}</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="homeGauraArati" v-model="bhakti.homeGauraArati" />
        <label for="homeGauraArati">{{$t('temple.gauraarati')}}</label>
      </div>
    </div>
    <hr />

    <div class="rows third">
      <h2 class="title">{{$t('bramin.activities')}}</h2>

      <p>{{$t('bramin.murtiseva')}}</p>
      <input type="number" v-model="bhakti.murtiseva" @blur="checkMax" />

      <p>{{$t('bramin.reading')}}</p>
      <input type="number" v-model="bhakti.reading" />

      <p>{{$t('bramin.study')}}</p>
      <input type="number" v-model="bhakti.study" />

      <p>{{$t('bramin.listening')}}</p>
      <input type="number" v-model="bhakti.listening" />

      <p>{{$t('bramin.other')}}</p>
      <input type="number" v-model="bhakti.other" />

      <p>{{$t('bramin.gayatri')}}</p>
      <input type="number" v-model="bhakti.gayatri" />
    </div>
  </section>
</template>

<style scoped>
  section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
    margin-bottom: 1em;
  }
  .rows {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 0.5em;
    align-items: center;
  }
  .four {
    grid-template-columns: 3fr 2fr 3fr 2fr;
  }
  .third {
    grid-template-columns: 2fr 1fr;
  }
  .title {
    grid-column: 1 / -1;
  }
  .radio {
    display: flex;
    gap: 0.5em;
  }
  .radio input {
    width: auto;
    margin: 0;
  }
  h1 {
    font-size: 4rem;
  }
  input {
    width: 50%;
    text-align: center;
    margin: 0 auto;
  }
  h2,
  p {
    margin: 0;
  }
  .wide {
    width: 100%;
  }
</style>
