<template>
<el-row type="flex" :gutter="20" class="receipt" v-loading="pending">
    <el-col :xs="24" :md="10">
        <div v-if="editing">
            <el-button type="success" @click="saveEdit">Save</el-button>
            <el-button @click="editing = false">Cancel</el-button>
        </div>
        <div v-else>
            <el-button type="primary" plain @click="editing = true">Edit</el-button>
            <el-button type="danger" @click="confirmDeletion">Delete</el-button>
        </div>
        <hr>
        <table class="details-table">
            <tbody>
                <tr>
                    <th>Name</th>
                    <td><el-input v-model="localReceipt.name" :disabled="!editing"></el-input>
                    </td>
                </tr>
                <tr>
                    <th>Date</th>
                    <td>
                        <el-date-picker
                          v-model="localReceipt.date"
                          type="date"
                          prefix-icon="null"
                          :disabled="!editing">
                        </el-date-picker>
                    </td>
                </tr>
                <tr>
                    <th>Uploaded On</th>
                    <td>{{moment(localReceipt.createdAt).format('MMMM Do YYYY')}}</td>
                </tr>
                <tr>
                    <th>Amount</th>
                    <td><el-input v-model="localReceipt.totalAmount"  :disabled="!editing"></el-input>
                </td>
                </tr>
                <tr>
                    <th>Merchant</th>
                    <td><el-input v-model="localReceipt.merchantName" :disabled="!editing"></el-input></td>
                </tr>
                <tr>
                    <th>Category</th>
                    <td style="text-transform: capitalize;">{{receipt.category}}</td>
                </tr>
                <tr>
                    <th>Notes</th>
                    <td><el-input type="textarea" v-model="localReceipt.notes" :disabled="!editing"></el-input></td>
                </tr>
            </tbody>
        </table>
    </el-col>
    <el-col :xs="24" :md="14">
        <img class="img-responsive" v-bind:src="receipt.mediaUrl" />
    </el-col>
</el-row>
</template>

<script>
import _ from 'lodash'
import moment from 'moment'
import { mapState, mapActions } from 'vuex'

export default {
  name: 'receipt',
  props: ['receipt'],
  data() {
    return {
        localReceipt: Object.assign({}, this.receipt),
        editing: false,
    }
  },
  computed: {
    ...mapState({
          pending: state => state.receipts.pending.receipt,
    })
  },
  methods: {
    moment,
    confirmDeletion() {
        this.$confirm('This will permanently delete this Receipt. Continue?', 'Warning', {
          confirmButtonText: 'OK',
          cancelButtonText: 'Cancel',
          type: 'warning'
        }).then(() => {
            const params = { id: this.localReceipt.id }
            this.deleteReceipt({params}).then((resp) => {
                this.$message({
                    type: 'success',
                    message: 'Receipt Deleted'
                })
                this.$emit('receipt-changed')
            })
        }).catch((err) => {
            console.error(err)
        })
    },
    saveEdit() {
        const data = _.pick(this.localReceipt, ['name', 'notes', 'totalAmount', 'merchantName', 'date']) // Only allowed to change these fields
        const params = { id: this.localReceipt.id }
        this.updateReceipt({ data, params }).then((resp) => {
            this.$message({
                type: 'success',
                message: 'Receipt Updated'
            })
            this.$emit('receipt-changed')
        })
    },
    ...mapActions([
        "updateReceipt",
        "deleteReceipt"
    ])
  }
}
</script>

<style >
.receipt.el-row {
    flex-wrap: wrap;
}

.img-responsive {
    max-width: 100%;
    display:block;
    height: auto;
}

.details-table {
    th, td {
        width: 50%;
    }
}
.el-input, .el-textarea {

    &.is-disabled {

        .el-input__inner, .el-textarea__inner {
            /*  Whenever the user isn't editing, undo the CSS rules to make it look like it isn't an input
                Janky, but works for now. TODO
            */
            padding: 0;
            background-color: white;
            border: 0;
            color: #606266;
        }
    }
}
</style>