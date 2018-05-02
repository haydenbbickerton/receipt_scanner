import config from 'config'
import _ from 'lodash';
import http from '@/utils/http'
import Vapi from "vuex-rest-api"

// Step 2
const receipts = new Vapi({
  axios: http,
    state: {
      receipt: null,
      receipts: [],
      categories: [ // TODO: this should come from the service, not re-defined here
        'airfare',
        'vehicle rental',
        'fuel',
        'lodging',
        'meals',
        'phone',
        'entertainment',
        'weapons',
        'other'
      ]
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
    property: "receipts",
    path: "/receipts",
    onSuccess: (state, payload) => {
      // modify the receipts before putting them in our store
      const data = payload.data.data
      state.receipts = data.map(receipt => ({...receipt,
        mediaUrl: `${config.webService.baseUrl}/${receipt.mediaUrl}`
      }))
    }
  })
  .put({
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
