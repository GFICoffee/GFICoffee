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
              <v-flex shrink class="subheading text-uppercase text-xs-center pointer">
                <v-menu offset-y transition="slide-y-transition">
                  <div slot="activator">{{ username }}</div>
                  <v-list>
                    <v-list-tile class="subheading text-uppercase text-xs-center" @click="accountDialog = true">
                      Mon compte
                    </v-list-tile>
                    <v-list-tile class="subheading text-uppercase text-xs-center" @click="auth.logout()">
                      Déconnexion
                    </v-list-tile>
                  </v-list>
                </v-menu>
              </v-flex>
            </template>
          </v-layout>
        </v-flex>
      </v-layout>
    </v-flex>
    <v-dialog
        v-model="signinDialog"
        width="500"
        :fullscreen="$vuetify.breakpoint.smAndDown"
    >
      <connexion @close="signinDialog = false"/>
    </v-dialog>
    <v-dialog
        v-model="signupDialog"
        width="500"
        :fullscreen="$vuetify.breakpoint.smAndDown"
    >
      <inscription @close="signupDialog = false"/>
    </v-dialog>

    <v-dialog
        v-model="accountDialog"
        width="500"
        :fullscreen="$vuetify.breakpoint.smAndDown"
    >
      <account @close="accountDialog = false"/>
    </v-dialog>
  </v-layout>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator'
import Connexion from '@/components/Connexion.vue'
import Inscription from '@/components/Inscription.vue'
import Account from '@/components/Account.vue'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox/dist/lib'
import { GetterAuth, Payload, StateAuth, MutationAuth } from '@/store/auth'


@Component({
  components: {
    Connexion,
    Inscription,
    Account
  }
})
export default class Navigator extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>
  @GetterAuth
  authenticated!: boolean
  @GetterAuth
  payload!: Payload
  @MutationAuth
  setPayload!: (payload: Payload) => void

  logo: string = require('@/assets/logo.png')
  logoNespresso: string = require('@/assets/nespresso-logo.png')
  signinDialog: boolean = false
  signupDialog: boolean = false
  accountDialog: boolean = false

  get username (): string {
    if (this.payload.username) {
      return this.payload.username.split('@')[0].split('.').join(' ')
    }
    return ''
  }

  mounted () {
    this.auth.addListener({tokensChanged: (tokens) => {
      this.setPayload(this.auth.decodeAccessToken() as Payload)
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
