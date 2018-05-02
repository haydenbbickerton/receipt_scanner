<template>
  <div class="app-wrapper">
    <div class="main-container">
      <el-header><navbar></navbar></el-header>
      <el-main><app-main></app-main></el-main>
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
  created () {
    // Do a blank request to trigger login if they aren't
    this.$store.dispatch('user/request')

    // Fetch receipts on startup
    this.$store.dispatch('getReceipts')
  }
}
</script>

<style scoped>
.app-wrapper {
  position: relative;
  height: 100%;
  width: 100%;
}

.el-header {
  background-color: white;
}
</style>
