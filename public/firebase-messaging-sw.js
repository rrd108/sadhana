// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js')

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
importScripts('/firebase-config.js')
firebase.initializeApp(firebaseConfig)

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging()

messaging.onBackgroundMessage(payload => {
  console.log(
    '[firebase-messaging-sw.js] Received background message ',
    payload
  )

  clients
    .matchAll()
    .then(clients => {
      const isAppInForeground = clients.some(
        client => client.visibilityState === 'visible'
      )

      if (isAppInForeground) return
    })
    .catch(err => console.error(err))

  // Customize notification here
  const notificationTitle = 'BG/' + payload.notification.title
  console.log('notificationTitle', notificationTitle)
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/android-chrome-192x192.png',
  }

  self.registration.showNotification(notificationTitle, notificationOptions)
})
