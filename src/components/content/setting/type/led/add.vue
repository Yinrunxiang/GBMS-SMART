<template>
    <div class="m-l-50 m-t-30 w-500">
        <el-form ref="form" :model="form" label-width="150px">
            <el-form-item label="Mode Name">
                <el-input v-model.trim="form.breed" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Watts">
                <el-input v-model.trim="form.watts" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Run Time(h)">
                <el-input v-model="form.run_time" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Cancel</el-button>
            </el-form-item>
        </el-form>
    </div>
</template>

<script>
import http from "../../../../../assets/js/http";
import fomrMixin from "../../../../../assets/js/form_com";

export default {
  data() {
    return {
      isLoading: false,
      form: {
        breed: "",
        watts: "",
        run_time: "",
        status: "enabled"
      }
    };
  },
  methods: {
    add(form) {
      var vm = this
      this.isLoading = !this.isLoading;
      const data =this.form
      this.apiPost("admin/led_breed", data).then(res => {
        // _g.clearVuex('setRules')
        if (res[0]) {
          var led_breed = [];
          led_breed = led_breed.concat(vm.$store.state.led_breed);
          led_breed.push(vm.form);
          vm.$store.dispatch('setLedBreed', led_breed)
          _g.toastMsg("success", res[1]);
          setTimeout(() => {
            vm.goback();
          }, 500);
        } else {
          _g.toastMsg("error", res[1]);
        }
        vm.isLoading = false;
      });
    }
  },
  created() {},
  mounted() {},
  components: {},
  mixins: [http, fomrMixin]
};
</script>