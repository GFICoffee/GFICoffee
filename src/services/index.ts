import { auth, client as axios } from './auth'
import UserResource from '@/api/User'

const userResource = new UserResource()

export default {
  auth,
  axios,
  userResource
}