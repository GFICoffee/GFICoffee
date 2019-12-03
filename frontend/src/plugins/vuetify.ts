import Vue from 'vue'

import '@mdi/font/css/materialdesignicons.css'

import Vuetify from 'vuetify/lib'
import fr from 'vuetify/src/locale/fr'
import { VuetifyPreset } from 'vuetify/types/presets'

Vue.use(Vuetify)

const opts: VuetifyPreset = {
  icons: {
    iconfont: 'mdi'
  },
  lang: {
    locales: { fr },
    current: 'fr'
  },
  theme: {
    dark: true,
    themes: {
      light: {
        primary: '#ff9c34',
        secondary: { base: '#000000', lighten1: '#111111', lighten2: '#262626', lighten3: '#525252', lighten4: '#707070' }
      },
      dark: {
        primary: '#ff9c34',
        secondary: { base: '#000000', lighten1: '#111111', lighten2: '#262626', lighten3: '#525252', lighten4: '#707070' }
      }
    }
  }
}

export default new Vuetify(opts)
