import { Module } from 'vuex'
import { RootState } from '.'
import { namespace } from 'vuex-class'
import Coffee from '@/api/model/Coffee'
import filter from 'lodash/filter'
import { Vue } from 'vue-property-decorator'

const _namespace = 'coffee'
export const StateCoffee = namespace(_namespace).State
export const GetterCoffee = namespace(_namespace).Getter
export const MutationCoffee = namespace(_namespace).Mutation
export const ActionCoffee = namespace(_namespace).Action

export interface CoffeeState {
  list: Coffee[]
}

const coffee = {
  namespaced: true,
  state: {
    list: []
  },
  mutations: {
    setCoffeeList (state: CoffeeState, list: Coffee[]) {
      state.list = list
    },
    toggleCoffeeSelection (state: CoffeeState, coffee: Coffee) {
      const index = state.list.indexOf(coffee)
      if (index !== -1) {
        Vue.set(coffee, 'selected', !coffee.selected)
      }
    },
    resetCoffeeSelection (state: CoffeeState) {
      for (const coffee of state.list) {
        Vue.set(coffee, 'selected', false)
      }
    },
    unselectCoffee (state: CoffeeState, coffee: Coffee) {
      const index = state.list.indexOf(coffee)
      if (index !== -1) {
        Vue.set(coffee, 'selected', false)
      }
    }
  },
  getters: {
    coffeeList (state: CoffeeState): Coffee[] {
      return state.list
    },
    selectedCoffeeList (state: CoffeeState): Coffee[] {
      return filter(state.list, ['selected', true])
    }
  }
} as Module<CoffeeState, RootState>

export default coffee
