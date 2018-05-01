<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Receipts</span>
      </div>
      <receipts-table :receipts="receipts"></receipts-table>
    </el-card>

    <!-- We'll use the dialog to switch out our content on this page -->
    <el-dialog
      :visible.sync="dialogIsVisible"
      v-on:close="dialogContent = null"
      class="receipt-dialog" center>
        <div v-if="dialogContent === 'receipt'">
            <span slot="title">{{ activeReceipt.name }}</span>
            <img class="img-responsive" v-bind:src="baseUrl + '/' + activeReceipt.mediaUrl" />
        </div>
        <div v-else-if="dialogContent === 'upload_form'">
            <upload-receipt-form v-on:receipt-uploaded="afterUpload"></upload-receipt-form>
        </div>
    </el-dialog>
  </div>
</template>

<script>
import ReceiptsTable from '@/components/ReceiptsTable'
import UploadReceiptForm from '@/components/UploadReceiptForm'
import { mapGetters, mapActions } from 'vuex'
import config from 'config'

export default {
    components: {
        ReceiptsTable,
        UploadReceiptForm
    },
    mounted() {
        this.getReceipts()
    },
    data() {
        return {
            activeReceiptId: null,
            baseUrl: config.webService.baseUrl,
            dialogContent: null,
            dialogIsVisible: false,
        }
    },
    computed: {
        activeReceipt() {
            if (!!this.receipts) {
                return this.receipts.find(x => x.id === this.activeReceiptId)
            }
        },
        ...mapGetters(['receipts'])
    },
    watch: {
        dialogContent: function (val) {
            // When we set something like 'receipt' or 'upload_form', make the dialog appear
            this.dialogIsVisible = (val !== null)
        },
    },
    methods: {
        afterUpload() {
            this.dialogContent = null
            this.getReceipts()
        },
        ...mapActions([
            'getReceipts',
            'deleteReceipt'
        ])
    }
}
</script>

<style lang="postcss">
.img-responsive {
    max-width: 100%;
    display:block;
    height: auto;
}

.receipt-dialog > .el-dialog {
    width: 50%;
}

@media only screen and (min-width: 601px) and (max-width: 1200px) {
    .receipt-dialog > .el-dialog {
        width: 75%;
    }
}

@media only screen and (max-width: 600px) {
    .receipt-dialog > .el-dialog {
        width: 95%;
    }
}
</style>