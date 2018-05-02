import Vue from 'vue'

import ElementUI from 'element-ui'
import locale from 'element-ui/lib/locale/lang/en'
import 'element-ui/lib/theme-chalk/index.css'
import '@/styles/index.css'
import 'normalize.css'

import money from 'v-money'


Vue.use(ElementUI, { locale })

Vue.use(money, {prefix: '$ '})
