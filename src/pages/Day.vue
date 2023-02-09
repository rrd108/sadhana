<script setup lang="ts">
  import { computed, ref, watch } from 'vue'
  import { useIdle } from '@vueuse/core'

  const date = ref(new Date().toISOString().substring(0, 10))
  const bhakti = ref({
    japa: { early: 0, morning: 0, afternoon: 0, night: 0 },
    templeProgram: {
      mangala: false,
      japa: false,
      kirtana: false,
      class: false,
      gauraarati: false,
    },
    brahman: { reading: 0, study: 0, murtiseva: 0 },
  })

  const totalJapa = computed(() => {
    return (
      bhakti.value.japa.early +
      bhakti.value.japa.morning +
      bhakti.value.japa.afternoon +
      bhakti.value.japa.night
    )
  })

  const points = computed(
    () =>
      bhakti.value.japa.early * 3 +
      bhakti.value.japa.morning * 2 +
      bhakti.value.japa.afternoon * 1 +
      bhakti.value.japa.night * 0.75 +
      +bhakti.value.templeProgram.mangala * 10 +
      +bhakti.value.templeProgram.japa * 10 +
      +bhakti.value.templeProgram.kirtana * 5 +
      +bhakti.value.templeProgram.class * 10 +
      +bhakti.value.templeProgram.gauraarati * 4 +
      bhakti.value.brahman.reading * 0.5 +
      bhakti.value.brahman.study * 1 +
      bhakti.value.brahman.murtiseva * 1
  )

  const { idle } = useIdle(5000) // set idle time to 5 seconds
  watch(idle, isIdle => {
    if (isIdle) {
      console.log('idle')
    }
  })
</script>

<template>
  <section>
    <div class="rows center">
      <h1>{{ points }}</h1>
      <div>
        <label for="date">Dátum</label>
        <input type="date" v-model="date" />
      </div>
    </div>

    <div class="rows">
      <h2>Japa</h2>
      <h2 class="center">{{ totalJapa }}</h2>

      <p>7 óra előtt</p>
      <input type="number" v-model="bhakti.japa.early" />

      <p>14 óra előtt</p>
      <input type="number" v-model="bhakti.japa.morning" />

      <p>20 óra előtt</p>
      <input type="number" v-model="bhakti.japa.afternoon" />

      <p>20 óra után</p>
      <input type="number" v-model="bhakti.japa.night" />
    </div>

    <div class="rows">
      <div class="radio">
        <input
          type="checkbox"
          id="mangala"
          v-model="bhakti.templeProgram.mangala"
        />
        <label for="mangala">Mangala-arati</label>
      </div>
      <div class="radio">
        <input type="checkbox" id="japa" v-model="bhakti.templeProgram.japa" />
        <label for="japa">Japa (templom)</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="kirtana"
          v-model="bhakti.templeProgram.kirtana"
        />
        <label for="kirtana">Kirtana</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="class"
          v-model="bhakti.templeProgram.class"
        />
        <label for="class">Lecke</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="gauraarati"
          v-model="bhakti.templeProgram.gauraarati"
        />
        <label for="gauraarati">Gaura-arati</label>
      </div>
    </div>

    <div class="rows">
      <h2>Olvasás (perc)</h2>
      <input type="number" v-model="bhakti.brahman.reading" />

      <h2>Tanulás (perc)</h2>
      <input type="number" v-model="bhakti.brahman.study" />

      <h2>Murti-seva (perc)</h2>
      <input type="number" v-model="bhakti.brahman.murtiseva" />
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
