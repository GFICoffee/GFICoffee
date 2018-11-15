import { Auth, AxiosAdapter, JwtTokenDecoder } from 'auth-toolbox'

import { environment } from '@/environments/environment'
import store from '@/store'

import axios from 'axios'
import SymfonyLexikAdapter from './auth/symfony-lexik-adapter'

const client = axios.create({ baseURL: environment.apiBaseUrl })

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

const auth = new Auth(
  { loginEndpoint: { url: `login_check`, method: 'POST' } },
  new SymfonyLexikAdapter(),
  new AxiosAdapter(client),
  {
    accessTokenDecoder: new JwtTokenDecoder(NaN),
    excludes: [
      `${environment.apiBaseUrl}/login`,
      `${environment.apiBaseUrl}/coffee/list`
    ]
  }
)

const payload = auth.decodeAccessToken()
store.commit('auth/setPayload', payload)

auth.addListener({
  tokensChanged: () => {
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
})

auth.usePersistentStorage = true

export {
  client,
  auth
}
