<template>
  <v-layout wrap justify-center class="navigator py-3">
    <v-flex sm8 :shrink="$vuetify.breakpoint.xsOnly">
      <v-layout class="logos">
        <v-flex shrink>
          <img :src="logo" alt="Logo GFI" height="60">
        </v-flex>
        <v-flex shrink class="mx-2">
          <v-icon color="white" class="x">mdi-close</v-icon>
        </v-flex>
        <v-flex shrink>
          <img :src="logoNespresso" alt="Logo Nespresso" height="60">
        </v-flex>
      </v-layout>
    </v-flex>
  </v-layout>
</template>
<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'

@Component
export default class Navigator extends Vue {
  logo: string = require('@/assets/logo.png')
  logoNespresso: string = require('@/assets/nespresso-logo.png')
}
</script>

<style scoped>
.navigator {
  background: -moz-linear-gradient(left, rgba(0,0,0,0) 0%, rgb(17,17,17) 100%); /* FF3.6-15 */
  background: -webkit-linear-gradient(left, rgba(0,0,0,0) 0%,rgb(17,17,17) 100%); /* Chrome10-25,Safari5.1-6 */
  background: linear-gradient(to right, rgba(0,0,0,0) 0%,rgb(17,17,17) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
}

.x {
  display: block;
  position: relative;
  top: 60%;
  transform: translateY(-50%);
  font-size: 1em;
}
</style>
