import { AxiosPromise, AxiosResponse } from 'axios'

export interface AxiosResponseExt {
  $axios: AxiosResponse
}


export abstract class AbstractResource {
  protected wrapPromise (axiosPromise: AxiosPromise) {
    return new Promise((resolve, reject) => {
      axiosPromise.then((axiosResponse: AxiosResponse) => {
        if (typeof axiosResponse.data === 'object') {
          (axiosResponse.data as AxiosResponseExt).$axios = axiosResponse
        }
        resolve(axiosResponse.data)
      }).catch(reject)
    })
  }
}