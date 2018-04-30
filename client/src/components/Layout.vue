<template>
  <div class="app-wrapper">
    <div class="main-container">
      <navbar></navbar>
      <app-main></app-main>
    </div>
  </div>
</template>

<script>
import 'normalize.css/normalize.css'
import { Navbar, AppMain } from '@/components/layout'

export default {
  name: 'layout',
  components: {
    Navbar,
    AppMain
  },
  mounted () {
    // now we have a token, grab the users data
    this.$store.dispatch('user/request')
  }
}
</script>

<style scoped>
/*// @import "src/styles/mixin.scss"; */
.app-wrapper {
  /*@include clearfix;*/
  position: relative;
  height: 100%;
  width: 100%;
}
</style>
