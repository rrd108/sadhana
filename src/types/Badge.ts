interface Badge {
  id: number
  icon: string
  name: string
  level: number
  description: string
  _joinData: {
    id: number
    created: string
    accepted: boolean
  }
}

export default Badge
