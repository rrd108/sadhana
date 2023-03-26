function sendNotification() {
  self.registration.showNotification('Sadhana emlékeztető', {
    body: 'Ne felejtsd el beírni a mai sadhanát!',
    icon: 'favicon-32x32.png',
  })
}

var currentDate = new Date()
var targetDate = new Date()
targetDate.setHours(17, 10, 0, 0)

if (currentDate < targetDate) {
  var timeToNotification = targetDate - currentDate
  setTimeout(sendNotification, timeToNotification)
}
