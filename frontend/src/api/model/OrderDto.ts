import UserModel from '@/api/model/UserModel';
import Coffee from '@/api/model/Coffee';

export default interface OrderDto {
  id?: number
  user?: UserModel
  items: Coffee[]
}