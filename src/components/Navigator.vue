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
        <v-spacer/>
        <v-flex shrink>
          <v-layout column justify-center fill-height class="white--text">
            <template v-if="!authenticated">
            <v-flex shrink class="subheading text-uppercase text-xs-center pointer" @click="signinDialog = true">Connexion</v-flex>
            <v-flex shrink class="subheading text-uppercase text-xs-center pointer" @click="signupDialog = true">Inscription</v-flex>
            </template>
            <template v-else>
              <v-flex shrink class="subheading text-uppercase text-xs-center pointer" @click="auth.logout()">Déconnexion</v-flex>
            </template>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-dialog
        v-model="signinDialog"
        width="500"
    >
      <connexion @close="signinDialog = false"/>
    </v-dialog>
    <v-dialog
        v-model="signupDialog"
        width="500"
    >
      <inscription @close="signupDialog = false"/>
    </v-dialog>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator'
import Connexion from '@/components/Connexion.vue'
import Inscription from '@/components/Inscription.vue'
import { IAuth, UsernamePasswordCredentials } from '@/jwt-toolbox/auth'
import { decode } from 'jsonwebtoken'
import { GetterAuth, Payload, StateAuth, MutationAuth } from '@/store/auth'


@Component({
  components: {
    Connexion,
    Inscription
  }
})
export default class Navigator extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>
  @GetterAuth
  authenticated!: boolean
  @MutationAuth
  setPayload!: (payload: Payload) => void

  logo: string = require('@/assets/logo.png')
  logoNespresso: string = require('@/assets/nespresso-logo.png')
  signinDialog: boolean = false
  signupDialog: boolean = false

  mounted () {
    this.auth.addListeners({tokensChanged: (tokens) => {
      if (tokens && tokens.accessToken) {
        this.setPayload(decode(tokens.accessToken) as Payload)
      }
    }})
  }
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

.pointer {
  cursor: pointer;
}
</style>
