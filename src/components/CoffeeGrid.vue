<template>
  <v-layout justify-center class="coffee-grid mt-4">
    <v-flex class="coffee-grid__frame">
      <v-layout wrap>
        <v-flex xs12 class="mb-4" v-if="$vuetify.breakpoint.mdAndUp">
          <v-layout wrap>
            <v-flex xs12>
              <div class="coffee-connector">&nbsp;</div>
            </v-flex>
            <v-flex class="fragments-five coffee-name">Ristretto</v-flex>
            <v-flex class="fragments-five coffee-name">Espresso</v-flex>
            <v-flex class="fragments-five coffee-name">Lungo</v-flex>
            <v-flex class="fragments-five coffee-name">Decaffeinato</v-flex>
            <v-flex class="fragments-five coffee-name">Variations</v-flex>
          </v-layout>
        </v-flex>
        <v-flex shrink v-for="(coffee, i) of coffeeList" :key="i">
          <coffee-tile v-model="coffeeList[i]"/>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Watch } from 'vue-property-decorator';
import CoffeeTile from '@/components/CoffeeTile.vue'
import Coffee from '@/models/CoffeeInterface'
import filter from 'lodash/filter'

@Component({
  components: {
    CoffeeTile
  }
})
export default class CoffeeGrid extends Vue {
  coffeeList: Coffee[] = [
    { img: require('@/assets/coffee/ristretto-intenso.png'), name: 'Ristretto Intenso', desc: 'Exceptionnelement intense et onctueux', intensity: 12, type: 'ristretto', unitPrice: 0.3 },
    { img: require('@/assets/coffee/espresso-forte.png'), name: 'Espresso Forte', desc: 'Rond et équilibré', intensity: 7, type: 'espresso', unitPrice: 0.3 },
    { img: require('@/assets/coffee/lungo-origin-guatemala.png'), name: 'Lungo Origin Guatemala', desc: 'Soyeux et affirmé', intensity: 6, type: 'lungo', unitPrice: 0.35 },
    { img: require('@/assets/coffee/espresso-decaffeinato.png'), name: 'Espresso Decaffeinato', desc: 'Dense et puissant', intensity: 7, type: 'espresso', unitPrice: 0.3 },
    { img: require('@/assets/coffee/espresso-vanilla.png'), name: 'Espresso Vanilla', desc: 'Aux arômes naturels de vanille', intensity: 7, type: 'espresso', unitPrice: 0.35 },
    { img: require('@/assets/coffee/ristretto-origin-india.png'), name: 'Ristretto Origin India', desc: 'Intense et épicé', intensity: 10, type: 'ristretto', unitPrice: 0.35 },
    { img: require('@/assets/coffee/espresso-leggero.png'), name: 'Espresso Leggero', desc: 'Léger et rafraîchissant', intensity: 6, type: 'espresso', unitPrice: 0.3 },
    { img: require('@/assets/coffee/lungo-forte.png'), name: 'Lungo Forte', desc: 'Élégant et torréfié', intensity: 4, type: 'lungo', unitPrice: 0.3 },
    { img: require('@/assets/coffee/lungo-decaffeinato.png'), name: 'Lungo Decaffeinato', desc: 'Equilibré et complexe', intensity: 4, type: 'espresso', unitPrice: 0.3 },
    { img: require('@/assets/coffee/espresso-caramel.png'), name: 'Espresso Caramel', desc: 'Aux arômes naturels de caramel', intensity: 7, type: 'espresso', unitPrice: 0.35 },
    { img: require('@/assets/coffee/ristretto.png'), name: 'Ristretto', desc: 'Intense et persistant', intensity: 9, type: 'ristretto', unitPrice: 0.3 },
    { img: require('@/assets/coffee/espresso-origin-brazil.png'), name: 'Espresso Origin Brazil', desc: 'Doux et satiné', intensity: 4, type: 'espresso', unitPrice: 0.35 },
    { img: require('@/assets/coffee/lungo-leggero.png'), name: 'Lungo Leggero', desc: 'Fleuri et Rafraîchissant', intensity: 2, type: 'lungo', unitPrice: 0.3 }
  ]

  get selectedCoffee (): Coffee[] {
    return filter(this.coffeeList, ['selected', true])
  }

  @Watch('selectedCoffee')
  onCoffeeListChanged () {
    this.$emit('input', this.selectedCoffee)
  }
}
</script>

<style scoped>
.coffee-grid__frame {
  max-width: 910px;
  flex-basis: 910px;
}

.fragments-five {
  max-width: 20%;
  flex-basis: 20%;
}

.coffee-name {
  text-transform: uppercase;
  text-align: center;
  font-family: 'Trebuchet MS',Helvetica,Arial,sans-serif;
  font-size: 18px;
  color: #B3B3B3 !important;
}

.coffee-name::before {
  border-left: 1px solid #4e4e4e;
  display: block;
  content: '';
  width: 1px;
  height: 7px;
  position: relative;
  left: 50%;
  top: -5px;
  transform: translateX(-50%);
  -ms-transform: translateX(-50%);
}

.coffee-connector {
  border-top: 1px solid #4e4e4e;
  width: 729px;
  margin: 0 auto;
  height: 1px;
  position: relative;
  top: -5px;
}
</style>
