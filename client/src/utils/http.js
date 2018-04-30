import axios from 'axios'
import { Message } from 'element-ui'
import store from '@/store'
import router from '@/router'
import config from 'config'

// create an axios instance
const http = axios.create({
  baseURL: config.webService.baseUrl,
  timeout: 10000
})

// request interceptor
http.interceptors.request.use(config => {
  if (store.getters['auth/isAuthenticated']) {
    // Add the auth token to every request
    config.headers.Authorization = `Bearer ${store.getters['auth/token']}`
  }
  return config
}, error => {
  console.log(error)
  Promise.reject(error)
})

// response interceptor
http.interceptors.response.use(
  response => response,
  error => {
    let message = error.message

    if (error.response.status == 401) {
      console.log('Auth token invalid')
      message = 'Your session has expired, please log back in.'
      router.push({name: 'Login'})
    }

    console.log('err' + error)
    // UI popup with the error message
    Message({
      message: message,
      type: 'error',
      duration: 5 * 1000
    })

    return Promise.reject(error)
  })

export default http
