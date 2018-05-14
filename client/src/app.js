import Vue from 'vue'
import { sync } from 'vuex-router-sync'
import App from './components/App'
import router from './router'
import store from './store'
import './plugins'

sync(store, router)

const app = new Vue({
  router,
  store,
  ...App
})

global.$vm = app

export { app, router, store }
