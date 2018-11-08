<template>
  <v-card color="secondary lighten-1 pb-2 px-3">
    <v-card-title class="headline white--text">S'inscrire</v-card-title>
    <v-flex>
      <v-form ref="form" v-model="valid">
        <v-layout column>
          <v-flex shrink v-if="emailAlreadyUsed">
            <h3 class="body-2 error--text">Un compte existe déjà avec cette adresse e-mail</h3>
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
          <v-flex shrink>
            <v-text-field
                v-model="passwordConfirmation"
                label="Confirmation mot de passe"
                :rules="confirmationPasswordRules"
                :required="true"
                :append-icon="showPasswordConfirm ? 'visibility_off' : 'visibility'"
                :type="showPasswordConfirm ? 'text' : 'password'"
                color="white"
                dark
                @click:append="showPasswordConfirm = !showPasswordConfirm"
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
        Valider
      </v-btn>
    </v-card-actions>
  </v-card>
</template>
<script lang="ts">
import { Component, Inject, Vue } from 'vue-property-decorator'
import UserWithPasswordModel from '../api/model/UserWithPasswordModel'
import UserResource from '@/api/User'

@Component
export default class Inscription extends Vue {
  @Inject()
  userResource!: UserResource

  credentials: UserWithPasswordModel = { email: '', password: '' }
  passwordConfirmation: string = ''
  valid: boolean = false

  emailAlreadyUsed: boolean = false
  showPassword: boolean = false
  showPasswordConfirm: boolean = false
  loading: boolean = false

  emailRules: any[] = [
    (v: string) => !!v || 'Champs requis',
    (v: string) => /.+@gfi\.fr/.test(v) || 'Vous devez utiliser une adresse GFI'
  ]

  passwordRules: any[] = [
    (v: string) => !!v || 'Champs requis',
    (v: string) => v.length >= 6 || 'Le mot de passe doit contenir au moins 6 caractères'
  ]

  get confirmationPasswordRules (): any[] {
    return [
      (v?: string) => v === this.credentials.password || 'Le champ doit être identique au mot de passe',
    ]
  }

  async submit () {
    if ((this as any).$refs.form.validate()) {
      this.loading = true
      try {
        await this.userResource.register(this.credentials)
        this.$emit('close')
      } catch (e) {
        this.emailAlreadyUsed = true
      } finally {
        this.loading = false
      }
    }
  }
}
</script>

