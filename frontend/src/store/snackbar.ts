import { Module } from 'vuex'

import { RootState } from '.'

import { namespace } from 'vuex-class'

const _namespace = 'snackbar'
export const StateSnackbar = namespace(_namespace).State
export const GetterSnackbar = namespace(_namespace).Getter
export const MutationSnackbar = namespace(_namespace).Mutation
export const ActionSnackbar = namespace(_namespace).Action

export interface SnackbarButton {
  text?: string,
  action?: () => any
}

export interface SnackbarEntry {
  message?: string
  icon?: string
  title?: string
  color?: string
  multiLine?: boolean
  timeout?: number
  closable?: boolean
  button?: SnackbarButton
}

export interface SnackbarState {
  entry?: SnackbarEntry | null
}

const snackbar = {
  namespaced: true,
  state: {
    entry: undefined
  },
  mutations: {
    setSnackbarEntry (state: SnackbarState, payload: SnackbarEntry | null) {
      if (payload && payload.closable === undefined) {
        payload.closable = true
      }
      state.entry = payload
    },
    clearSnackbarEntry (state: SnackbarState) {
      state.entry = undefined
    }
  },
  getters: {
    currentSnackbarEntry (state: SnackbarState): SnackbarEntry | undefined | null {
      return state.entry
    }
  }
} as Module<SnackbarState, RootState>

export default snackbar
