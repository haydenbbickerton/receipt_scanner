import Vue from 'vue'
import Router from 'vue-router'
import store from '@/store'
import Layout from '@/components/Layout'

Vue.use(Router)

export const constantRouterMap = [
  {
    path: '/login',
    name: 'Login',
    component: () => import('@/views/Login'),
    hidden: true
  },
  {
    path: '/',
    component: Layout,
    redirect: '/home',
    name: 'Layout',
    hidden: true,
    children: [
    {
        path: '/home',
        name: 'Home',
        component: () => import('@/views/Home'),
    }
    ]
  },
  { path: '*', redirect: '/home', hidden: true }
]

const router = new Router({
  scrollBehavior: () => ({ y: 0 }),
  routes: constantRouterMap
})

router.beforeEach((to, from, next) => {
  if (to.name == 'Login') {
    store.dispatch('auth/logout')
    next()
  }

  if (!store.getters['auth/isAuthenticated'] && to.name != 'Login') {
    next({ path: 'Login' })
  } else {
    next()
  }
})

export default router
