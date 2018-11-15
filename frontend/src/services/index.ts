import { auth, client as axios } from './auth'
import CoffeeResource from '@/api/Coffee';
import OrderResource from '@/api/Order';

const coffeeResource = new CoffeeResource()
const orderResource = new OrderResource()

export default {
  auth,
  axios,
  coffeeResource,
  orderResource
}