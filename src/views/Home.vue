<template>
  <div class="Home">
    <coffee-grid/>
  </div>
</template>
<script lang="ts">
import { Vue, Component } from 'vue-property-decorator'
import CoffeeGrid from '@/components/CoffeeGrid.vue'

@Component({
  components: {
    CoffeeGrid
  }
})
export default class Home extends Vue {
}
</script>
