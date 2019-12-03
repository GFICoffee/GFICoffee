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
        <v-flex v-if="loadingList" style="min-height: 70vh">
          <v-layout column justify-center fill-height>
            <v-flex shrink class="text-xs-center">
              <v-progress-circular indeterminate color="white" :size="50"/>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex shrink v-for="(coffee, i) of coffeeList" :key="i" v-else>
          <coffee-tile :coffee="coffee"/>
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Watch, Inject } from 'vue-property-decorator'
import CoffeeTile from '@/components/CoffeeTile.vue'
import Coffee from '@/api/model/Coffee'
import filter from 'lodash/filter'
import CoffeeResource from '@/api/Coffee'
import { GetterCoffee, MutationCoffee } from '@/store/coffee'

@Component({
  components: {
    CoffeeTile
  }
})
export default class CoffeeGrid extends Vue {
  @Inject()
  coffeeResource!: CoffeeResource
  @GetterCoffee
  coffeeList!: Coffee[]
  @MutationCoffee
  setCoffeeList!: (list: Coffee[]) => void

  loadingList: boolean = false

  async created () {
    this.loadingList = true
    try {
      this.setCoffeeList(await this.coffeeResource.getCoffeeList())
    } catch (e) {
      console.error(e)
    } finally {
      this.loadingList = false
    }
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
  font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
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
