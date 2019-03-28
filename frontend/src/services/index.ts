import { auth, client as axios } from './auth'
import CoffeeResource from '@/api/Coffee'
import OrderResource from '@/api/Order'
import NotificationResource from '@/api/Notification'

const coffeeResource = new CoffeeResource()
const orderResource = new OrderResource()
const notificationResource = new NotificationResource()

export default {
  auth,
  axios,
  coffeeResource,
  orderResource,
  notificationResource
}
