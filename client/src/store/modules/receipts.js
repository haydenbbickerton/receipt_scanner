import config from 'config'
import _ from 'lodash';
import http from '@/utils/http'
import Vapi from "vuex-rest-api"

// Step 2
const receipts = new Vapi({
  axios: http,
    state: {
      receipt: null,
      receipts: []
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
      state.receipts = payload.data.data
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
  receipts: state => state.receipts
}

export default receipts
