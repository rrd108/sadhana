import SadhanaFields from './SadhanaFields'

interface Journal extends SadhanaFields {
  id: number
  user_id: string
  date: string
}

export default Journal
