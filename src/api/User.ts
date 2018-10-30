import axios from 'axios'
import { AbstractResource, AxiosResponseExt } from '@/api/index';
import { environment } from '@/environments/environment.ts'

export default class UserResource extends AbstractResource {
  login (username: string, password: string): Promise<string & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/login_check`
    return this.wrapPromise(axios.post(path, { username, password })) as Promise<string & AxiosResponseExt>
  }
}