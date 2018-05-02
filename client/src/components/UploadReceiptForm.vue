<template>
  <div class="form-wrapper">
    <el-form :model="receiptForm" :rules="rules" ref="receiptForm" label-width="120px" v-loading="pending">
      <el-form-item label="Name" prop="name" :error="serverFormErrors.name">
        <el-input v-model="receiptForm.name"></el-input>
      </el-form-item>
      <el-form-item label="Category" prop="category">
        <el-select v-model="receiptForm.category" placeholder="Category" :error="serverFormErrors.category">
          <el-option v-for="category in categories" :key="category" :label="category" :value="category"></el-option>
        </el-select>
      </el-form-item>
      <el-form-item label="Notes" prop="notes" :error="serverFormErrors.notes">
        <el-input type="textarea" v-model="receiptForm.notes"></el-input>
      </el-form-item>
      <el-form-item label="Receipt Image" prop="receipt" :error="serverFormErrors.receipt">
        <el-input type="file" v-model="receiptForm.receipt" ref="rfile" class="file-input"></el-input>
      </el-form-item>
        <el-button type="primary" @click="submitForm()">Upload</el-button>
        <el-button @click="$emit('receipt-form-finished')">Cancel</el-button>
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
      serverFormErrors: {}, // If the server-side validation fails, we'll show them here
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
          { required: true, message: 'Please select an image to upload', trigger: 'change' }
        ]
      }
    };
  },
  methods: {
    submitForm(formName) {
      this.$refs['receiptForm'].validate((valid) => {

          const data = this.formDataObj
          this.uploadReceipt({data: data }).then((resp) => {
            this.$message({
              message: 'Receipt Uploaded Sucessfully',
              type: 'success'
            })

            this.$emit('receipt-uploaded', resp.data.data)
            this.$emit('receipt-form-finished')  // Let's the parent component know we're finished. In this case, it will close the dialog box.
          }).catch((err) => {
            if (err.response.data.status === 400) {
              // Server-side validation failed. Set the errors on here so the items will update the labels
              const errors = err.response.data.invalid_fields
              this.serverFormErrors = Object.assign({}, this.serverFormErrors, errors)
            } else {
              console.error(err)
            }
          })

      });
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