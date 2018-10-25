import Vue from 'vue'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css'

import './hooks' // This must be imported before any component

import App from './App.vue'
import router from './router'

Vue.config.productionTip = false
Vue.use(Vuetify)

// tslint:disable-next-line:no-unused-expression
new Vue({
  router,
  el: '#app',
  components: { App },
  template: '<App/>'
})
