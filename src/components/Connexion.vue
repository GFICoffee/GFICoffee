<template>
  <v-card color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text pl-0">Connexion</v-card-title>
    <v-flex>
      <v-form ref="form" v-model="valid">
        <v-layout column>
          <v-flex shrink>
            <v-text-field
                v-model="credentials.email"
                label="E-mail"
                :rules="emailRules"
                :required="true"
                color="white"
                dark
            />
          </v-flex>
          <v-flex shrink>
            <v-text-field
                v-model="credentials.password"
                label="Mot de passe"
                :rules="passwordRules"
                :required="true"
                color="white"
                dark
            />
          </v-flex>
        </v-layout>
      </v-form>
    </v-flex>
    <v-card-actions>
      <v-spacer/>
      <v-btn outline
             dark
             :disabled="!valid"
             @click="submit"
      >
        Connexion
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator'
import { IAuth, UsernamePasswordCredentials } from '../jwt-toolbox/auth'

@Component
export default class Connexion extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>

  valid: boolean = false
  credentials: { email: string, password: string } = { email: '', password: '' }
  emailRules: any[] = [
    (v?: string) => !!v || 'Champs requis'
  ]

  passwordRules: any[] = [
    (v?: string) => !!v || 'Champs requis'
  ]

  submit () {
    if ((this as any).$refs.form.validate()) {
      this.auth.login({ username: this.credentials.email, password: this.credentials.password }, true)
    }
  }
}
</script>

