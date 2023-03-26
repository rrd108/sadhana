const sendNotification = () => {
  self.registration.showNotification('Sadhana emlékeztető', {
    body: 'Ne felejtsd el beírni a mai sadhanát!',
    icon: 'favicon-32x32.png',
  })
}

let targetTime = '17:36'

const setNotification = time => {
  let currentDate = new Date()
  let targetDate = new Date()
  const [hours, minutes] = time.split(':')
  targetDate.setHours(hours, minutes, 0, 0)

  if (currentDate < targetDate) {
    const timeToNotification = targetDate - currentDate
    setTimeout(sendNotification, timeToNotification)
  }
}

self.addEventListener('message', event => {
  // listen for a message from the main app containing the desired notification time
  console.log('Message received from main app: ', event.data)
  setNotification(event.data)
})

setNotification(targetTime)
