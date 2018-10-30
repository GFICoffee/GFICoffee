import services from '@/services'
import { AbstractResource, AxiosResponseExt } from '@/api/index';
import { environment } from '@/environments/environment.ts'
import UserWithPasswordModel from '@/api/model/UserWithPasswordModel';
import UserModel from '@/api/model/UserModel';

export default class UserResource extends AbstractResource {
  register (user: UserWithPasswordModel): Promise<UserModel & AxiosResponseExt> {
    let path = `${environment.apiBaseUrl}/register`
    return this.wrapPromise(services.axios.post(path, user)) as Promise<UserModel & AxiosResponseExt>
  }
}