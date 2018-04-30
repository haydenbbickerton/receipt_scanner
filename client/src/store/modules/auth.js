import config from 'config'
import http from '@/utils/http'

const auth = {
  namespaced: true,
  state: {
    token: localStorage.getItem('user-token') || '',
  },

  getters: {
    isAuthenticated: state => !!state.token,
    token: state => state.token
  },

  mutations: {
    setToken: (state, token) => {
      state.token = token
    },
  },

  actions: {
    login: ({commit, dispatch}, userInfo) => {
      return new Promise((resolve, reject) => {

        const data = {
          grant_type: 'password',
          client_id: config.webService.clientId,
          client_secret: config.webService.clientSecret,
          username: userInfo.username.trim(),
          password: userInfo.password
        }

        http.post('/auth', data).then(resp => {
            const token = resp.data.access_token
            localStorage.setItem('user-token', token) // store the token in localstorage
            commit('setToken', token)
            resolve(resp)
        })
        .catch(err => {
          localStorage.removeItem('user-token') // if the request fails, remove any possible user token if possible
          reject(err)
        })
      })
    },

    logout: ({commit, dispatch}) => {
      return new Promise((resolve, reject) => {
        localStorage.removeItem('user-token') // clear the token from localstorage
        resolve()
      })
    }
  }
}

export default auth
