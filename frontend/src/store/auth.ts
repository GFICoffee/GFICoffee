import { Module } from 'vuex'
import { RootState } from '@/store'
import { namespace } from 'vuex-class'

const _namespace = 'auth'
export const StateAuth = namespace(_namespace).State
export const GetterAuth = namespace(_namespace).Getter
export const MutationAuth = namespace(_namespace).Mutation
export const ActionAuth = namespace(_namespace).Action

export interface Payload {
  username: string
  roles: string[]
  exp: number
  iat: number
}

export interface AuthState {
  payload?: Payload
}

const auth = {
  namespaced: true,
  state: {
    payload: undefined
  },
  getters: {
    authenticated: (state: AuthState) => {
      return !!state.payload
    },
    payload: (state: AuthState) => {
      return state.payload
    }
  },
  mutations: {
    setPayload (state: AuthState, payload: Payload) {
      state.payload = payload
    }
  }
} as Module<AuthState, RootState>

export default auth
