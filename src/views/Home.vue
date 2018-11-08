<template>
  <div class="Home">
    <coffee-grid @input="(e) => {  selectedCoffee = e }"/>
    <calculator v-model="selectedCoffee" v-if="authenticated"/>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator';
import CoffeeGrid from '@/components/CoffeeGrid.vue'
import Calculator from '@/components/Calculator.vue'
import Coffee from '@/api/model/coffee'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox/dist/lib';
import { GetterAuth } from '@/store/auth'

@Component({
  components: {
    CoffeeGrid,
    Calculator
  }
})
export default class Home extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>
  @GetterAuth
  authenticated!: boolean

  selectedCoffee: Coffee[] = []
}
</script>

<style scoped>
* {
  font-family: 'Trebuchet MS',Helvetica,Arial,sans-serif;
}
</style>
