<template>
  <v-card color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text pl-0">Se connecter</v-card-title>
    <v-flex>
      <v-form ref="form" v-model="valid">
        <v-layout column>
          <v-flex shrink v-if="invalidCredentials">
            <h3 class="body-2 error--text">Identifiants invalides</h3>
          </v-flex>
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
                :append-icon="showPassword ? 'visibility_off' : 'visibility'"
                :type="showPassword ? 'text' : 'password'"
                color="white"
                dark
                @click:append="showPassword = !showPassword"
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
             :loading="loading"
             @click="submit"
      >
        Connexion
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator'
import { IAuth, UsernamePasswordCredentials } from '@/jwt-toolbox/auth'

@Component
export default class Connexion extends Vue {
  @Inject()
  auth!: IAuth<UsernamePasswordCredentials, any>

  invalidCredentials: boolean = false
  valid: boolean = false
  showPassword: boolean = false
  loading: boolean = false
  credentials: { email: string, password: string } = { email: '', password: '' }
  emailRules: any[] = [
    (v?: string) => !!v || 'Champs requis'
  ]

  passwordRules: any[] = [
    (v?: string) => !!v || 'Champs requis'
  ]

  async submit () {
    if ((this as any).$refs.form.validate()) {
      this.loading = true
      try {
        await this.auth.login({ username: this.credentials.email, password: this.credentials.password }, true)
        this.$emit('close')
      } catch (e) {
        this.invalidCredentials = true
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

