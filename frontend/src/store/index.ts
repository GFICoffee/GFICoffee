import Vue from 'vue'
import Vuex from 'vuex'
import auth from './auth'
import snackbar from './snackbar'
import coffee from './coffee'

Vue.use(Vuex)

export interface RootState {
  [stateKey: string]: any
}

export default new Vuex.Store<RootState>({
  state: {} as RootState,
  modules: {
    auth,
    snackbar,
    coffee
  },
  strict: false
})
