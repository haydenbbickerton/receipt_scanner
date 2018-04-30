import Vue from 'vue'
import Vuex from 'vuex'

import auth from './modules/auth'
import user from './modules/user'
import receipts from './modules/receipts'

Vue.use(Vuex)

const store = new Vuex.Store({
  modules: {
    auth,
    user,
    receipts
  }
})

export default store

