<template>
  <el-dialog ref="dialog" :visible="showChange" :before-close="handleClose" custom-class="w-400 h-300" title="Change password">
    <div>
      <el-form ref="form" :model="form" :rules="rules" label-width="120px">
        <el-form-item label="old password" prop="old_pwd" class="m-b-15">
          <el-input v-model.trim="form.old_pwd"></el-input>
        </el-form-item>
        <el-form-item label="new password" prop="new_pwd" class="m-b-15">
          <el-input v-model.trim="form.new_pwd"></el-input>
        </el-form-item>
      </el-form>
    </div>
    <div class="p-t-20">
      <el-button type="primary" class="fl m-l-20" :disabled="disable" @click="submit()">Submit</el-button>
    </div>
  </el-dialog>
</template>
<style>

</style>
<script>
import http from '../../assets/js/http'

export default {
  data() {
    return {
      disable: false,
      myShowChange: this.showChange,
      form: {
        old_pwd: '',
        new_pwd: ''
      },
      rules: {
        old_pwd: [
          { required: true, message: 'Please input the old password', trigger: 'blur' },
          { min: 3, max: 12, message: 'The length is 3 to 12 characters', trigger: 'blur' }
        ],
        new_pwd: [
          { required: true, message: 'Please input the new password', trigger: 'blur' },
          { min: 3, max: 12, message: 'The length is 3 to 12 characters', trigger: 'blur' }
        ]
      }
    }
  },
  methods: {
    // open() {
    //   console.log(this.$refs.dialog)
    //   this.$refs.dialog.open()
    // },
    // close() {
    //   this.$refs.dialog.close()
    // },
    handleClose(done) {
      this.myShowChange = false
    },
    submit() {

      this.$refs.form.validate((pass) => {
        if (pass) {

          this.disable = !this.disable
          var password = Lockr.get('password')
          if (this.form.old_pwd != password) {
            _g.toastMsg('error', 'Password is not correct.')
            return
          }
          this.form.username = Lockr.get('username')
          const data = {
            params: this.form
          }
          this.apiGet('account/account.php?action=change', data).then((res) => {
            if (res[0]) {
              _g.toastMsg('success', 'Change success')
              Lockr.rm('password')
              setTimeout(() => {
                router.replace('/')
              }, 1500)
            }
            else {
              _g.toastMsg('error', 'Change failed')
              this.disable = !this.disable
            }
          })
        }
      })
    }
  },
  created() {
    console.log('changePwd')
    // this.form.auth_key = Lockr.get('authKey')
  },
  props: ['showChange'],
  watch: {
    showChange(val) {
      this.myShowChange = val;
    },
    myShowChange(val) {
      this.$emit("change", val);
    }
  },

  mixins: [http]
}
</script>