<template>
        <el-table
            :data="tableData"
            stripe
            show-summary
            sum-text="Total"
            v-loading="pending"
            class="receipts-table"
            v-on:expand-change="setActiveReceiptId"
            >
            <el-table-column
                width="50"
                type="expand">
                    <template slot-scope="props">
                        <receipt v-bind:receipt="props.row"
                                 v-on:receipt-changed="getReceipts"></receipt>
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
    </div>
</template>

<script>
import { mapState, mapGetters, mapActions } from 'vuex'
import Receipt from '@/components/Receipt'
const sortedUniques = (arr, key) => [...new Set(arr.map(x => x[key]))].sort()

export default {
    name: 'receipts-table',
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
            return sortedUniques(this.receipts, 'category').map(cat => ({'text': cat, 'value': cat}))
        },
        merchants() {
            return sortedUniques(this.receipts, 'merchantName').map(mer => ({'text': mer, 'value': mer}))
        },
        tableData() {
          return this.receipts
        },
        activeReceipt() {
            return this.receipts.find(x => x.id === this.activeReceiptId)
        },
        ...mapState({
          pending: state => state.receipts.pending.receipts,
        }),
        ...mapGetters(['receipts'])
    },
    methods: {
        formatAmount: row => (row.totalAmount).toLocaleString('en-US', {style: 'currency', currency: 'USD'}),
        formatDate: row => new Date(row.date).toLocaleDateString('en-US'),
        filterHandler(value, row, column) {
            const property = column['property'];
            return row[property] === value;
        },
        setActiveReceiptId(row, expandedRows) {

            this.activeReceiptId = row.id
        },
        ...mapActions([
          "getReceipts"
        ])
    }
}
</script>

<style >
.receipts-table {
    overflow-scrolling: touch;
    -webkit-overflow-scrolling: touch;
}
</style>