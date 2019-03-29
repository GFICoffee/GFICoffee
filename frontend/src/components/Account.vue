<template>
  <v-card class="account pb-2 px-3" color="secondary lighten-1">
    <v-card-title class="headline white--text">Mon compte</v-card-title>
    <v-flex>
      <v-layout column>
        <v-flex class="user-waiting-orders">
          <h3 class="white--text thin mb-2">Mes commandes en cours</h3>
          <orders-table :value="waitingOrders" :loading="waitingOrdersLoading" @delete="deleteOrder"/>
        </v-flex>

        <v-flex class="not-waiting-orders">
          <h3 class="white--text thin mb-2 mt-3">Toutes mes commandes traitées</h3>
          <orders-table :value="notWaitingOrders" :loading="notWaitingOrdersLoading" :canDelete="false"/>
        </v-flex>

        <v-flex class="all-waiting-orders" v-if="isAdmin">
          <v-layout class="my-4">
            <v-flex>
              <hr/>
            </v-flex>
          </v-layout>

          <v-layout class="mb-2">
            <v-flex shrink>
              <h3 class="white--text thin">Toutes les commandes en cours</h3>
            </v-flex>
            <v-spacer/>
            <v-flex shrink>
              <v-tooltip top>
                <v-icon color="white" slot="activator" @click="exportAllWaitingOrders()"
                        :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0">mdi-file-export
                </v-icon>
                <span>Exporter</span>
              </v-tooltip>
            </v-flex>
            <v-flex shrink class="ml-3">
              <v-tooltip top>
                <v-icon color="white" slot="activator" @click="validateOrdersDialog = true"
                        :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0">
                  mdi-checkbox-multiple-marked
                </v-icon>
                <span>Valider toutes les commandes</span>
              </v-tooltip>
            </v-flex>
          </v-layout>

          <orders-table :value="allWaitingOrders" :loading="allWaitingOrdersLoading" @delete="deleteOrder"/>
        </v-flex>

        <v-flex class="all-not-waiting-orders" v-if="isAdmin">
          <v-layout class="mb-2 mt-3">
            <v-flex shrink>
              <h3 class="white--text thin">Toutes les commandes traitées</h3>
            </v-flex>
            <v-spacer/>
            <v-flex shrink>
              <v-tooltip top>
                <v-icon color="white" slot="activator" @click="validateNotificationDialog = true"
                        :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0">mdi-send
                </v-icon>
                <span>Notifier la réception</span>
              </v-tooltip>
            </v-flex>
          </v-layout>
          <orders-table :value="allNotWaitingOrders" :loading="allNotWaitingOrdersLoading" :canDelete="false" :payControl="true" @update="updateOrder"/>
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
          Êtes-vous sûr de vouloir valider toutes les commandes en attente ?<br/>
          Pensez à <span class="font-weight-bold green--text">exporter</span> les données avant de les valider.
        </v-flex>
        <v-card-actions>
          <v-btn flat :disabled="allWaitingOrdersLoading || allWaitingOrders.length === 0" @click="exportAllWaitingOrders()">Exporter</v-btn>
          <v-spacer/>
          <v-btn flat @click="validateOrdersDialog = false">Non</v-btn>
          <v-btn outline @click="validateAllWaitingOrders()" :loading="validateOrdersLoading">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>

    <v-dialog v-model="validateNotificationDialog" width="500">
      <v-card color="secondary lighten-1 pb-2 px-3">
        <v-card-title class="headline white--text pl-0">Confirmation</v-card-title>
        <v-flex class="white--text">
          <p>Êtes-vous sûr de vouloir notifier les personnes dont les commandes viennent d'arriver ?</p>
          <p class="font-weight-thin font-italic caption">Cette action enverra un mail aux personnes de la liste des commandes traitées n'ayant pas encore payé pour leur
            indiquer qu'il peuvent venir retirer et payer leur commande.</p>
        </v-flex>
        <v-flex>
          <v-form v-model="numFactureFormValid" ref="numFactureForm">
            <v-text-field
              v-model="numfacture"
              type="number"
              label="Numéro de facture"
              outline
              :rules="numFactureRules"
              required
            />
          </v-form>
        </v-flex>
        <v-card-actions>
          <v-spacer/>
          <v-btn flat @click="validateNotificationDialog = false">Non</v-btn>
          <v-btn outline @click="sendPickupNotification()" :loading="validateNotificationLoading">Oui</v-btn>
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
import NotificationResource from '@/api/Notification'
import { MutationSnackbar, SnackbarEntry } from '@/store/snackbar'

@Component({
  components: {
    OrdersTable
  }
})
export default class Account extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, AxiosResponse>
  @GetterAuth
  payload!: Payload
  @Inject()
  orderResource!: OrderResource
  @Inject()
  notificationResource!: NotificationResource
  @MutationSnackbar
  setSnackbarEntry!: (entry: SnackbarEntry) => void

  @Prop({ type: Boolean, default: false })
  dialogStatus!: boolean

  waitingOrders: OrderDto[] = []
  notWaitingOrders: OrderDto[] = []
  allWaitingOrders: OrderDto[] = []
  allNotWaitingOrders: OrderDto[] = []
  waitingOrdersLoading: boolean = false
  notWaitingOrdersLoading: boolean = false
  allWaitingOrdersLoading: boolean = false
  allNotWaitingOrdersLoading: boolean = false

  validateOrdersDialog: boolean = false
  validateOrdersLoading: boolean = false
  validateNotificationDialog: boolean = false
  validateNotificationLoading: boolean = false

  numfacture: string = ''
  numFactureFormValid: boolean = false
  numFactureRules: any[] = [
    (v: any) => !!v || 'Le numéro de facture est obligatoire'
  ]

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
    if (this.payload) {
      return this.payload.roles.indexOf('ROLE_ADMIN') >= 0
    }
    return false
  }

  calcTotalPrice (item: Coffee): string {
    if (item.unit_price) {
      const quantity30: number = parseInt(`${item.quantity30}`, undefined)
      const quantity50: number = parseInt(`${item.quantity50}`, undefined)
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

  async updateOrder (order: OrderDto) {
    try {
      this.allNotWaitingOrdersLoading = true
      await this.orderResource.updateOrder(order)
    } catch (e) {
      console.error(e)
    } finally {
      this.allNotWaitingOrdersLoading = false
    }
  }

  async exportAllWaitingOrders () {
    const tokens = this.auth.getTokens()
    const token = tokens ? tokens.access.value : undefined
    if (token) {
      window.location.href = `${environment.apiBaseUrl}/orders/waiting-all/export?bearer=${token}`
    } else {
      window.location.href = `${environment.apiBaseUrl}/orders/waiting-all/export`
    }
  }

  async validateAllWaitingOrders () {
    this.validateOrdersLoading = true
    try {
      await this.orderResource.validateAllWaitingOrders()
      this.allWaitingOrders = []
      this.waitingOrders = []
      this.validateOrdersDialog = false
      this.reloadAllNotWaitingOrders()
    } catch (e) {
      console.error(e)
    } finally {
      this.validateOrdersLoading = false
    }
  }

  async sendPickupNotification () {
    if ((this as any).$refs.numFactureForm.validate()) {
      this.validateNotificationLoading = true
      try {
        await this.notificationResource.sendPickupNotification(this.numfacture)
        this.validateNotificationDialog = false
        this.setSnackbarEntry({
          title: 'Notifications',
          icon: 'mdi-send',
          message: 'Notifications envoyées avec succès.',
          color: 'success',
          timeout: 6000
        } as SnackbarEntry)
      } catch (e) {
        console.error(e)
      } finally {
        this.validateNotificationLoading = false
      }
    }
  }

  @Watch('dialogStatus', { immediate: true })
  onDialogStatusChanged (newVal: boolean) {
    if (newVal) {
      this.waitingOrdersLoading = true
      this.orderResource.getWaitingOrders().then((result) => {
        this.waitingOrders = result
        this.waitingOrdersLoading = false
      }).catch((err) => {
        console.error(err)
        this.waitingOrdersLoading = false
      })

      if (this.isAdmin) {
        this.allWaitingOrdersLoading = true
        this.orderResource.getAllWaitingOrders().then((result) => {
          this.allWaitingOrders = result
          this.allWaitingOrdersLoading = false
        }).catch((err) => {
          console.error(err)
          this.allWaitingOrdersLoading = false
        })
        this.reloadAllNotWaitingOrders()
      } else {
        this.notWaitingOrdersLoading = true
        this.orderResource.getNotWaitingOrders().then((result) => {
          this.notWaitingOrders = result
          this.notWaitingOrdersLoading = false
        }).catch((err) => {
          console.error(err)
          this.notWaitingOrdersLoading = false
        })
      }
    }
  }

  private reloadAllNotWaitingOrders () {
    this.allNotWaitingOrdersLoading = true
    this.orderResource.getAllNotWaitingOrders().then((result) => {
      this.allNotWaitingOrders = result
      this.allNotWaitingOrdersLoading = false
    }).catch((err) => {
      console.error(err)
      this.allNotWaitingOrdersLoading = false
    })

    this.notWaitingOrdersLoading = true
    this.orderResource.getNotWaitingOrders().then((result) => {
      this.notWaitingOrders = result
      this.notWaitingOrdersLoading = false
    }).catch((err) => {
      console.error(err)
      this.notWaitingOrdersLoading = false
    })
  }
}
</script>

<style scoped>
.thin {
  font-weight: 100;
}

.v-card.account {
  border: solid #929292 1px !important;
}

.account /deep/ td {
  white-space: nowrap;
}

.pointer {
  cursor: pointer;
}
</style>
