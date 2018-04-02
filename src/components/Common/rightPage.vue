<template>
  <div @click.stop="stop()">
    <el-card class="contarl-right-page">
        <div class="right-page-content">
            <div class="left-collect" @click="hideRightPage">
                <div class="btn-contetn">
                <i class="collect-btn el-icon-caret-right"></i>
                </div>
            </div>
            <div class="content">
              <light v-if="devicetype == 'light'"></light>
              <led v-if="devicetype == 'led'"></led>
              <ac v-if="devicetype == 'ac'"></ac>
              <music v-if="devicetype == 'music'"></music>
              <ir v-if="devicetype == 'ir'"></ir>
              <security v-if="devicetype == 'security'"></security>
              <curtain v-if="devicetype == 'curtain'"></curtain>
              <floorheat v-if="devicetype == 'floorheat'"></floorheat>
            </div>
        </div>
        
    </el-card>
  </div>
</template>
<style>
.contarl-right-page {
  position: absolute;
  top: 0;
  right: 0;
  width: 350px;
  height: 99%;
  background-color: #fff;
  z-index:999;
}
.contarl-right-content {
  position: relative;
}
.contarl-right-page .left-collect {
  position: absolute;
  top: 0;
  left: 0;
  width: 30px;
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
import light from "../Content/devices/light/index";
import led from "../Content/devices/led/index";
import ac from "../Content/devices/ac/index";
import music from "../Content/devices/music/index";
import ir from "../Content/devices/ir/index";
import curtain from "../Content/devices/curtain/index";
import security from "../Content/devices/security/index";
import floorheat from "../Content/devices/floorheat/index";
export default {
  data() {
    return {
      // devicetype:"",
    };
  },
  // props: ['device'],
  methods: {
    stop(){},
    hideRightPage() {
      this.$store.dispatch("setShowRightPage", false);
    }
  },
  mounted() {},
  components: {
    light,
    led,
    ac,
    music,
    ir,
    curtain,
    security,
    floorheat,
  },
  computed: {
    devicetype() {
      var device = this.$store.state.device;
      return device.devicetype;
    },
    showRightPage() {
      return this.$store.state.showRightPage;
    }
  }
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