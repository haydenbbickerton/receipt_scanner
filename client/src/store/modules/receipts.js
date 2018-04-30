import config from 'config'
import _ from 'lodash';
import http from '@/utils/http'
import Vapi from "vuex-rest-api"

// Step 2
const receipts = new Vapi({
  axios: http,
    state: {
      receipt: null,
      rawReceipts: []
    }
  })
  // Step 3
  .get({
    action: "getReceipt",
    property: "receipt",
    path: ({ id }) => `/receipts/${id}`
  })
  .get({
    action: "getReceipts",
    property: "rawReceipts",
    path: "/receipts",
    onSuccess: (state, payload) => {
      state.rawReceipts = payload.data.data
    }
  })
  .post({
    action: "updateReceipt",
    property: "receipt",
    path: ({ id }) => `/receipts/${id}`
  })
  .post({
    action: "uploadReceipt",
    property: "receipt",
    path: "/receipts",
    requestConfig: {
      'Content-Type': 'multipart/form-data'
    }
  })
  .delete({
    action: "deleteReceipt",
    property: "receipt",
    path: ({ id }) => `/receipts/${id}`
  })
  // Step 4
  .getStore()


receipts.getters = {
  receipts: (state, getters) => {
    // Between my api, tagguns api, and pagination, there's too many nested "data"s.
    // Clean that up a bit to make things easier.
    return _.forEach(state.rawReceipts, (receipt) =>{
      let data = _.mapValues(receipt.data, 'data')
      _.unset(receipt, 'data')
      _.assignIn(receipt, data)
      return receipt
    })
  }
}

export default receipts
