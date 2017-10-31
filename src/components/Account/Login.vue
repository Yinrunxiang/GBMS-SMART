<template>
  <div @keyup.enter="handleSubmit2(form)">
    <el-form :model="form" :rules="rules2" ref="form" label-position="left" label-width="0px" class="demo-ruleForm card-box loginform">
      <h3 class="title">{{systemName}}</h3>
      <el-form-item prop="username" class="m-b-20">
        <el-input type="text" v-model="form.username" auto-complete="off" placeholder="Account"></el-input>
      </el-form-item>
      <el-form-item prop="password" class="m-b-20">
        <el-input type="password" v-model="form.password" auto-complete="off" placeholder="Password"></el-input>
      </el-form-item>
      <el-checkbox v-model="checked" style="margin:0px 0px 35px 0px;">Remember</el-checkbox>
      <el-form-item style="width:100%;">
        <el-button type="primary" style="width:100%;" v-loading="loading" @click="handleSubmit2('form')">Sign in</el-button>
      </el-form-item>
    </el-form>
  </div>
</template>

<script>
import http from '../../assets/js/http'

export default {
  data() {
    return {
      systemName: 'SMART GBMS',
      loading: false,
      form: {
        username: '',
        password: '',
      },
      rules2: {
        username: [
          { required: true, message: 'Please enter your account', trigger: 'blur' }
        ],
        password: [
          { required: true, message: 'Please enter your password', trigger: 'blur' }
        ]
      },
      checked: false
    }
  },
  methods: {
    handleSubmit2(form) {
      if (this.loading) return
      this.$refs.form.validate((valid) => {
        if (valid) {
          this.loading = !this.loading

          let data = {
            params: {
              username: this.form.username,
              password: this.form.password
            }

          }
          if (this.checked) {
            data.params.isRemember = 1
          } else {
            data.params.isRemember = 0
          }
          this.apiGet('account/account.php?action=login', data).then((res) => {
            if (!res[0]) {
              this.loading = !this.loading
              _g.toastMsg('error', "Login error")
            } else {
              // this.refreshVerify()
              if (this.checked) {
                Cookies.set('rememberPwd', true, { expires: 1 })
              }
              this.resetCommonData(res[0])
              _g.toastMsg('success', 'Login success')
            }
          })
        } else {
          return false
        }
      })
    },
    checkIsRememberPwd() {
      if (Cookies.get('rememberPwd')) {
        var username = Lockr.get('username')
        var password = Lockr.get('password')
        var database_name = Lockr.get('database_name')
        let data = {
          params: {
            username: username,
            password: password,
            database_name:database_name,
          }

        }
        this.apiGet('account/account.php?action=login', data).then((res) => {
          console.log(res)
          if (res[0]) {
            this.resetCommonData(res[0])
          }

        })
      }
    }
  },
  created() {
    console.log('login')
    // var username = Lockr.get('username')
    // var password = Lockr.get('password')
    // if (username )
    this.checkIsRememberPwd()
  },
  mounted() {
    // window.addEventListener('keyup', (e) => {
    //   if (e.keyCode === 13) {
    //     this.handleSubmit2('form')
    //   }
    // })
  },
  mixins: [http]
}
</script>

<style>
.verify-pos {
  position: absolute;
  right: 100px;
  top: 0px;
}

.card-box {
  padding: 20px;
  /*box-shadow: 0 0px 8px 0 rgba(0, 0, 0, 0.06), 0 1px 0px 0 rgba(0, 0, 0, 0.02);*/
  -webkit-border-radius: 5px;
  border-radius: 5px;
  -moz-border-radius: 5px;
  background-clip: padding-box;
  margin-bottom: 20px;
  background-color: #F9FAFC;
  margin: 120px auto;
  width: 400px;
  border: 2px solid #8492A6;
}

.title {
  margin: 0px auto 40px auto;
  text-align: center;
  color: #505458;
}

.loginform {
  width: 350px;
  padding: 35px 35px 15px 35px;
}
</style>