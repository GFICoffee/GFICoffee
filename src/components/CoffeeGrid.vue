<template>
  <v-layout justify-center class="coffee-grid">
    <v-flex class="coffee-grid__frame">
      <v-layout wrap>
        <v-flex shrink v-for="(coffee, i) of coffeeList" :key="i">
          <coffee-tile :coffee="coffee"/>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import CoffeeTile from '@/components/CoffeeTile.vue'
import Coffee from '@/models/CoffeeInterface'

@Component({
  components: {
    CoffeeTile
  }
})
export default class CoffeeGrid extends Vue {
  coffeeList: Coffee[] = [
    { img: require('@/assets/coffee/ristretto-intenso.png'), name: 'Ristretto Intenso', desc: 'Exceptionnelement intense et onctueux', intensity: 12, type: 'ristretto' },
    { img: require('@/assets/coffee/espresso-forte.png'), name: 'Espresso Forte', desc: 'Rond et équilibré', intensity: 7, type: 'espresso' },
    { img: require('@/assets/coffee/lungo-origin-guatemala.png'), name: 'Lungo Origin Guatemala', desc: 'Soyeux et affirmé', intensity: 6, type: 'lungo' },
    { img: require('@/assets/coffee/espresso-decaffeinato.png'), name: 'Espresso Decaffeinato', desc: 'Dense et puissant', intensity: 7, type: 'espresso' },
    { img: require('@/assets/coffee/espresso-vanilla.png'), name: 'Espresso Vanilla', desc: 'Aux arômes naturels de vanille', intensity: 7, type: 'espresso' },
    { img: require('@/assets/coffee/ristretto-origin-india.png'), name: 'Ristretto Origin India', desc: 'Intense et épicé', intensity: 10, type: 'ristretto' },
    { img: require('@/assets/coffee/espresso-leggero.png'), name: 'Espresso Leggero', desc: 'Léger et rafraîchissant', intensity: 6, type: 'espresso' },
    { img: require('@/assets/coffee/lungo-forte.png'), name: 'Lungo Forte', desc: 'Élégant et torréfié', intensity: 4, type: 'lungo' },
    { img: require('@/assets/coffee/lungo-decaffeinato.png'), name: 'Lungo Decaffeinato', desc: 'Equilibré et complexe', intensity: 4, type: 'espresso' },
    { img: require('@/assets/coffee/espresso-caramel.png'), name: 'Espresso Caramel', desc: 'Aux arômes naturels de caramel', intensity: 7, type: 'espresso' },
    { img: require('@/assets/coffee/ristretto.png'), name: 'Ristretto', desc: 'Intense et persistant', intensity: 9, type: 'ristretto' },
    { img: require('@/assets/coffee/espresso-origin-brazil.png'), name: 'Espresso Origin Brazil', desc: 'Doux et satiné', intensity: 4, type: 'espresso' },
    { img: require('@/assets/coffee/lungo-leggero.png'), name: 'Lungo Leggero', desc: 'Fleuri et Rafraîchissant', intensity: 2, type: 'lungo' }
  ]
}
</script>

<style scoped>
.coffee-grid__frame {
  max-width: 910px;
  flex-basis: 910px;
}
</style>
