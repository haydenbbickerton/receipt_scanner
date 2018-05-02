'use strict'
const pkg = require('../package')

module.exports = {
  title: 'client',
  // Options for webpack-dev-server
  // See https://webpack.js.org/configuration/dev-server
  devServer: {
    host: 'localhost',
    port: 4000,
    hot: true,
    watchOptions: {
      poll: true,
    }
  },
  // when you use electron please set to relative path like ./
  // otherwise only set to absolute path when you're using history mode
  publicPath: '/',
  webService: {
    host: 'localhost',
    port: '8000',
    baseUrl: 'http://localhost:8000',
    clientId: 2,
    clientSecret: '2Iz3YOKdaplQVMfHM2QrBRf7lHOpZFH1MVZCcSBc'
  }
}
