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
  const time = ref(store.user.notificationTime || '19')

  const saveNotificationTime = () =>
    axios
      .patch(
        `${import.meta.env.VITE_APP_API_URL}users/${store.user.id}.json`,
        {
          notificationTime: notificationPermission.value ? time.value : null,
        },
        store.tokenHeader
      )
      .then(
        res => (store.user.notificationTime = res.data.user.notificationTime)
      )
      .catch(err => console.error(err))

  const getFirebaseToken = () => {
    if (!store.user.firebaseUserToken) {
      getToken(messaging, {
        vapidKey: import.meta.env.VITE_APP_FIREBASE_VAPIDKEY,
      })
        .then(currentToken => {
          if (currentToken) {
            axios
              .patch(
                `${import.meta.env.VITE_APP_API_URL}users/${
                  store.user.id
                }.json`,
                { firebaseUserToken: currentToken },
                store.tokenHeader
              )
              .then(res => {
                toast.success('Beállítás mentve')
                store.user.firebaseUserToken = res.data.user.firebaseUserToken
              })
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
  }

  const requestNotificationPermission = () => {
    if (store.user.notificationTime && notificationPermission.value == false) {
      // swich off notifications by set notificationTime to null
      saveNotificationTime()
      return
    }

    if (Notification.permission == 'granted') {
      // permission is already granted, this is a time change
      notificationPermission.value = true
      getFirebaseToken()
      saveNotificationTime()
      return
    }

    if (Notification.permission == 'denied') {
      isDenied.value = true
      return
    }

    // notification permission is not granted yet
    Notification.requestPermission()
      .then(permission => {
        if (permission == 'granted') {
          notificationPermission.value = true
          if (window.matchMedia('(display-mode: standalone)').matches) {
            // PWA mode
            navigator.serviceWorker.ready
              .then(registration =>
                registration.showNotification('Sadhana', {
                  body: 'Az értesítések engedélyezve!',
                  icon: 'favicon-32x32.png',
                })
              )
              .catch(err => {
                toast.error('Unable to get permission to notify.', err)
              })
          }
          if (!window.matchMedia('(display-mode: standalone)').matches) {
            // Browser mode
            new Notification('Sadhana', {
              body: 'Az értesítések engedélyezve!',
              icon: 'favicon-32x32.png',
            })
          }
          saveNotificationTime()
        }
      })
      .catch(err => {
        toast.error('Unable to get permission to notify.', err)
      })

    getFirebaseToken()
  }

  // if (
  //   store.user.notificationTime &&
  //   Notification.permission == 'granted' &&
  //   !store.user.firebaseUserToken
  // ) {
  //   // TODO move this to login/day after I found out how and when to invalidate a token
  //   getFirebaseToken()
  // }
</script>

<template>
  <h2>Emlékeztető</h2>
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
    <select v-model="time" @change="saveNotificationTime">
      <option v-for="i in 12" :key="i" :value="i + 8">{{ i + 8 }}</option>
    </select>
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
</template>

<style scoped>
  input {
    margin-left: 1em;
  }
  select {
    margin-left: 0.5em;
  }
  div {
    display: inline-block;
  }

  input[type='checkbox'] {
    margin-right: 2em;
  }
  input[type='number'] {
    text-align: center;
  }
</style>