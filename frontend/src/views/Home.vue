<template>
  <div class="Home">
    <coffee-grid/>
    <calculator v-if="authenticated"/>
  </div>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator'
import CoffeeGrid from '@/components/CoffeeGrid.vue'
import Calculator from '@/components/Calculator.vue'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox'
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
}
</script>

<style scoped>
* {
  font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
}
</style>
