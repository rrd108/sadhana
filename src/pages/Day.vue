<script setup lang="ts">
  import { computed, ref, watch } from 'vue'
  import { useIdle } from '@vueuse/core'
  import axios from 'axios'
  import { POSITION, useToast } from 'vue-toastification'
  import { useStore } from '../store'
  import { onBeforeRouteLeave } from 'vue-router'

  const store = useStore()

  const date = ref(new Date().toISOString().substring(0, 10))
  const emptyBhakti = {
    id: 0,
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
    murtiseva: 0,
  }
  const bhakti = ref(emptyBhakti)

  const dateChanged = ref(false)
  let initialData = {}
  const getSadhana = () =>
    axios
      .get(
        `${import.meta.env.VITE_APP_API_URL}sadhanas/${date.value.replace(
          /\-/g,
          ''
        )}.json`,
        store.tokenHeader
      )
      .then(res => {
        bhakti.value = res.data ? res.data : emptyBhakti
        dateChanged.value = true
        initialData = { ...bhakti.value }
      })
      .catch(err => console.error(err))

  getSadhana()

  const totalJapa = computed(() => {
    return (
      bhakti.value.japaEarly +
      bhakti.value.japaMorning +
      bhakti.value.japaAfternoon +
      bhakti.value.japaNight
    )
  })

  const points = computed(
    () =>
      // TODO get these numbers from the API issue #24
      bhakti.value.japaEarly * 3 +
      bhakti.value.japaMorning * 2 +
      bhakti.value.japaAfternoon * 1 +
      bhakti.value.japaNight * 0.75 +
      +bhakti.value.mangala * 10 +
      +bhakti.value.japa * 10 +
      +bhakti.value.kirtana * 5 +
      +bhakti.value.class * 10 +
      +bhakti.value.gauraarati * 4 +
      bhakti.value.reading * 0.25 +
      bhakti.value.study * 0.5 +
      bhakti.value.murtiseva * 0.25
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
        toast.error('Ment??si hiba')
      })
  }

  const checkMax = () => {
    if (bhakti.value.murtiseva > 120) {
      toast.warning('Ugye ez mind szabadid??s murti-seva volt?', {
        timeout: 10000,
        position: POSITION.BOTTOM_CENTER,
      })
    }
  }

  onBeforeRouteLeave((to, from, next) => {
    if (
      JSON.stringify(initialData) != JSON.stringify(bhakti.value) &&
      (pointsChanged.value || dateChanged.value)
    ) {
      saveData()
    }
    next()
  })
</script>

<template>
  <section>
    <div class="rows center">
      <h1>{{ points }}</h1>
      <div>
        <label for="date">D??tum</label>
        <input type="date" v-model="date" @change="getSadhana" />
      </div>
    </div>

    <div class="rows">
      <h2>Japa</h2>
      <h2 class="center">{{ totalJapa }}</h2>

      <p>7 ??ra el??tt</p>
      <input type="number" v-model="bhakti.japaEarly" />

      <p>14 ??ra el??tt</p>
      <input type="number" v-model="bhakti.japaMorning" />

      <p>20 ??ra el??tt</p>
      <input type="number" v-model="bhakti.japaAfternoon" />

      <p>20 ??ra ut??n</p>
      <input type="number" v-model="bhakti.japaNight" />
    </div>

    <div class="rows">
      <div class="radio">
        <input type="checkbox" id="mangala" v-model="bhakti.mangala" />
        <label for="mangala">Mangala-arati</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="japa" v-model="bhakti.japa" />
        <label for="japa">Japa (templom)</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="kirtana" v-model="bhakti.kirtana" />
        <label for="kirtana">Kirtana</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="class" v-model="bhakti.class" />
        <label for="class">Lecke</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="gauraarati" v-model="bhakti.gauraarati" />
        <label for="gauraarati">Gaura-arati</label>
      </div>
    </div>

    <div class="rows">
      <h2>Olvas??s (perc)</h2>
      <input type="number" v-model="bhakti.reading" />

      <h2>Tanul??s (perc)</h2>
      <input type="number" v-model="bhakti.study" />

      <h2>Murti-seva (perc)</h2>
      <input type="number" v-model="bhakti.murtiseva" @blur="checkMax" />
    </div>
  </section>
</template>

<style scoped>
  section {
    display: flex;
    flex-direction: column;
    gap: 0.5rem;
  }
  .rows {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 0.5em;
    align-items: center;
    margin-bottom: 1em;
  }
  .radio {
    display: flex;
    gap: 0.5em;
  }
  .radio input {
    width: auto;
  }
  h1 {
    font-size: 4rem;
  }
  h2 {
    font-size: 1rem;
  }
  input {
    width: 100%;
    text-align: center;
  }
</style>
