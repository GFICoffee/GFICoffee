import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.min.css'
import fr from 'vuetify/src/locale/fr'
import './hooks' // This must be imported before any component
import './styles/fonts.css'
import './styles/global.css'
import services from './services'
import store from './store'

import App from './App.vue'
import router from './router'

Vue.config.productionTip = false
Vue.use(Vuetify, {
  theme: {
    primary: '#ff9c34',
    secondary: { base: '#000000', lighten1: '#111111', lighten2: '#262626', lighten3: '#707070' }
  },
  lang: {
    locales: { fr },
    current: 'fr'
  }
})

Vue.prototype.$utils = {
  console: console
}

// tslint:disable-next-line:no-unused-expression
new Vue({
  router,
  store,
  provide: { ...services },
  render: (h) => h(App)
}).$mount('#app')