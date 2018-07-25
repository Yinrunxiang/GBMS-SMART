<template>
    <el-row class="w-100p">
        <el-col :xs= "22" :md = "{span:6}" class="m-a-10">
        <el-form ref="form" :model="form" label-width="100px">
            <el-form-item  label="Mode Name">
                <el-input :disabled="true" v-model.trim="form.breed" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Watts">
                <el-input v-model.trim="form.watts" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item label="Run Time(h)">
                <el-input v-model="form.run_time" class="h-40 w-200"></el-input>
            </el-form-item>
            <el-form-item>
                <el-button type="primary" @click="add('form')" :loading="isLoading">Save</el-button>
                <el-button @click="goback()">Back</el-button>
            </el-form-item>
        </el-form>
        </el-col>
    </el-row>
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
      var vm = this;
      this.isLoading = !this.isLoading;
      const data = this.form;
      this.apiPut("admin/light_breed/", data.id, data).then(res => {
        this.handelResponse(res, data => {
          var light_breed = [];
          light_breed = light_breed.concat(vm.$store.state.light_breed);
          for (var i = 0; i < light_breed.length; i++) {
            if (light_breed[i].breed == vm.form.breed) {
              light_breed[i] = vm.form;
            }
          }
          vm.$store.dispatch("setLightBreed", light_breed);
          _g.toastMsg("success", data);
          setTimeout(() => {
            vm.goback();
          }, 500);
        });

        vm.isLoading = false;
      });
    }
  },
  created() {
    this.form = this.$route.query;
  },
  mounted() {},
  components: {},
  mixins: [http, fomrMixin]
};
</script>