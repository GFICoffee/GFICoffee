import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index';
import { environment } from '@/environments/environment.ts'
import OrderDto from '@/api/model/OrderDto';

export default class OrderResource extends AbstractResource {
  order (order: OrderDto): Promise<any & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/order`
    return this.wrapPromise(services.axios.post(path, order)) as Promise<any & AxiosResponseExt>
  }

  getWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/waiting`
    return this.wrapPromise(services.axios.get(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }

  getAllWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/waiting-all`
    return this.wrapPromise(services.axios.get(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }
}