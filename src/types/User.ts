import Badge from './Badge'

interface User {
  id: string
  name: string
  email: string
  role: string
  token: string
  firebaseUserToken: string | null
  notificationTime: string | null
  counsellors: string[]
  counsulees: string[]
  badges: Badge[]
}

export default User
