interface Badge {
  id: number
  icon: string
  name: string
  level: number
  description: string
  gained: string
  accepted: boolean
  badgesUsersId?: number
  goal: string
}

export default Badge
