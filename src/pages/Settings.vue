<script setup lang="ts">
  import { ref, watch } from 'vue'

  const notification = ref(false)
  const time = ref('20:00') // should be the same in sadhana-sw.js

  if (Notification.permission === 'granted') {
    notification.value = true
  }

  watch(notification, value => {
    if (value) {
      console.log('Notification enabled')
      if (Notification.permission !== 'denied') {
        Notification.requestPermission()
          .then(permission => {
            if (permission === 'granted') {
              console.log('Notification permission granted.')
            }
          })
          .catch(err => {
            console.error('Unable to get permission to notify.', err)
          })
      }

      if (Notification.permission === 'granted') {
        const notification = new Notification('Sadhana', {
          body: 'Az értesítések engedélyezve!',
          icon: 'favicon-32x32.png',
        })
      }
    }
    if (!value) {
      console.log('Notification disabled')
      notification.value = false
    }
  })

  watch(time, value => {
    navigator.serviceWorker.controller?.postMessage({
      type: 'timeChange',
      data: value,
    })
  })
</script>

<template>
  <section>
    <h1>Beállítások</h1>
    <div>
      <label for="notification">Emlékeztető</label>
      <input type="checkbox" v-model="notification" />
      <input type="time" v-model="time" v-show="notification" />
      <p>
        Ha az emlékeztetőt bekapcsolod, akkor az alkalmazás a kiválasztott
        időpontban küld egy üzenetet a telefonra, ha aznap nem töltötted ki a
        sadhana adatokat.
      </p>
    </div>
  </section>
</template>

<style scoped>
  h1 {
    margin: 1em 0;
  }
  input {
    margin-left: 1em;
  }
  p {
    color: var(--pinky);
    margin-top: 0.5em;
  }
</style>
