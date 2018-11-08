<template>
  <v-card class="account" color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text">Mon compte</v-card-title>
    <v-flex>
      <v-layout column>
        <v-flex class="user-waiting-orders">
          <h3 class="white--text thin mb-2">Mes commandes en cours</h3>
          <v-data-table
              :items="waitingOrders"
              hide-actions
              class="elevation-1"
              dark
              :loading="waitingOrdersLoading"
              :no-data-text="waitingOrdersLoading ? 'Chargement de vos commandes' : 'Aucune commande en cours'"
          >
            <template slot="headers" slot-scope="props"></template>
            <v-progress-linear slot="progress" color="white" indeterminate/>
            <template slot="items" slot-scope="props">
              <tr @click="props.expanded = !props.expanded" class="pointer">
                <td>Commande #{{ props.item.id }}</td>
              </tr>
            </template>

            <template slot="expand" slot-scope="props">
              <v-card flat>
                <v-data-table
                    :items="props.item.items"
                    hide-actions
                    :headers="subheaders"
                    dark
                >
                  <template slot="items" slot-scope="subprops">
                    <tr @click="props.expanded = !props.expanded">
                      <td>{{ subprops.item.name }}</td>
                      <td>{{ subprops.item.quantity30 }}</td>
                      <td>{{ subprops.item.quantity50 }}</td>
                      <td>{{ subprops.item.unit_price }} €</td>
                      <td>{{ calcTotalPrice(subprops.item) }} €</td>
                    </tr>
                  </template>
                </v-data-table>
              </v-card>
            </template>
          </v-data-table>
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
import { Component, Inject, Prop, Vue, Watch } from 'vue-property-decorator';
import OrderDto from '@/api/model/OrderDto'
import OrderResource from '@/api/Order'
import Coffee from '@/api/model/Coffee'

@Component
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

