import { auth, client as axios } from './auth'
import UserResource from '@/api/User'
import CoffeeResource from '@/api/Coffee';

const userResource = new UserResource()
const coffeeResource = new CoffeeResource()

export default {
  auth,
  axios,
  userResource,
  coffeeResource
}