<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Receipts</span>
        <span style="float: right;">
            <el-button @click="exportTable">Export</el-button>
            <el-button type="primary" @click="uploadDialogVisible = true">Upload</el-button>
        </span>
      </div>
      <receipts-table :receipts="receipts" v-on:table-data-modified="currentTableData = $event"></receipts-table>
    </el-card>

    <el-dialog
      title="Upload Receipt"
      :visible.sync="uploadDialogVisible"
      class="receipt-dialog">
            <upload-receipt-form
            v-on:receipt-uploaded="getReceipts"
            v-on:receipt-form-finished="uploadDialogVisible = false" v-if="uploadDialogVisible"></upload-receipt-form>
    </el-dialog>
  </div>
</template>

<script>
import { mapActions, mapGetters } from 'vuex'
import CsvExport from '@/utils/csv_export'
import ReceiptsTable from '@/components/ReceiptsTable'
import UploadReceiptForm from '@/components/UploadReceiptForm'

export default {
    components: {
        ReceiptsTable,
        UploadReceiptForm
    },
    data() {
        return {
            currentTableData: {},
            uploadDialogVisible: false,
        }
    },
    computed: {
        ...mapGetters(['receipts'])
    },
    methods: {
        exportTable() {
            this.$prompt('Enter a filename for this export', 'Filename').then(value => {
                // TODO: make sure there is data in the table first
                const columns = Object.keys(this.currentTableData[0])
                CsvExport(this.currentTableData, columns, value.value)
            })
        },
        ...mapActions([
          "getReceipts"
        ])
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