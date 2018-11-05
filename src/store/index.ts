import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'

Vue.use(Vuex)

export interface RootState {
  [stateKey: string]: any
}

export default new Vuex.Store<RootState>({
  state: {} as RootState,
  modules: {
    auth
  },
  strict: false
})
