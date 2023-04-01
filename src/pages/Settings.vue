<script setup lang="ts">
  import { ref } from 'vue'
  import axios from 'axios'
  import { useStore } from '../store'
  import { initializeApp } from 'firebase/app'
  import { getMessaging, getToken } from 'firebase/messaging'
  import { useToast } from 'vue-toastification'

  const toast = useToast()

  const firebaseConfig = {
    apiKey: import.meta.env.VITE_APP_FIREBASE_APIKEY,
    authDomain: import.meta.env.VITE_APP_FIREBASE_AUTHDOMAIN,
    projectId: import.meta.env.VITE_APP_FIREBASE_PROJECTID,
    storageBucket: import.meta.env.VITE_APP_FIREBASE_STORAGEBUCKET,
    messagingSenderId: import.meta.env.VITE_APP_FIREBASE_MESSAGINGSENDERID,
    appId: import.meta.env.VITE_APP_FIREBASE_APPID,
    measurementId: import.meta.env.VITE_APP_FIREBASE_MEASUREMENTID,
  }
  const app = initializeApp(firebaseConfig)

  // Get registration token. Initially this makes a network call, once retrieved
  // subsequent calls to getToken will return from cache.
  // this initialize the service worker
  const messaging = getMessaging(app)

  const store = useStore()

  const notificationPermission = ref(Notification.permission == 'granted')
  const isDenied = ref(Notification.permission == 'denied')

  const requestNotificationPermission = () => {
    if (Notification.permission == 'granted') {
      notificationPermission.value = true
      return
    }

    if (Notification.permission == 'denied') {
      isDenied.value = true
      return
    }

    Notification.requestPermission()
      .then(permission => {
        if (permission == 'granted') {
          notificationPermission.value = true
          new Notification('Sadhana', {
            body: 'Az értesítések engedélyezve!',
            icon: 'favicon-32x32.png',
          })
        }
      })
      .catch(err => {
        toast.error('Unable to get permission to notify.', err)
      })
  }

  if (!store.user.firebaseUserToken) {
    getToken(messaging, {
      vapidKey: import.meta.env.VITE_APP_FIREBASE_VAPIDKEY,
    })
      .then(currentToken => {
        if (currentToken) {
          axios
            .patch(
              `${import.meta.env.VITE_APP_API_URL}users/${store.user.id}.json`,
              { firebaseUserToken: currentToken },
              store.tokenHeader
            )
            .then(res => toast.success('Beállítás mentve'))
            .catch(err => toast.error(err))
        }
        if (!currentToken) {
          requestNotificationPermission()
        }
      })
      .catch(err => {
        toast.warning(
          'An error occurred while retrieving token. ' + err.message
        )
      })
  }

  // TODO read from API
  const time = ref('20')
  // TODO save to API on blur
  const timeBlur = () => console.log('blur')
</script>

<template>
  <section>
    <h1>Beállítások</h1>
    <div>
      <label for="notification">Emlékeztető</label>
      <input
        id="notification"
        type="checkbox"
        v-model="notificationPermission"
        @change="requestNotificationPermission"
        :disabled="isDenied"
      />
    </div>
    <div v-show="notificationPermission">
      <label for="time">Időpont</label>
      <input
        id="time"
        type="number"
        min="8"
        max="24"
        step="1"
        v-model="time"
        @blur="timeBlur"
      />
      óra
    </div>
    <p v-if="isDenied" class="info">
      Korábban letiltottad az értesítéseket. Engedélyezdned kell a
      <strong>böngészőben</strong>, hogy megkapd az értesítéseket.
    </p>
    <div>
      <p v-if="!notificationPermission">
        Ha kipipálod akkor a böngésző rá fog kérdezni, hogy engedélyezed-e az
        értesítéseket, ahol az <strong>"engedélyezést"</strong> kell választani.
      </p>
      <p>
        Ha bekapcsolod, akkor az alkalmazás a kiválasztott időpontban küld egy
        üzenetet a telefonra, ha aznap nem töltötted ki a sadhana adatokat.
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
  div {
    display: inline-block;
  }
  p {
    color: var(--pinky);
    margin-top: 0.5em;
  }
  .info {
    background-color: var(--pinky);
    color: var(--dark-purple);
    margin: 1em;
    padding: 0.5em;
  }

  input[type='checkbox'] {
    margin-right: 2em;
  }
  input[type='number'] {
    text-align: center;
  }
</style>
