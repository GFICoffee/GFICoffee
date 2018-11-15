import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index'
import { environment } from '@/environments/environment.ts'
import Coffee from '@/api/model/Coffee'

export default class CoffeeResource extends AbstractResource {
  getCoffeeList (): Promise<Coffee[] & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/coffee/list`
    return this.wrapPromise(services.axios.get(path)) as Promise<Coffee[] & AxiosResponseExt>
  }
}
