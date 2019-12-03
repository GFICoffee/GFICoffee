<template>
  <div class="orders-table">
    <v-data-table
      :items="value"
      class="elevation-1"
      :loading="loading"
      :no-data-text="loading ? 'Chargement de vos commandes' : 'Aucune commande en cours'"
    >
      <template slot="headers" slot-scope="props"></template>
      <v-progress-linear slot="progress" color="white" indeterminate/>
      <template v-slot:item="props">
        <tr>
          <td class="pa-0">
            <v-expansion-panels accordion>
              <v-expansion-panel>
                <v-expansion-panel-header>
                  <v-row no-gutters align="center">
                    <v-col>Commande #{{ props.item.id }}</v-col>
                    <v-col cols="auto" v-if="props.item.user">{{ props.item.user.username }}</v-col>
                    <v-col cols="auto" class="actions ml-3">
                      <v-row no-gutters>
                        <v-col v-if="payControl">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-switch v-on="on" :disabled="loading" @click.native.stop @change="updateOrder(props.item)"
                                        v-model="props.item.paid" color="primary" hide-details class="ma-0"/>
                            </template>
                            <span>A payé ?</span>
                          </v-tooltip>
                        </v-col>
                        <v-col v-if="canDelete">
                          <v-tooltip top>
                            <template v-slot:activator="{ on }">
                              <v-icon v-on="on" @click.stop.prevent="deleteDialog = true; deleteId = props.item.id">
                                mdi-delete
                              </v-icon>
                            </template>
                            <span>Supprimer</span>
                          </v-tooltip>
                        </v-col>
                      </v-row>
                    </v-col>
                  </v-row>
                </v-expansion-panel-header>
                <v-expansion-panel-content>
                  <v-simple-table class="secondary lighten-3">
                    <thead>
                      <tr>
                        <th>Produit</th>
                        <th class="text-xs-center">Quantité x30</th>
                        <th class="text-xs-center">Quantité x50</th>
                        <th class="text-xs-center">Prix unitaire</th>
                        <th class="text-xs-center">Total</th>
                      </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(item, i) of props.item.items" :key="i">
                      <td>{{ item.name }}</td>
                      <td class="text-xs-center">{{ item.quantity30 }}</td>
                      <td class="text-xs-center">{{ item.quantity50 }}</td>
                      <td class="text-xs-center">{{ item.unit_price }} €</td>
                      <td class="text-xs-center">{{ calcTotalPrice(item) }} €</td>
                    </tr>
                    </tbody>
                  </v-simple-table>
                </v-expansion-panel-content>
              </v-expansion-panel>
            </v-expansion-panels>
          </td>
        </tr>
      </template>
    </v-data-table>

    <v-dialog
      v-model="deleteDialog"
      width="500"
      :fullscreen="$vuetify.breakpoint.smAndDown"
    >
      <v-card color="secondary lighten-1 pb-2 px-3">
        <v-card-title class="headline white--text">Supprimer</v-card-title>
        <v-card-text class="white--text">Êtes-vous sûr de vouloir supprimer cette commande ?</v-card-text>
        <v-card-actions>
          <v-spacer/>
          <v-btn text @click="deleteDialog = false">Non</v-btn>
          <v-btn outlined @click="() => { deleteOrder(deleteId) }">Oui</v-btn>
        </v-card-actions>
      </v-card>
    </v-dialog>
  </div>
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator'
import OrderDto from '@/api/model/OrderDto'
import Coffee from '@/api/model/Coffee'

@Component
export default class OrdersTable extends Vue {
  @Prop({ type: Array, default: () => [] })
  value!: OrderDto[]

  @Prop({ type: Boolean, default: false })
  loading!: boolean

  @Prop({ type: Boolean, default: true })
  canDelete!: boolean

  @Prop({ type: Boolean, default: false })
  payControl!: boolean

  deleteDialog: boolean = false
  deleteId: number | string | null = null

  get subheaders () {
    return [
      { text: 'Produit', sortable: false },
      { text: 'Quantité x30', sortable: false, class: 'text-xs-center' },
      { text: 'Quantité x50', sortable: false, class: 'text-xs-center' },
      { text: 'Prix unitaire', sortable: false, class: 'text-xs-center' },
      { text: 'Total', sortable: false, class: 'text-xs-center' }
    ]
  }

  calcTotalPrice (item: Coffee): string {
    if (item.unit_price) {
      const quantity30: number = parseInt(`${item.quantity30 || 0}`, undefined)
      const quantity50: number = parseInt(`${item.quantity50 || 0}`, undefined)
      return (((30 * quantity30) + (50 * quantity50)) * item.unit_price).toFixed(2)
    }
    return ''
  }

  deleteOrder (id: string | number) {
    this.$emit('delete', id)
    this.deleteDialog = false
  }

  updateOrder (order: OrderDto) {
    this.$emit('update', order)
  }
}
</script>

<style scoped>
.orders-table /deep/ td {
  white-space: nowrap;
}

.orders-table /deep/ .v-expansion-panel-content__wrap {
  padding: 0;
}

.pointer {
  cursor: pointer;
}

.subtable /deep/ .v-datatable {
  background-color: #525252;
}
</style>
