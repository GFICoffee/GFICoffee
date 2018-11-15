import Vue from 'vue'
import App from './App.vue'
import router from './router'
import store from './store'
import Vuetify from 'vuetify'
import 'vuetify/dist/vuetify.min.css' // Ensure you are using css-loader
import 'material-design-icons-iconfont/dist/material-design-icons.css'
import '@mdi/font/css/materialdesignicons.min.css'
import fr from 'vuetify/src/locale/fr'
import './styles/fonts.css'
import './styles/global.css'
import services from './services'
import './registerServiceWorker'

Vue.config.productionTip = false;


const urlParams = new URLSearchParams(window.location.search)
const token = urlParams.get('token')

if (token) {
  if (history.pushState) {
    let newurl = window.location.protocol + "//" + window.location.host + window.location.pathname;
    window.history.pushState({ path: newurl }, '', newurl);
  }
  services.auth.setTokens({ access: { value: token } })
}

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
});

(Vue as any).prototype.$utils = {
  console: console
}

// tslint:disable-next-line:no-unused-expression
new Vue({
  router,
  store,
  provide: { ...services },
  render: (h) => h(App)
}).$mount('#app')