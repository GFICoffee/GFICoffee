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
      <template slot="items" slot-scope="props">
        <tr @click="props.expanded = !props.expanded" class="pointer">
          <td>
            <v-layout>
              <v-flex>Commande #{{ props.item.id }}</v-flex>
              <v-flex shrink v-if="props.item.user">{{ props.item.user.username }}</v-flex>
              <v-flex shrink class="actions ml-3">
                <v-layout>
                  <v-flex>
                    <v-tooltip top>
                      <v-icon slot="activator" @click.stop.prevent="deleteDialog = true; deleteId = props.item.id">mdi-delete</v-icon>
                      <span>Supprimer</span>
                    </v-tooltip>
                  </v-flex>
                </v-layout>
              </v-flex>
            </v-layout>
          </td>
        </tr>
      </template>

      <template slot="expand" slot-scope="props">
        <v-card flat>
          <v-data-table
              class="subtable"
              :items="props.item.items"
              hide-actions
              :headers="subheaders"
          >
            <template slot="items" slot-scope="subprops">
              <tr @click="props.expanded = !props.expanded">
                <td>{{ subprops.item.name }}</td>
                <td class="text-xs-center">{{ subprops.item.quantity30 }}</td>
                <td class="text-xs-center">{{ subprops.item.quantity50 }}</td>
                <td class="text-xs-center">{{ subprops.item.unit_price }} €</td>
                <td class="text-xs-center">{{ calcTotalPrice(subprops.item) }} €</td>
              </tr>
            </template>
          </v-data-table>
        </v-card>
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
          <v-btn flat @click="deleteDialog = false">Non</v-btn>
          <v-btn outline @click="() => { deleteOrder(deleteId) }">Oui</v-btn>
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
}
</script>

<style scoped>
.thin {
  font-weight: 100;
}

.orders-table /deep/ td {
  white-space: nowrap;
}

.pointer {
  cursor: pointer;
}

.subtable /deep/ .v-datatable{
  background-color: #525252;
}
</style>
