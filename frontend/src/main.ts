import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import './styles/fonts.css'
import './styles/global.css'
import services from './services'
import './registerServiceWorker'
import vuetify from './plugins/vuetify'

Vue.config.productionTip = false

if (URLSearchParams) {
  const urlParams = new URLSearchParams(window.location.search)
  const token = urlParams.get('token')

  if (token) {
    if (history.pushState) {
      let newurl = window.location.protocol + '//' + window.location.host + window.location.pathname
      window.history.pushState({ path: newurl }, '', newurl)
    }
    services.auth.setTokens({ access: { value: token } })
  }
}

(Vue as any).prototype.$utils = {
  console: console
}

// tslint:disable-next-line:no-unused-expression
new Vue({
  vuetify,
  router,
  store,
  provide: { ...services },
  render: (h) => h(App)
}).$mount('#app')
