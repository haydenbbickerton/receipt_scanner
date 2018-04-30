import Vapi from 'vuex-rest-api'
import http from '@/utils/http'


const user = {
  namespaced: true,
  state: {
    id: '',
    username: '',
    email: ''
  },

  getters: {

  },

  mutations: {
    setUser: (state, user) => {
      state.id = user.id
      state.email = user.email
      state.username = user.username
    },
  },

  actions: {
    request: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        http.get('/users/me').then(resp => {
            commit('setUser', resp.data.data)
            resolve(resp)
        })
        .catch(err => {
          reject(err)
        })
      })
    }
  }
}

export default user
