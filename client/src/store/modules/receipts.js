import config from 'config'
import http from '@/utils/http'
import Vapi from "vuex-rest-api"

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
      payload.data.data.map(receipt => $vm.$store.commit('SET_RECEIPT', receipt))
    },
  })
  .put({
    action: "updateReceipt",
    property: "receipt",
    path: ({ id }) => `/receipts/${id}`,
    onSuccess: (state, payload) => {
      $vm.$store.commit('SET_RECEIPT', payload.data.data)
    }
  })
  .post({
    action: "uploadReceipt",
    property: "receipt",
    path: "/receipts",
    requestConfig: {
      'Content-Type': 'multipart/form-data'
    },
    onSuccess: (state, payload) => {
      $vm.$store.commit('SET_RECEIPT', payload.data.data)
    }
  })
  .delete({
    action: "deleteReceipt",
    property: "receipt",
    path: ({ id }) => `/receipts/${id}`,
    onSuccess: (state, payload) => {
      // Remove from our receipts array by id
      const deleted_id = payload.request.responseURL.split('/').pop()
      $vm.$store.commit('REMOVE_RECEIPT', deleted_id)
    }
  })
  // Step 4
  .getStore()

receipts.getters = {
  receipts: state => state.receipts
}

receipts.mutations.SET_RECEIPT = (state, receipt) => {
  // We'll shorthand assign to the old receipt to maintain reactivity
  const cur_receipt = state.receipts[receipt.id] || {}
  const new_receipt = { ...cur_receipt, ...receipt}

  new_receipt.mediaUrl = `${config.webService.baseUrl}/${receipt.mediaUrl}`
  $vm.$set(state.receipts, receipt.id, new_receipt)
}

receipts.mutations.REMOVE_RECEIPT = (state, id) => {
  $vm.$delete(state.receipts, id)
}

export default receipts
