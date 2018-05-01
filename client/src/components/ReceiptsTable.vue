<template>
  <div class>
    <el-table
      :data="tableData"
      show-summary
      sum-text="Total"
      stripe
      style="width: 100%">
      <el-table-column type="expand">
      <template slot-scope="props">
        <p>derp</p>
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
const sortedUniques = (arr, key) => [...new Set(arr.map(x => x[key]))].sort()

export default {
    name: 'receipts-table',
    props: ['receipts'],
    computed: {
        categories() {
            return sortedUniques(this.receipts, 'category').map(cat => ({'text': cat, 'value': cat}))
        },
        merchants() {
            return sortedUniques(this.receipts, 'merchantName').map(mer => ({'text': mer, 'value': mer}))
        },
        tableData() {
          return this.receipts
        }
    },
    methods: {
        formatAmount: row => (row.totalAmount).toLocaleString('en-US', {style: 'currency', currency: 'USD'}),
        formatDate: row => (row.date).toLocaleDateString('en-US'),
        filterTag(value, row) {
            return row.tag === value;
        },
        filterHandler(value, row, column) {
            const property = column['property'];
            return row[property] === value;
        }
    }
}
</script>
