<template>
    <el-dialog v-if="ready" class="time-setting" ref="dialog" :visible="open" :before-close="handleClose" custom-class="w-500" title="Time Contral Center">
        <div>
            <ac></ac>
        </div>
        <div class="p-t-20 p-b-20">
            <el-button type="primary" class="fl " :disabled="disable" @click="submit()">Save</el-button>
        </div>
    </el-dialog>
</template>
<style>

</style>
<script>
import http from "../../../assets/js/http";
import ac from "../devices/ac"

export default {
  data() {
    return {
      id:'',
      disable: false,
      myopen: this.open,
      runtime: {},
      ready: false
    };
  },
  methods: {
    handleClose() {
      this.myopen = false;
    },
    //保存运行时间
    submit() {
      
    },
    //获取运行时间
  },
  created() {
    console.log("setting");
    this.ready = true;
    this.$store.dispatch("setDevice", this.device);
    // this.form.auth_key = Lockr.get('authKey')
  },
  props: ["open", "device"],
  watch: {
    open(val) {
      this.myopen = val;
    },
    myopen(val) {
      this.$emit("change", val);
    }
  },
  components: {
      ac
  },
  mixins: [http]
};
</script>