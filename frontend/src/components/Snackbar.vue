<template>
  <v-snackbar class="snackbar-global" :multi-line="!!currentSnackbarEntry.title && !!currentSnackbarEntry.message"
              v-if="currentSnackbarEntry"
              v-bind="currentSnackbarEntry" v-model="snackbarDisplayed">
    <v-layout>
      <v-flex shrink v-if="currentSnackbarEntry.icon" class="pr-2">
        <v-icon class="vertical-center">{{ currentSnackbarEntry.icon }}</v-icon>
      </v-flex>
      <v-flex class="white--text">
        <v-layout column>
          <v-flex v-if="currentSnackbarEntry.title">
            <h4>{{ currentSnackbarEntry.title }}</h4>
          </v-flex>
          <v-flex v-if="currentSnackbarEntry.message" v-html="currentSnackbarEntry.message">
          </v-flex>
        </v-layout>
      </v-flex>
      <v-flex shrink class="pl-3" v-if="currentSnackbarEntry.button">
        <v-btn dark flat @click="currentSnackbarEntry.button.action()" class="vertical-center">{{ currentSnackbarEntry.button.text }}</v-btn>
      </v-flex>
      <v-flex shrink class="pl-2" v-if="currentSnackbarEntry.closable">
        <v-btn dark flat @click.native="snackbarDisplayed = false" class="vertical-center">
          <v-icon>mdi-close</v-icon>
        </v-btn>
      </v-flex>
    </v-layout>
  </v-snackbar>
</template>

<script lang="ts">
import Vue from 'vue'
import Component from 'vue-class-component'

import { GetterSnackbar, MutationSnackbar, SnackbarEntry } from '@/store/snackbar'
import { Watch } from 'vue-property-decorator'

@Component
export default class Snackbar extends Vue {
  @MutationSnackbar
  setSnackbarEntry!: (entry: SnackbarEntry) => void

  @GetterSnackbar
  currentSnackbarEntry!: SnackbarEntry | SnackbarEntry

  snackbarDisplayed: boolean = false

  @Watch('currentSnackbarEntry')
  onSnackbarEntryChange (entry?: SnackbarEntry | null) {
    this.snackbarDisplayed = !!entry
  }
}
</script>

<style scoped>
.vertical-center {
  display: block;
  position: relative;
  top: 50%;
  transform: translateY(-50%);
}
</style>
