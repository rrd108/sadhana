import Badge from './Badge'

interface User {
  id: string
  email: string
  role: string
  token: string
  firebaseUserToken: string
  notificationTime: string
  badges: Badge[]
}

export default User
