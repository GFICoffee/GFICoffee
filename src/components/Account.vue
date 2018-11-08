<template>
  <v-card color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text">Mon compte</v-card-title>
    <v-flex>
      <v-layout column>
        <v-flex v-if="waitingOrders.length > 0 && !waitingOrdersLoading">
          <h3 class="white--text thin mb-2">Mes commandes en cours</h3>
          <v-data-table
              :items="waitingOrders"
              hide-actions
              hide-headers
              class="elevation-1"
              dark
          >
            <template slot="items" slot-scope="props">
              <td>{{ props.item.name }}</td>
              <td class="text-xs-right">{{ props.item.calories }}</td>
              <td class="text-xs-right">{{ props.item.fat }}</td>
              <td class="text-xs-right">{{ props.item.carbs }}</td>
            </template>
          </v-data-table>
        </v-flex>
        <v-flex v-else-if="waitingOrdersLoading">
          <v-progress-circular indeterminate color="white" :size="50" class="my-3 vertical-center"/>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-card-actions>
      <v-spacer/>
      <v-btn outline
             dark
             @click="$emit('close')"
      >
        Fermer
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator';
import OrderDto from '@/api/model/OrderDto'
import OrderResource from '@/api/Order'

@Component
export default class Account extends Vue {
  @Inject()
  orderResource!: OrderResource

  waitingOrders: OrderDto[] = []
  waitingOrdersLoading: boolean = false

  async created () {

    this.waitingOrdersLoading = true
    try {
      this.waitingOrders = await this.orderResource.getWaitingOrders()
    } catch (e) {
      console.error(e)
    } finally {
      this.waitingOrdersLoading = false
    }
  }
}
</script>

<style scoped>
  .thin {
    font-weight: 100;
  }

  .vertical-center {
    position: relative;
    left: 50%;
    transform: translateX(-50%);
  }
</style>

