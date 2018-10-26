import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.min.css'
import './hooks' // This must be imported before any component
import './styles/global.css'

import App from './App.vue'
import router from './router'

Vue.config.productionTip = false
Vue.use(Vuetify, {
  theme: {
    primary: '#ff9c34',
    secondary: { base: '#000000', lighten1: '#111111' }
  }
})

// tslint:disable-next-line:no-unused-expression
new Vue({
  router,
  el: '#app',
  components: { App },
  template: '<App/>'
})
