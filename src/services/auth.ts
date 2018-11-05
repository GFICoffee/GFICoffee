import Auth, { Tokens, UsernamePasswordCredentials } from 'auth-toolbox/dist/lib/auth-toolbox'
import AxiosAdapter from 'auth-toolbox/dist/lib/client-adapter/axios-adapter'

import { AxiosRequestConfig, AxiosResponse } from 'axios'
import DefaultTokenStorage from 'auth-toolbox/dist/lib/token-storage/default-token-storage'
import { environment } from '@/environments/environment'
import store from '@/store'

import axios from 'axios'
import SymfonyLexikAdapter from './auth/symfony-lexik-adapter'
import JwtTokenDecoder from 'auth-toolbox/dist/lib/token-decoder/jwt-token-decoder';
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



const auth = new Auth<UsernamePasswordCredentials, AxiosResponse>(
  {loginEndpoint: {url: `${environment.apiBaseUrl}/login_check`, method: 'POST'}},
  new SymfonyLexikAdapter(),
  new AxiosAdapter(client),
  new JwtTokenDecoder()
)

const listener = {
  tokensChanged: (tokens: Tokens) => {
    const payload = auth.decodeAccessToken()
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

auth.addListener(listener)
auth.loadTokensFromStorage()

export {
  client,
  auth
}