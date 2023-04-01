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
  const messaging = getMessaging()

  const store = useStore()

  if (!store.user.firebaseUserToken) {
    // TODO An update frequency of once per month likely strikes a good balance between battery impact vs. detecting inactive registration tokens. So if the token is older than a month, you should call getToken again.
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
            .then(res => toast.success(res.data.firebaseUserToken))
            .catch(err => toast.error(err))
        }
        if (!currentToken) {
          // Show permission request UI
          toast.info(
            'No reg token available. Request permission to generate one.'
          )
          Notification.requestPermission()
            .then(permission => {
              if (permission === 'granted') {
                toast.info('Notification permission granted.')
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
      })
      .catch(err => {
        toast.warning('An error occurred while retrieving token. ', err)
      })
  }

  // TODO read from API
  const time = ref('20:00') // should be the same in sw.js

  // TODO get from API
  const notification = ref(false)
  if (store.user.firebaseUserToken) {
    notification.value = true
  }
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
