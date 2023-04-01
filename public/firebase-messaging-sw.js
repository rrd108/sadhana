// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-app.js')
importScripts('https://www.gstatic.com/firebasejs/8.10.1/firebase-messaging.js')

// Initialize the Firebase app in the service worker by passing in
// your app's Firebase config object.
// https://firebase.google.com/docs/web/setup#config-object
firebase.initializeApp({
  apiKey: 'AIzaSyBXKOxVSf48xRbRGfzc37DDAsan7GAeSsM',
  authDomain: 'sadhana-d0c2c.firebaseapp.com',
  projectId: 'sadhana-d0c2c',
  storageBucket: 'sadhana-d0c2c.appspot.com',
  messagingSenderId: '56073975740',
  appId: '1:56073975740:web:68d3b1b01acc04e5151db9',
  measurementId: 'G-YS3T67DKJR',
})

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging()

messaging.onBackgroundMessage(payload => {
  console.log(
    '[firebase-messaging-sw.js] Received background message ',
    payload
  )
  // Customize notification here
  const notificationTitle = payload.notification.title
  const notificationOptions = {
    body: payload.notification.body,
    icon: '/android-chrome-192x192.png',
  }

  self.registration.showNotification(notificationTitle, notificationOptions)
})
