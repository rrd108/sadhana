export const today = new Date()
export const year = today.getFullYear()
export const firstDayOfYear = new Date(year, 0, 1)
export const firstMonday = new Date(year, 0, 1 - firstDayOfYear.getDay() + 2)
export const todayDayNumber = Math.floor(
  (today.getTime() - firstDayOfYear.getTime()) / (24 * 60 * 60 * 1000)
)

const getWeeks = () => {
  const weeks: string[][] = []
  // Get last Thursday of the year to determine if we need 53 weeks
  const lastDay = new Date(year, 11, 31)
  const lastThursday = lastDay.getDate() - lastDay.getDay() + 4
  const weeksInYear = lastThursday > 28 ? 53 : 52

  for (let i = 0; i < weeksInYear; i++) {
    const weekStart = new Date(firstMonday.getTime() + i * 7 * 24 * 60 * 60 * 1000)
    const weekEnd = new Date(weekStart.getTime() + 6 * 24 * 60 * 60 * 1000)
    weeks.push([
      weekStart.toISOString().substring(0, 10),
      weekEnd.toISOString().substring(0, 10)
    ])
  }
  return weeks
}

export const weeks = getWeeks()

export const todayWeekNumber = (() => {
  const date = today
  // If we're in the first week of the new year but the week started in previous year
  if (date.getFullYear() > year && date.getMonth() === 0 && date.getDate() <= 7) {
    const lastWeek = weeks[weeks.length - 1]
    if (date.toISOString().substring(0, 10) <= lastWeek[1]) {
      return weeks.length
    }
    return 1
  }
  // If we're in the last week of the year but that week ends in next year
  if (date.getFullYear() < year && date.getMonth() === 11 && date.getDate() >= 29) {
    return 1
  }
  return weeks.findIndex(
    w =>
      date.toISOString().substring(0, 10) >= w[0] &&
      date.toISOString().substring(0, 10) <= w[1]
  ) + 1
})()

export const toISO = (date: string) => {
  date = date.replace(/\D/g, '')
  return `${date.slice(0, 4)}-${date.slice(4, 6)}-${date.slice(6, 8)}`
}

export const getMonthName = (month: number) => {
  return new Date(year, month, 1).toLocaleString('default', { month: 'long' })
}

export const getDatesInWeek = (week: string) => {
  const [year, weekNumber] = week.split('-W')

  // Set the date to the first day of the week
  let date = new Date(`${year}-01-01`)
  const dayOfWeek = date.getDay()
  const diff = (parseInt(weekNumber) - 1) * 7 - dayOfWeek + 1
  date.setDate(diff + 1)

  // Get all the dates of the week
  const dates = []
  for (let i = 0; i < 7; i++) {
    dates.push(new Date(date.getTime()))
    date.setDate(date.getDate() + 1)
  }

  return dates
}
