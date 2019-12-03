<template>
  <v-row no-gutters justify="center" class="navigator py-3">
    <v-col cols="auto" sm="8">
      <v-row no-gutters class="logos">
        <v-col cols="auto">
          <img :src="logo" alt="Logo GFI" height="60">
        </v-col>
        <v-col cols="auto" class="mx-2">
          <v-icon color="white" class="x">mdi-close</v-icon>
        </v-col>
        <v-col cols="auto">
          <img :src="logoNespresso" alt="Logo Nespresso" height="60">
        </v-col>
        <v-spacer/>
        <v-col cols="auto">
          <v-row no-gutters justify="center" align="center" class="white--text fill-height">
            <template v-if="!authenticated">
              <v-col cols="12" class="subheading text-uppercase text-xs-center pointer" @click="login()">Connexion
              </v-col>
            </template>
            <template v-else>
              <v-col cols="12" class="subheading text-uppercase text-xs-center pointer">
                <v-menu offset-y transition="slide-y-transition">
                  <template v-slot:activator="{ on }">
                    <div v-on="on">{{ username }}</div>
                  </template>
                  <v-list>
                    <v-list-item class="subheading text-uppercase text-xs-center" @click="accountDialog = true">
                      <v-list-item-content>
                        <v-list-item-title>Mon compte</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                    <v-list-item class="subheading text-uppercase text-xs-center" @click="auth.logout()">
                      <v-list-item-content>
                        <v-list-item-title>Déconnexion</v-list-item-title>
                      </v-list-item-content>
                    </v-list-item>
                  </v-list>
                </v-menu>
              </v-col>
            </template>
          </v-row>
        </v-col>
      </v-row>
    </v-col>
    <v-dialog
      v-model="accountDialog"
      width="700"
      :fullscreen="$vuetify.breakpoint.smAndDown"
    >
      <account @close="accountDialog = false" :dialog-status="accountDialog"/>
    </v-dialog>
  </v-row>
</template>
<script lang="ts">
import { Vue, Component, Inject } from 'vue-property-decorator'
import Account from '@/components/Account.vue'
import { IAuth, UsernamePasswordCredentials } from 'auth-toolbox'
import { GetterAuth, Payload, StateAuth, MutationAuth } from '@/store/auth'
import { environment } from '../environments/environment'

@Component({
  components: {
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
  accountDialog: boolean = false

  get username (): string {
    if (this.payload.username) {
      return this.payload.username.split('@')[0].split('.').join(' ')
    }
    return ''
  }

  mounted () {
    this.auth.addListener({
      tokensChanged: (tokens) => {
        this.setPayload(this.auth.decodeAccessToken() as Payload)
      }
    })
  }

  login () {
    window.location.href = `${environment.apiBaseUrl}/login`
  }
}
</script>

<style scoped>
.navigator {
  background: -moz-linear-gradient(left, rgba(0, 0, 0, 0) 0%, rgb(17, 17, 17) 100%); /* FF3.6-15 */
  background: -webkit-linear-gradient(left, rgba(0, 0, 0, 0) 0%, rgb(17, 17, 17) 100%); /* Chrome10-25,Safari5.1-6 */
  background: linear-gradient(to right, rgba(0, 0, 0, 0) 0%, rgb(17, 17, 17) 100%); /* W3C, IE10+, FF16+, Chrome26+, Opera12+, Safari7+ */
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
