<template>

  <v-hover>
    <v-responsive
        :aspect-ratio="91/141"
        width="182px"
        class="coffee-tile secondary"
        slot-scope="{ hover }"
        :class="`lighten-${ hover || coffee.selected ? 2 : 1 }`"
        @click="coffee.selected = !coffee.selected; $emit('input', coffee)"
    >
      <v-layout column fill-height>
        <v-flex shrink>
          <v-responsive :aspect-ratio="1/1">
            <img :src="coffee.img" :alt="`${coffee.name} image`" class="coffee-img">
          </v-responsive>
        </v-flex>
        <v-flex class="py-1 px-3">
          <v-layout wrap>
            <v-flex xs12>
              <h3 class="coffee-name white--text">{{ coffee.name }}</h3>
            </v-flex>
            <v-flex xs12>
              <h4 class="coffee-desc">{{ coffee.desc }}</h4>
            </v-flex>
          </v-layout>
        </v-flex>
        <v-flex shrink class="px-2">
          <v-layout>
            <v-flex>
              <intensity
                  :length="12"
                  :value="coffee.intensity"
              />
            </v-flex>
            <v-flex shrink>
              <img :src="typeIcon" alt="Type café">
            </v-flex>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-responsive>
  </v-hover>
</template>
<script lang="ts">
import { Vue, Component, Prop, Watch } from 'vue-property-decorator';
import Intensity from '@/components/Intensity.vue';
import Coffee from '@/api/model/Coffee';

@Component({
  components: {
    Intensity
  }
})
export default class CoffeeTile extends Vue {
  @Prop({ default: null })
  value!: Coffee | null

  coffee: Coffee | null = null

  @Watch('value', { immediate: true })
  onValueChanged () {
    if (this.value) {
      this.coffee = this.value
    }
  }

  get typeIcon (): string {
    if (this.coffee) {
      switch (this.coffee.type) {
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
  width: calc(100% - 10px);
}

.coffee-img {
  width: 100%;
  transition: all 0.05s ease;
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

.coffee-name,
.coffee-desc {
  font-family: "Trebuchet MS", Helvetica, arial, sans-serif;
}
</style>