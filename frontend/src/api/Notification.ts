import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index'
import { environment } from '@/environments/environment.ts'

export default class NotificationResource extends AbstractResource {
  sendPickupNotification (): Promise<any & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/notification/pickup/send`
    return this.wrapPromise(services.axios.post(path)) as Promise<any & AxiosResponseExt>
  }
}
