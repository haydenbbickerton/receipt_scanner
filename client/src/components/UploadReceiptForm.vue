<template>
  <div class="form-wrapper">
    <el-form :model="receiptForm" :rules="rules" ref="receiptForm" label-width="120px" v-loading="pending">
      <el-form-item label="Name" prop="name">
        <el-input v-model="receiptForm.name"></el-input>
      </el-form-item>
      <el-form-item label="Category" prop="category">
        <el-select v-model="receiptForm.category" placeholder="Category">
          <el-option v-for="category in categories" :key="category" :label="category" :value="category"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Notes" prop="notes">
        <el-input type="textarea" v-model="receiptForm.notes"></el-input>
      </el-form-item>
      <el-form-item label="Receipt" prop="receipt">
        <span>(jpeg, png, pdf)</span>
        <el-input type="file" v-model="receiptForm.receipt" ref="rfile" class="file-input"></el-input>
      </el-form-item>

        <el-button type="primary" @click="submitForm()">Create</el-button>
        <el-button @click="resetForm()">Reset</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import { mapState, mapActions } from 'vuex'

export default {
  name: 'upload-receipt',
  data() {
    return {
      receiptForm: {
        name: '',
        category: '',
        notes: '',
        receipt: null
      },
      categories: [
        'airfare',
        'vehicle rental',
        'fuel',
        'lodging',
        'meals',
        'phone',
        'entertainment',
        'weapons',
        'other'
      ],
      rules: {
        name: [
          { required: true, message: 'Please give you receipt a name', trigger: 'blur' },
          { min: 3, max: 32, message: 'Length should be 3 to 5', trigger: 'blur' }
        ],
        category: [
          { required: true, message: 'Please select a category', trigger: 'change' }
        ],
        receipt: [
          { required: true, message: 'Please select a file to upload', trigger: 'change' }
        ]
      }
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs['receiptForm'].validate((valid) => {
        if (valid) {
          const data = this.formDataObj
          this.uploadReceipt({data: data }).then((resp) => {
            this.$message({
              message: 'Receipt Uploaded Sucessfully',
              type: 'success'
            })
            this.$emit('receipt-uploaded')
          }).catch((err) => {
            console.error(err)
            this.loading = false
          })

        } else {
          console.log('error submit!!');
          return false;
        }
      });
    },
    resetForm(formName) {
      this.$refs['receiptForm'].resetFields();
    },
    ...mapActions([
      "uploadReceipt"
    ])
  },
  computed: {

    formDataObj() {
      // In order to upload the file, we have to turn it into a FormData obj.
      let formData = new FormData();
      formData.append('userId', this.userId)
      formData.append('name', this.receiptForm.name)
      formData.append('category', this.receiptForm.category)
      formData.append('notes', this.receiptForm.notes)
      formData.append('receipt', this.$refs['rfile'].$refs.input.files[0])

      return formData
    },
    ...mapState({
      receipt: state => state.receipts.receipt,
      pending: state => state.receipts.pending.receipt,
      error: state => state.receipts.error,
      userId: state => state.user.id
    })
  }
}
</script>

<style type="text/css">
.el-input.file-input > input {
  border-width: 0;
  padding: 0;
  height: auto;
}
</style>