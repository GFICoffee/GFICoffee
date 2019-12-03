<template>
  <v-layout justify-center class="calculator">
    <v-flex md9 xs12>
      <v-layout column>
        <v-flex v-for="(coffee, i) of selectedCoffeeList" :key="i" class="coffee-choice pt-4 pb-2">
          <v-layout>
            <v-flex>
              <h2 class="white--text">{{ coffee.name }}</h2>
              <h6 class="white--text">{{ coffee.unit_price.toFixed(2) }}&nbsp;€ / unité</h6>
            </v-flex>
            <v-flex shrink>
              <v-select
                  :items="quantities"
                  class="quantity-field mr-3"
                  label="Quantité (x30)"
                  color="white"
                  hide-details
                  type="number"
                  v-model="coffee.quantity30"
                  clearable
              />
            </v-flex>
            <v-flex shrink>
              <v-select
                  :items="quantities"
                  class="quantity-field mr-3"
                  label="Quantité (x50)"
                  color="white"
                  hide-details
                  type="number"
                  v-model="coffee.quantity50"
                  clearable
              />
            </v-flex>
            <v-flex shrink>
              <h3 class="white--text">{{ coffeePrice(coffee).toFixed(2) }}&nbsp;€</h3>
            </v-flex>
            <v-flex shrink>
              <v-icon class="ml-3" @click="unselectCoffee(coffee)">mdi-close</v-icon>
            </v-flex>
          </v-layout>
        </v-flex>

        <v-flex class="coffee-choice pt-4 pb-2">
          <v-layout>
            <v-flex>
              <h2 class="white--text">Total</h2>
            </v-flex>
            <v-flex shrink>
              <h3 class="white--text">{{ totalPrice.toFixed(2) }}&nbsp;€</h3>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex>
          <v-layout justify-end>
            <v-flex shrink>
              <v-btn ouline @click="confirmDialog = true" :disabled="!canOrder">Commander</v-btn>
              <v-dialog v-model="confirmDialog" width="500">
                <v-card class="confirm pb-2 px-3" color="secondary lighten-1">
                  <v-card-title class="headline white--text pl-0">Confirmation</v-card-title>
                  <v-flex class="white--text">
                    Êtes-vous sûr de vouloir passer cette commande ?
                  </v-flex>
                  <v-card-actions>
                    <v-spacer/>
                    <v-btn flat @click="confirmDialog = false">Non</v-btn>
                    <v-btn outline @click="order()" :loading="orderLoading">Oui</v-btn>
                  </v-card-actions>
                </v-card>
              </v-dialog>
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator'
import Coffee from '@/api/model/Coffee'
import OrderResource from '@/api/Order'
import OrderDto from '@/api/model/OrderDto'
import filter from 'lodash/filter'
import { MutationSnackbar, SnackbarEntry } from '@/store/snackbar'
import { GetterCoffee, MutationCoffee } from '@/store/coffee'

@Component
export default class Calculator extends Vue {
  @Inject()
  orderResource!: OrderResource

  @MutationSnackbar
  setSnackbarEntry!: (entry: SnackbarEntry) => void
  @GetterCoffee
  selectedCoffeeList!: Coffee[]
  @MutationCoffee
  resetCoffeeSelection!: () => void
  @MutationCoffee
  unselectCoffee!: (coffee: Coffee) => void

  quantities: number[] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]
  confirmDialog: boolean = false
  orderLoading: boolean = false

  get totalPrice (): number {
    let total = 0

    for (const coffee of this.selectedCoffeeList) {
      total += this.coffeePrice(coffee)
    }
    return total
  }

  get canOrder (): boolean {
    let canOrder: boolean = true

    if (this.selectedCoffeeList.length === 0) {
      canOrder = false
    } else {
      for (let coffee of this.selectedCoffeeList) {
        if (!coffee.quantity30 && !coffee.quantity50) {
          canOrder = false
        } else if (this.selectedCoffeeList.length > 1 && (coffee.quantity30 || coffee.quantity50)) {
          canOrder = true
          break
        }
      }
    }
    return canOrder
  }

  coffeePrice (coffee: Coffee): number {
    let total: number = 0

    if (coffee.quantity30 && coffee.unit_price) {
      total += coffee.unit_price * (typeof coffee.quantity30 === 'string' ? parseFloat(coffee.quantity30) : coffee.quantity30) * 30
    }

    if (coffee.quantity50 && coffee.unit_price) {
      total += coffee.unit_price * (typeof coffee.quantity50 === 'string' ? parseFloat(coffee.quantity50) : coffee.quantity50) * 50
    }
    return total
  }

  async order () {
    this.orderLoading = true
    try {
      const order: OrderDto = {
        items: filter<Coffee[]>(this.selectedCoffeeList, function (coffee: Coffee) {
          return coffee.quantity30 || coffee.quantity50
        }) as Coffee[],
        paid: false
      }
      await this.orderResource.order(order)
      this.setSnackbarEntry({
        title: 'Commande',
        icon: 'mdi-coffee-to-go',
        message: 'Votre commande a bien été prise en compte.',
        color: 'success',
        timeout: 6000
      } as SnackbarEntry)
    } catch (e) {
      console.error(e)
    } finally {
      this.orderLoading = false
      this.confirmDialog = false
      this.resetCoffeeSelection()
    }
  }
}
</script>

<style scoped>
* {
  font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
}

h2 {
  font-size: 1.2em;
}

h3 {
  font-size: 1em;
}

h6 {
  font-size: .8em;
}

.confirm.v-card {
  border: solid #929292 1px !important;
}

.coffee-choice {
  border-bottom: solid transparent 1px;
}

.coffee-choice:not(:last-child) {
  border-bottom-color: #4e4e4e !important;
}

.quantity-field {
  transform: translateY(-50%);
}
</style>
