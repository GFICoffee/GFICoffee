<template>
  <v-row no-gutters justify="center" class="coffee-grid mt-4">
    <v-col class="coffee-grid__frame">
      <v-row no-gutters>
        <v-col cols="12" class="mb-4" v-if="$vuetify.breakpoint.mdAndUp">
          <v-row no-gutters>
            <v-col cols="12">
              <div class="coffee-connector">&nbsp;</div>
            </v-col>
            <v-col class="fragments-five coffee-name">Ristretto</v-col>
            <v-col class="fragments-five coffee-name">Espresso</v-col>
            <v-col class="fragments-five coffee-name">Lungo</v-col>
            <v-col class="fragments-five coffee-name">Decaffeinato</v-col>
            <v-col class="fragments-five coffee-name">Variations</v-col>
          </v-row>
        </v-col>
        <v-col v-if="loadingList" style="min-height: 70vh">
          <v-row no-gutters justify="center" align="center" class="fill-height">
            <v-col cols="auto" class="text-xs-center">
              <v-progress-circular indeterminate color="white" :size="50"/>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="auto" v-for="(coffee, i) of coffeeList" :key="i" v-else>
          <coffee-tile :coffee="coffee"/>
        </v-col>
      </v-row>
    </v-col>
  </v-row>
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
