import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index'
import { environment } from '@/environments/environment.ts'
import OrderDto from '@/api/model/OrderDto'

export default class OrderResource extends AbstractResource {
  order (order: OrderDto): Promise<any & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/order`
    return this.wrapPromise(services.axios.post(path, order)) as Promise<any & AxiosResponseExt>
  }

  updateOrder (order: OrderDto): Promise<any & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/order`
    return this.wrapPromise(services.axios.put(path, order)) as Promise<any & AxiosResponseExt>
  }

  getWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/waiting`
    return this.wrapPromise(services.axios.get(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }

  getAllWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/waiting-all`
    return this.wrapPromise(services.axios.get(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }

  getAllNotWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/not-waiting-all`
    return this.wrapPromise(services.axios.get(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }

  deleteOrder (id: string | number): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/${id}`
    return this.wrapPromise(services.axios.delete(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }

  validateAllWaitingOrders (): Promise<OrderDto[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/orders/waiting-all/validate`
    return this.wrapPromise(services.axios.post(path)) as Promise<OrderDto[] & AxiosResponseExt>
  }
}
