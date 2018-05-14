<template>
    <el-table
        :data="receiptList"
        ref="receiptsTable"
        stripe
        show-summary
        sum-text="Total"
        v-loading="pending"
        class="receipts-table"
        v-on:expand-change="setActiveReceiptId"
        v-on:filter-change="tableDataModified"
        v-on:sort-change="tableDataModified"
        >
        <el-table-column
            width="50"
            type="expand">
                <template slot-scope="props">
                    <receipt v-bind:receipt="props.row"></receipt>
                </template>
        </el-table-column>
        <el-table-column
            prop="name"
            label="Name"
            sortable>
        </el-table-column>
        <el-table-column
            prop="merchantName"
            label="Merchant"
            :filters="merchants"
            :filter-method="filterHandler"
            filter-placement="bottom-end"
            sortable>
        </el-table-column>
        <el-table-column
            prop="totalAmount" label="Amount"
            :formatter="formatAmount"
            sortable>
        </el-table-column>
        <el-table-column
            prop="category" label="Category"
            :filters="categories"
            :filter-multiple="true"
            :filter-method="filterHandler"
            filter-placement="bottom-end"
            sortable>
        </el-table-column>
        <el-table-column
            prop="date"
            label="Date"
            sortable
            width="180"
            :formatter="formatDate"
            :filter-method="filterHandler"
        >
        </el-table-column>
    </el-table>
</template>

<script>
import { mapState, mapGetters } from 'vuex'
import Receipt from '@/components/Receipt'

const sortedUniques = (arr, key) => [...new Set(arr.map(x => x[key]))].sort()

export default {
    name: 'receipts-table',
    props: ['receipts'],
    mounted() {
        this.tableDataModified() // First load
    },
    data() {
        return {
            activeReceiptId: null
        }
    },
    components: {
        Receipt
    },
    computed: {
        categories() {
            return sortedUniques(this.receiptList, 'category').map(cat => ({'text': cat, 'value': cat}))
        },
        merchants() {
            return sortedUniques(this.receiptList, 'merchantName').map(mer => ({'text': mer, 'value': mer}))
        },
        activeReceipt() {
            return this.receipts[this.activeReceiptId]
        },
        receiptList() {
            return Object.values(this.receipts)
        },
        ...mapState({
          pending: state => state.receipts.pending.receipts,
        })
    },
    methods: {
        formatAmount: row => {
            if (row.totalAmount !== null) {
                return (row.totalAmount).toLocaleString('en-US', {style: 'currency', currency: 'USD'})
            }
        },
        formatDate: row => {
            if (row.date !== null) {
                return new Date(row.date).toLocaleDateString('en-US')
            }
        },
        filterHandler(value, row, column) {
            const property = column['property'];
            return row[property] === value;
        },
        setActiveReceiptId(row, expandedRows) {

            this.activeReceiptId = row.id
        },
        tableDataModified() {
            this.$emit('table-data-modified', this.$refs['receiptsTable'].tableData)
        }
    }
}
</script>

<style >
.receipts-table {
    overflow-scrolling: touch;
    -webkit-overflow-scrolling: touch;
}
</style>