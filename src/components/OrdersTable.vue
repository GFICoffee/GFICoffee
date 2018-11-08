<template>
  <v-data-table
      :items="value"
      hide-actions
      class="orders-table elevation-1"
      dark
      :loading="loading"
      :no-data-text="loading ? 'Chargement de vos commandes' : 'Aucune commande en cours'"
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
</template>

<script lang="ts">
import { Component, Prop, Vue } from 'vue-property-decorator';
import OrderDto from '@/api/model/OrderDto'
import Coffee from '@/api/model/Coffee'

@Component
export default class OrdersTable extends Vue {
  @Prop({ type: Array, default: () => [] })
  value!: OrderDto[]

  @Prop({ type: Boolean, default: false })
  loading!: boolean

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
      const quantity30: number = parseInt(`${item.quantity30 || 0}`)
      const quantity50: number = parseInt(`${item.quantity50 || 0}`)
      return (((30 * quantity30) + (50 * quantity50)) * item.unit_price).toFixed(2)
    }
    return ''
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
</style>

