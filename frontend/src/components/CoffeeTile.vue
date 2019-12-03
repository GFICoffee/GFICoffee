<template>
  <v-hover v-slot:default="{ hover }">
    <v-responsive
        :aspect-ratio="91/145"
        width="182px"
        class="coffee-tile secondary"
        :class="`lighten-${ hover || coffee.selected ? 2 : 1 }`"
        @click="toggleCoffeeSelection(coffee)"
    >
      <v-row no-gutters class="fill-height">
        <v-col cols="12">
          <v-responsive :aspect-ratio="1/1">
            <img :src="coffee.img" :alt="`${coffee.name} image`" class="coffee-img">
          </v-responsive>
        </v-col>
        <v-col cols="12" class="py-1 px-3">
          <v-row no-gutters>
            <v-col cols="12">
              <h3 class="coffee-name white--text">{{ coffee.name }}</h3>
            </v-col>
            <v-col cols="12">
              <h4 class="coffee-desc">{{ coffee.desc }}</h4>
            </v-col>
            <v-col cols="12">
              <h4 class="coffee-price">{{ coffee.unit_price.toFixed(2) }}€ / unité</h4>
            </v-col>
          </v-row>
        </v-col>
        <v-col cols="12" class="px-2">
          <v-row no-gutters>
            <v-col>
              <intensity
                  :length="12"
                  :value="coffee.intensity"
              />
            </v-col>
            <v-col cols="auto">
              <img :src="typeIcon" alt="Type café">
            </v-col>
          </v-row>
        </v-col>
      </v-row>
    </v-responsive>
  </v-hover>
</template>

<script lang="ts">
import { Vue, Component, Prop, Watch } from 'vue-property-decorator'
import Intensity from '@/components/Intensity.vue'
import Coffee from '@/api/model/Coffee'
import { MutationCoffee } from '@/store/coffee'

@Component({
  components: {
    Intensity
  }
})
export default class CoffeeTile extends Vue {
  @Prop({ default: null })
  coffee!: Coffee | null

  @MutationCoffee
  toggleCoffeeSelection!: (coffee: Coffee) => void

  get typeIcon (): string {
    if (this.coffee && this.coffee.type) {
      switch (this.coffee.type.type) {
        case 'ristretto':
          return require('@/assets/icons/small.png')
        case 'espresso':
          return require('@/assets/icons/medium.png')
        default:
          return require('@/assets/icons/large.png')
      }
    }
    return require('@/assets/icons/large.png')
  }
}
</script>

<style scoped>
.coffee-tile {
  position: relative;
  cursor: pointer;
  text-align: center;
  padding: 10px 0px 20px;
  transition: all .2s ease;
}

.coffee-tile:hover .coffee-img {
  transform: translate(-50%, -45%);
}

.coffee-img {
  width: 100%;
  transition: all 0.2s ease;
  position: relative;
  display: block;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
}

.coffee-name {
  font-size: 14px;
  line-height: 1.2em;
  text-align: center;
  font-weight: 400;
  margin: 0px 0px 4px;
}

.coffee-desc {
  margin: 0px 0px 10px;
  text-transform: uppercase;
  color: rgba(255, 255, 255, 0.7);
  font-size: 11px;
  line-height: 12px;
  font-weight: normal;
}

.coffee-price {
  margin: 0;
  color: rgba(255, 255, 255, 0.7);
  font-size: 10px;
  line-height: 11px;
  font-weight: normal;
}

.coffee-name,
.coffee-desc,
.coffee-price {
  font-family: "Trebuchet MS", Helvetica, arial, sans-serif;
}
</style>
