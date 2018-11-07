import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index';
import { environment } from '@/environments/environment.ts'
import OrderDto from '@/api/model/OrderDto';

export default class OrderResource extends AbstractResource {
  order (order: OrderDto): Promise<any & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/order`
    return this.wrapPromise(services.axios.post(path, order)) as Promise<any & AxiosResponseExt>
  }
}