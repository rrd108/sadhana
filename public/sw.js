import { precacheAndRoute } from 'workbox-precaching'

precacheAndRoute(self.__WB_MANIFEST)

let targetTime = '20:00' // should be the same in Settings.vue
let at

const sendNotification = () => {
  self.registration.showNotification('Sadhana emlékeztető', {
    body: 'Ne felejtsd el beírni a mai sadhanát!',
    icon: 'favicon-32x32.png',
  })
}

const setNotificationTime = notificationTime => {
  let currentDateTime = new Date()
  let targetDateTime = new Date()
  const [hours, minutes] = notificationTime.split(':')
  targetDateTime.setHours(hours, minutes, 0, 0)

  if (currentDateTime < targetDateTime) {
    const timeToNotification = targetDateTime - currentDateTime
    // send the notification at the given time
    at = setTimeout(sendNotification, timeToNotification)
  }

  // set the next notification for tommorrow same time
  if (currentDateTime > targetDateTime) {
    targetDateTime.setDate(targetDateTime.getDate() + 1)
    console.log('targetDateTime: ', targetDateTime)
    const timeToNotification = targetDateTime - currentDateTime
    // send the notification at the given time
    at = setTimeout(sendNotification, timeToNotification)
  }
}

self.addEventListener('message', event => {
  // listen for a message from the main app
  const { type, data } = event.data

  if (type == 'timeChange') {
    console.log('Time change message received from main app: ', data)
    setNotificationTime(data)
  }

  if (type == 'sadhanaData') {
    let currentDate = new Date()
    // TODO put this code back after tests
    // if (data == currentDate.toISOString().substring(0, 10)) {
    //   // this is today's sadhana data, no notification is needed
    //   clearTimeout(at)
    // }
  }
})

self.addEventListener('activate', event => {
  console.log('Service worker activated')
  setNotificationTime(targetTime)
})

console.log('Service worker loaded')
