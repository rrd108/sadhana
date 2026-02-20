import { initializeApp } from 'firebase/app'
import { getMessaging, getToken, onMessage } from 'firebase/messaging'
import axios from 'axios'
import { useStore } from '../store'

export function useFirebaseToken() {
  const store = useStore()

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
  const messaging = getMessaging(app)

  const refreshFirebaseToken = () => {
    if (!store.user.notificationTime) {
      return
    }
    if (!('Notification' in window)) {
      return
    }
    if (Notification.permission !== 'granted') {
      return
    }

    getToken(messaging, {
      vapidKey: import.meta.env.VITE_APP_FIREBASE_VAPIDKEY,
    })
      .then(currentToken => {
        if (currentToken && currentToken !== store.user.firebaseUserToken) {
          axios
            .patch(
              `${import.meta.env.VITE_APP_API_URL}users/${
                store.user.id
              }.json`,
              { firebaseUserToken: currentToken },
              store.tokenHeader
            )
            .then(res => {
              store.user.firebaseUserToken = res.data.user.firebaseUserToken
            })
            .catch(err => console.error(err))
        }
      })
      .catch(err => {
        console.error(err)
      })
  }

  onMessage(messaging, payload => {
    console.log('Foreground message received:', payload)
  })

  return { refreshFirebaseToken }
}
