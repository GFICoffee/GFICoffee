import Auth, { Tokens, UsernamePasswordCredentials } from '../jwt-toolbox/auth'
import AxiosAdapter from '../jwt-toolbox/auth/client-adapter/axios-adapter'
import jwtDecode from 'jwt-decode'

import { AxiosRequestConfig, AxiosResponse } from 'axios'
import DefaultTokenStorage from '../jwt-toolbox/auth/token-storage/default-token-storage'
import { environment } from '@/environments/environment'
import store from '@/store'

import axios from 'axios'
import SymfonyLexikAdapter from '@/jwt-toolbox/auth/server-adapter/symfony-lexik-adapter'
const client = axios.create()

export interface Payload {
  jti: string,

  exp: number,

  nbf: number,

  iat: number,

  iss: string,

  aud: string,

  sub: string,

  typ: string,

  azp: string,

  auth_time: number,

  session_state: string,

  acr: number,

  'allowed-origins': [string],

  realm_access: RealmAccess,

  resource_access: ResourceAccess,

  name: string,

  preferred_username: string,

  given_name: string,

  family_name: string,

  email: string
}

export interface RealmAccess {
  roles: [string]
}

export interface ResourceAccess {
  account: RealmAccess
}

const auth = new Auth<UsernamePasswordCredentials, AxiosRequestConfig, AxiosResponse>(
  {loginEndpoint: {url: `${environment.apiBaseUrl}/login_check`, method: 'POST'}},
  new SymfonyLexikAdapter(),
  new AxiosAdapter(client),
  new DefaultTokenStorage(window.sessionStorage),
  new DefaultTokenStorage(window.localStorage)
)

const listener = {
  tokensChanged: (tokens: Tokens) => {
    const payload = (tokens && tokens.accessToken) ? jwtDecode(tokens.accessToken) : undefined
    store.commit('auth/setPayload', payload)
  },
  expired: () => {
    if (window.stop) {
      window.stop()
    }
    console.log('expired')
  },
  logout: () => {
    if (window.stop) {
      window.stop()
    }
    console.log('logout')
  }
}

auth.addListeners(listener)
auth.init()

export {
  client,
  auth
}