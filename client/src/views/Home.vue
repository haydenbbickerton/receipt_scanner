<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Receipts</span>
        <el-button type="primary" class="fr" @click="uploadDialogVisible = true">Upload</el-button>
      </div>
      <receipts-table></receipts-table>
    </el-card>

    <el-dialog
      title="Upload Receipt"
      :visible.sync="uploadDialogVisible"
      class="receipt-dialog">
            <upload-receipt-form v-on:receipt-uploaded="afterUpload"></upload-receipt-form>
    </el-dialog>
  </div>
</template>

<script>
import ReceiptsTable from '@/components/ReceiptsTable'
import UploadReceiptForm from '@/components/UploadReceiptForm'

export default {
    components: {
        ReceiptsTable,
        UploadReceiptForm
    },
    data() {
        return {
            uploadDialogVisible: false,
        }
    },
    methods: {
        afterUpload () {

        }
    }
}
</script>

<style>
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