<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="selectData" label-width="150px">
            <el-form-item label="Name">
                <el-input v-model.trim="selectData.name" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="sex">
                <el-input v-model.trim="selectData.sex" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="age">
                <el-input v-model.trim="selectData.age" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="title">
                <el-input v-model.trim="selectData.title" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="tel">
                <el-input v-model.trim="selectData.tel" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="addData()" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import http from "../../../assets/js/http"
// import fomrMixin from '../../../../assets/js/form_com'

export default {
  data() {
    return {
      isLoading: false,
    };
  },
  methods: {
    goback() {
      this.$emit("goback", false);
    },
    addData() {
      this.isLoading = !this.isLoading;
      const data = {
        params: this.selectData
      };

      if (this.add) {
        this.apiPost("admin/doctor", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
  
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.isLoading = false;
        });
      } else {
        this.apiPut("device/address.php?action=update", data).then(res => {
          // _g.clearVuex('setRules')
          if (res[0]) {
            
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.isLoading = false;
        });
      }
    },
  },
  props: ["add", "selectData"],
  mounted() {
  },
  computed: {
  },
  components: {},
  mixins: [http]
};
</script>