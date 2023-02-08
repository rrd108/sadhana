<script setup lang="ts">
  import { computed, ref } from 'vue'

  const date = ref(new Date().toISOString().substring(0, 10))
  const japa = ref({ early: 0, morning: 0, afternoon: 0, night: 0 })
  const templePresence = ref([])
  const reading = ref(0)
  const study = ref(0)
  const murtiseva = ref(0)

  const totalJapa = computed(() => {
    return (
      japa.value.early +
      japa.value.morning +
      japa.value.afternoon +
      japa.value.night
    )
  })

  const points = computed(
    () =>
      japa.value.early * 3 +
      japa.value.morning * 2 +
      japa.value.afternoon * 1 +
      japa.value.night * 0.5 +
      templePresence.value.length * 2 +
      reading.value * 1 +
      study.value * 1 +
      murtiseva.value * 1
  )

  // TODO do a debounce save: https://vueuse.org/core/useIdle/
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
      <input type="number" v-model="japa.early" />

      <p>12 óra előtt</p>
      <input type="number" v-model="japa.morning" />

      <p>21 óra előtt</p>
      <input type="number" v-model="japa.afternoon" />

      <p>21 óra után</p>
      <input type="number" v-model="japa.night" />
    </div>

    <div class="rows">
      <div class="radio">
        <input
          type="checkbox"
          id="mangala"
          value="mangala"
          v-model="templePresence"
        />
        <label for="mangala">Mangala-arati</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="japa"
          value="japa"
          v-model="templePresence"
        />
        <label for="japa">Japa (templom)</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="kirtana"
          value="kirtana"
          v-model="templePresence"
        />
        <label for="kirtana">Kirtana</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="class"
          value="class"
          v-model="templePresence"
        />
        <label for="class">Lecke</label>
      </div>
      <div class="radio">
        <input
          type="checkbox"
          id="gauraarati"
          value="gauraarati"
          v-model="templePresence"
        />
        <label for="gauraarati">Gaura-arati</label>
      </div>
    </div>

    <div class="rows">
      <h2>Olvasás (perc)</h2>
      <input type="number" v-model="reading" />

      <h2>Tanulás (perc)</h2>
      <input type="number" v-model="study" />

      <h2>Murti-seva (perc)</h2>
      <input type="number" v-model="murtiseva" />
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
