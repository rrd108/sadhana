import Badge from './Badge'

interface User {
  id: string
  email: string
  role: string
  token: string
  badges: Badge[]
}

export default User
