<template>
  <v-app id="app" class="secondary">
    <v-layout column wrap>
      <v-flex shrink>
        <navigator/>
      </v-flex>
      <v-flex>
        <router-view/>
      </v-flex>
    </v-layout>
  </v-app>
</template>
<script lang="ts">
import Vue from 'vue';
import Component from 'vue-class-component';
import Navigator from '@/components/Navigator.vue';

@Component({
  components: {
    Navigator
  }
})
export default class App extends Vue {
}
</script>

