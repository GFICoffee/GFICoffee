import { auth, client as axios } from './auth'
import UserResource from '@/api/User'
import CoffeeResource from '@/api/Coffee';
import OrderResource from '@/api/Order';

const userResource = new UserResource()
const coffeeResource = new CoffeeResource()
const orderResource = new OrderResource()

export default {
  auth,
  axios,
  userResource,
  coffeeResource,
  orderResource
}