<template>
  <div class="Home">
    <coffee-grid @input="(e) => {  selectedCoffee = e }"/>
    <v-layout justify-center v-if="authenticated">
      <v-flex md9 xs12>
        <v-layout column>
          <v-flex v-for="(coffee, i) of selectedCoffee" :key="i" class="coffee-choice pt-4 pb-2">
            <v-layout>
              <v-flex>
                <h2 class="white--text">{{ coffee.name }}</h2>
                <h6 class="white--text">{{ coffee.unitPrice.toFixed(2) }}&nbsp;€ / unité</h6>
              </v-flex>
              <v-flex shrink>
                <v-select
                    :items="quantities"
                    dark
                    class="quantity-field mr-3"
                    label="Quantité (x30)"
                    color="white"
                    hide-details
                    type="number"
                    v-model="coffee.quantity30"
                />
              </v-flex>
              <v-flex shrink>
                <v-select
                    :items="quantities"
                    dark
                    class="quantity-field mr-3"
                    label="Quantité (x50)"
                    color="white"
                    hide-details
                    type="number"
                    v-model="coffee.quantity50"
                />
              </v-flex>
              <v-flex shrink>
                <h3 class="white--text">{{ coffePrice(coffee).toFixed(2) }}&nbsp;€</h3>
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
        </v-layout>
      </v-flex>
    </v-layout>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator';
import CoffeeGrid from '@/components/CoffeeGrid.vue'
import Coffee from '@/models/CoffeeInterface'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox/dist/lib/auth-toolbox';
import { GetterAuth } from '../store/auth'

@Component({
  components: {
    CoffeeGrid
  }
})
export default class Home extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>
  @GetterAuth
  authenticated!: boolean

  selectedCoffee: Coffee[] = []
  quantities: number[] = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10]

  coffePrice (coffee: Coffee): number {
    let total: number = 0

    if (coffee.quantity30) {
      total += coffee.unitPrice * ( typeof coffee.quantity30 === 'string' ? parseFloat(coffee.quantity30) : coffee.quantity30) * 30
    }

    if (coffee.quantity50) {
      total += coffee.unitPrice * ( typeof coffee.quantity50 === 'string' ? parseFloat(coffee.quantity50) : coffee.quantity50) * 30
    }
    return total
  }

  get totalPrice (): number {
    let total = 0

    for (const coffee of this.selectedCoffee) {
      total += this.coffePrice(coffee)
    }
    return total
  }
}
</script>

<style scoped>
* {
  font-family: 'Trebuchet MS',Helvetica,Arial,sans-serif;
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
