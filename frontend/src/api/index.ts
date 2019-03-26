import { AxiosPromise, AxiosResponse } from 'axios'
import { SnackbarEntry } from '@/store/snackbar'
import store from '@/store'

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
      }).catch((error) => {
        let message = 'Une erreur est survenue'
        if (error.response && error.response.data && error.response.data.message) {
          message = error.response.data.message
        } else if (error.message) {
          message = `${message}: ${error.message}`
        }
        const snackbar = {
          title: 'Erreur',
          icon: 'mdi-alert',
          message: message,
          color: 'error',
          timeout: 6000
        } as SnackbarEntry
        store.commit('snackbar/setSnackbarEntry', snackbar)
        console.error(message)
        reject()
      })
    })
  }
}
