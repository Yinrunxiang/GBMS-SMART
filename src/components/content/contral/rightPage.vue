<template>
    <el-card class="contarl-right-page">
        <div class="right-page-content">
            <div class="left-collect" @click="hideRightPage">
                <div class="btn-contetn">
                <i class="collect-btn el-icon-caret-right"></i>
                </div>
            </div>
            <div class="content">
              <light v-if="devicetype == 'light'"></light>
            </div>
        </div>
        
    </el-card>
</template>
<style>
.contarl-right-page {
  position: absolute;
  top: 0;
  right: 0;
  width: 350px;
  height: 100%;
  background-color: #fff;
}
.contarl-right-content {
  position: relative;
}
.contarl-right-page .left-collect {
  position: absolute;
  top: 0;
  left: 0;
  width: 18px;
  height: 100%;
}
.contarl-right-page .left-collect:hover {
  background-color: #eee;
  color: #333;
}
.contarl-right-page .left-collect .btn-contetn {
  position: relative;
  height: 100%;
}
.contarl-right-page .left-collect .collect-btn {
  position: absolute;
  top: 45%;
  left: 0;
  font-size: 18px;
}
</style>

<script>
import light from "./light";
export default {
  data() {
    return {
      // devicetype:"",
    };
  },
  // props: ['device'],
  methods: {
    hideRightPage(){
      this.$store.dispatch("setShowRightPage", false);
    }
  },
  mounted() {},
  components: {
    light
  },
  computed: {
    devicetype() {
      var device = this.$store.state.device;
      return device.devicetype;
    },
    showRightPage() {
      return this.$store.state.showRightPage;
    },
    
  },
  // watch: {
  //   devicetype: {
  //     handler: function(val, oldVal) {
  //       this.devicetype = ""
  //       this.devicetype = val.devicetype
  //     },
  //     deep: true
  //   }
  // }
};
</script>