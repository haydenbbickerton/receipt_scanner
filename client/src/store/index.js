import Vue from 'vue'
import Vuex from 'vuex'

import auth from './modules/auth'
import user from './modules/user'
import socket from './modules/socket'
import receipts from './modules/receipts'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    user,
    socket,
    receipts
  }
})

export default store

