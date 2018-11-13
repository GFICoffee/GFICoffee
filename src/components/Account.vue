<template>
  <v-card class="account" color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text">Mon compte</v-card-title>
    <v-flex>
      <v-layout column>
        <v-flex class="user-waiting-orders">
          <h3 class="white--text thin mb-2">Mes commandes en cours</h3>
          <orders-table :value="waitingOrders" :loading="waitingOrdersLoading" @delete="deleteOrder"/>
        </v-flex>

        <v-flex class="all-waiting-orders" v-if="isAdmin">
          <v-layout class="mb-2 mt-3">
            <v-flex shrink>
              <h3 class="white--text thin">Toutes les commandes en cours</h3>
            </v-flex>
            <v-spacer/>
            <v-flex shrink>
              <v-tooltip top>
                <v-icon color="white" slot="activator" @click="exportAllWaitingOrders()" :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0">mdi-file-export</v-icon>
                <span>Exporter</span>
              </v-tooltip>
            </v-flex>
            <v-flex shrink class="ml-3">
              <v-tooltip top>
                <v-icon color="white" slot="activator" @click="validateOrdersDialog = true" :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0">mdi-checkbox-multiple-marked</v-icon>
                <span>Valider toutes les commandes</span>
              </v-tooltip>
            </v-flex>
          </v-layout>

          <orders-table :value="allWaitingOrders" :loading="allWaitingOrdersLoading" @delete="deleteOrder"/>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-card-actions>
      <v-spacer/>
      <v-btn outline @click="$emit('close')">
        Fermer
      </v-btn>
    </v-card-actions>
    <v-dialog v-model="validateOrdersDialog" width="500">
      <v-card color="secondary lighten-1 pb-2 px-3">
        <v-card-title class="headline white--text pl-0">Confirmation</v-card-title>
        <v-flex class="white--text">
          Êtes-vous sûr de vouloir valider toutes les commandes en attente ?
        </v-flex>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click="validateOrdersDialog = false">Non</v-btn>
          <v-btn outline @click="validateAllWaitingOrders()" :loading="validateOrdersLoading">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script lang="ts">
import { Component, Inject, Prop, Vue, Watch } from 'vue-property-decorator'
import OrdersTable from '@/components/OrdersTable.vue'
import OrderDto from '@/api/model/OrderDto'
import OrderResource from '@/api/Order'
import Coffee from '@/api/model/Coffee'
import { GetterAuth, Payload } from '@/store/auth'
import { environment } from '@/environments/environment.ts'
import { AxiosResponse } from 'axios'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox'

@Component({
  components: {
    OrdersTable
  }
})
export default class Account extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, AxiosResponse>;
  @GetterAuth
  payload!: Payload
  @Inject()
  orderResource!: OrderResource

  @Prop({ type: Boolean, default: false })
  dialogStatus!: boolean

  waitingOrders: OrderDto[] = []
  allWaitingOrders: OrderDto[] = []
  waitingOrdersLoading: boolean = false
  allWaitingOrdersLoading: boolean = false

  validateOrdersDialog: boolean = false
  validateOrdersLoading: boolean = false

  get subheaders () {
    return [
      { text: 'Produit', sortable: false },
      { text: 'Quantité x30', sortable: false },
      { text: 'Quantité x50', sortable: false },
      { text: 'Prix unitaire', sortable: false },
      { text: 'Total', sortable: false }
    ]
  }

  get isAdmin (): boolean {
    return this.payload.roles.indexOf('ROLE_ADMIN') >= 0
  }

  calcTotalPrice (item: Coffee): string {
    if (item.unit_price) {
      const quantity30: number = parseInt(`${item.quantity30}`)
      const quantity50: number = parseInt(`${item.quantity50}`)
      return (((30 * quantity30) + (50 * quantity50)) * item.unit_price).toFixed(2)
    }
    return ''
  }

  removeOrderFromList (id: string | number, list: OrderDto[]): boolean {
    for (const order of list) {
      if (`${order.id}` === `${id}`) {
        const index = list.indexOf(order)
        list.splice(index, 1)
        return true
      }
    }
    return false
  }

  async deleteOrder (id: string | number) {
    this.waitingOrdersLoading = true
    this.allWaitingOrdersLoading = true
    try {
      await this.orderResource.deleteOrder(id)
      this.removeOrderFromList(id, this.waitingOrders)
      if (this.isAdmin) {
        this.removeOrderFromList(id, this.allWaitingOrders)
      }
    } catch (e) {
      console.error(e)
    } finally {
      this.waitingOrdersLoading = false
      this.allWaitingOrdersLoading = false
    }
  }

  async exportAllWaitingOrders () {
    window.location.href = `${environment.apiBaseUrl}/orders/waiting-all/export?bearer=${this.auth.getTokens() ? this.auth.getTokens().access.value : ''}`
  }

  async validateAllWaitingOrders () {
    this.validateOrdersLoading = true
    try {
      await this.orderResource.validateAllWaitingOrders()
      this.allWaitingOrders = []
      this.waitingOrders = []
      this.validateOrdersDialog = false
    } catch (e) {
      console.error(e)
    } finally {
      this.validateOrdersLoading = false
    }
  }

  @Watch('dialogStatus', { immediate: true })
  async onDialogStatusChanged (newVal: boolean) {
    if (newVal) {
      this.waitingOrdersLoading = true
      this.orderResource.getWaitingOrders().then((result) => {
        this.waitingOrders = result
        this.waitingOrdersLoading = false
      }).catch((err) => {
        console.log(err)
        this.waitingOrdersLoading = false
      })

      if (this.isAdmin) {
        this.allWaitingOrdersLoading = true
        this.orderResource.getAllWaitingOrders().then((result) => {
          this.allWaitingOrders = result
          this.allWaitingOrdersLoading = false
        }).catch((err) => {
          console.log(err)
          this.allWaitingOrdersLoading = false
        })
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

