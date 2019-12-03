<template>
  <v-app id="app" dark style="background-color: black;">
    <v-row no-gutters>
      <v-col cols="12">
        <navigator/>
      </v-col>
      <v-col cols="12">
        <router-view/>
      </v-col>
    </v-row>

    <snackbar/>
  </v-app>
</template>
<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'
import Navigator from '@/components/Navigator.vue'
import Snackbar from '@/components/Snackbar.vue'

@Component({
  components: {
    Snackbar,
    Navigator
  }
})
export default class App extends Vue {
}
</script>
