<template>
  <div class="app-container">
    <el-card class="box-card">
      <div slot="header" class="clearfix">
        <span>Receipts</span>
      </div>

      <data-tables :data="receipts" :actions-def="actionsDef" :action-col-def="actionColDef">
        <el-table-column v-for="title in titles" :prop="title.prop" :key="title.prop" :label="title.label" sortable="custom">
        </el-table-column>
      </data-tables>
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
import UploadReceiptForm from '@/components/UploadReceiptForm'
import { mapGetters, mapActions } from 'vuex'
import config from 'config'

export default {
    components: {
        UploadReceiptForm
    },
    mounted() {
        this.getReceipts()
    },
    data() {
        return {
            yea: [],
            activeReceiptId: null,
            baseUrl: config.webService.baseUrl,
            dialogContent: null,
            dialogIsVisible: false,
            titles: [
                {
                    prop:  'name',
                    label: 'Name'
                },
                {
                    prop:  'merchantName',
                    label: 'Merchant'
                },
                {
                    prop:  'category',
                    label: 'Category'
                },
                {
                    prop:  'totalAmount',
                    label: 'Amount'
                },
                {
                    prop:  'date',
                    label: 'Date'
                },
            ],
            actionsDef: {
                def: [{
                  name: 'upload',
                  handler: () => {
                    this.dialogContent = 'upload_form'
                  },
                  icon: 'upload'
                }]
            },
            actionColDef: {
                label: 'Actions',
                def: [{
                    handler: row => {
                        console.log(row)
                        this.activeReceiptId = row.id
                        this.dialogContent = 'receipt'
                    },
                    name: 'View'
                },
                {
                    handler: row => {
                        // this is janky. And belongs somewhere else.
                        this.$confirm('This will permanently delete the file. Continue?', 'Warning', {
                          confirmButtonText: 'OK',
                          cancelButtonText: 'Cancel',
                          type: 'warning'
                        }).then(() => {
                            const params = { id: row.id }
                            this.deleteReceipt({ params }).then((resp) => {
                                this.$message({
                                    type: 'success',
                                    message: 'Delete completed'
                                })
                            })
                            this.getReceipts()
                        })
                  },
                  name: 'Delete'
                }
                ]
            }
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