<template>
  <v-layout class="intensity">
    <v-flex shrink>
      <div class="intensity-box white--text">{{ value }}</div>
    </v-flex>
    <v-flex>
      <div class="intensity-bar">
        <span
            v-for="i in range" :key="i"
            class="intensity-item"
            :class="{ white: i < value, 'secondary lighten-3': i >= value }"
        >&nbsp;</span>
      </div>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Prop } from 'vue-property-decorator'

@Component
export default class Intensity extends Vue {
  @Prop({ type: Number, default: 12 })
  length!: number

  @Prop({ type: Number, default: 0 })
  value!: number

  get range (): number[] {
    const range: number[] = []
    for (let i = 0; i < this.length; i++) {
      range.push(i)
    }
    return range
  }
}
</script>

<style scoped>
.intensity-item {
  width: 5px;
  height: 5px;
  display: inline-block;
  margin-right: 3px;
  position: relative;
  top: 9px;
  padding: 0px !important;
}

.intensity-box {
  border-radius: 2px;
  -moz-border-radius: 2px;
  -webkit-border-radius: 2px;
  border: 1px solid rgba(255, 255, 255, 0.4);
  display: inline-block;
  padding: 3px !important;
  margin-right: 4px;
  text-align: center;
  font-size: 12px;
  width: 20px;
  height: 20px;
  font-family: 'Trebuchet MS', Helvetica, Arial, sans-serif;
  font-size: 12px;
  font-weight: normal;
  line-height: 12px;
}
</style>
