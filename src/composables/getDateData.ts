export const today = new Date()
export const year = today.getFullYear()
export const firstDayOfYear = new Date(year, 0, 1)
export const firstMonday = new Date(year, 0, 1 - firstDayOfYear.getDay() + 2)
export const todayDayNumber = Math.floor(
  (today.getTime() - firstDayOfYear.getTime()) / (24 * 60 * 60 * 1000)
)

const getWeeks = () => {
  const weeks: string[][] = []
  for (let i = 0; i < 52; i++) {
    weeks.push([
      new Date(firstMonday.getTime() + i * 7 * 24 * 60 * 60 * 1000)
        .toISOString()
        .substring(0, 10),
      new Date(
        firstMonday.getTime() +
          i * 7 * 24 * 60 * 60 * 1000 +
          6 * 24 * 60 * 60 * 1000
      )
        .toISOString()
        .substring(0, 10),
    ])
  }
  return weeks
}

export const weeks = getWeeks()

export const todayWeekNumber =
  weeks.findIndex(
    w =>
      today.toISOString().substring(0, 10) >= w[0] &&
      today.toISOString().substring(0, 10) <= w[1]
  ) + 1

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
