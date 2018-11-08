<template>
  <v-card class="account" color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text">Mon compte</v-card-title>
    <v-flex>
      <v-layout column>
        <v-flex class="user-waiting-orders">
          <h3 class="white--text thin mb-2">Mes commandes en cours</h3>
          <orders-table :value="waitingOrders" :loading="waitingOrdersLoading"/>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-card-actions>
      <v-spacer/>
      <v-btn outline dark @click="$emit('close')">
        Fermer
      </v-btn>
    </v-card-actions>
  </v-card>
</template>

<script lang="ts">
import { Component, Inject, Prop, Vue, Watch } from 'vue-property-decorator'
import OrdersTable from '@/components/OrdersTable.vue'
import OrderDto from '@/api/model/OrderDto'
import OrderResource from '@/api/Order'
import Coffee from '@/api/model/Coffee'

@Component({
  components: {
    OrdersTable
  }
})
export default class Account extends Vue {
  @Inject()
  orderResource!: OrderResource

  @Prop({ type: Boolean, default: false })
  dialogStatus!: boolean

  waitingOrders: OrderDto[] = []
  waitingOrdersLoading: boolean = false

  get subheaders () {
    return [
      { text: 'Produit', sortable: false },
      { text: 'Quantité x30', sortable: false },
      { text: 'Quantité x50', sortable: false },
      { text: 'Prix unitaire', sortable: false },
      { text: 'Total', sortable: false }
    ]
  }

  calcTotalPrice (item: Coffee): string {
    if (item.unit_price) {
      const quantity30: number = parseInt(`${item.quantity30}`)
      const quantity50: number = parseInt(`${item.quantity50}`)
      return (((30 * quantity30) + (50 * quantity50)) * item.unit_price).toFixed(2)
    }
    return ''
  }

  @Watch('dialogStatus', { immediate: true })
  async onDialogStatusChanged (newVal: boolean) {
    if (newVal) {
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
}
</script>

<style scoped>
  .thin {
    font-weight: 100;
  }

  .account /deep/ td {
    white-space: nowrap;
  }

  .pointer {
    cursor: pointer;
  }
</style>

