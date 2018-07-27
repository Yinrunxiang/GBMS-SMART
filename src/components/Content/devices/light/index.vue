<template>
    <div class="right-page-content light">
      <div>{{device.device}}</div>
        <div>
            <i class="fa fa-lightbulb-o icon"></i>
        </div>
        <el-switch v-model="device.deviceProperty.on_off" @change="switch_change">
        </el-switch>
        <template>
            <div class="block">
                <el-slider v-model="device.deviceProperty.brightness" :min='0' :max='100' :step="1" @change="slider_change">
                </el-slider>
            </div>
        </template>
    </div>
</template>
<style>
.right-page-content {
  margin: 0 10px;
  text-align: center;
  width: 300px;
  height: 100%;
}
.light .icon {
  display: inline-block;
  width: 300px;
  height: 300px;
  font-size: 300px;
  color: #c0ccda;
  text-align: center;
}
</style>
<script>
import lightApi from "./api";
export default {
  data() {
    return {
    };
  },
  // props: ['device'],
  methods: {
    //开关
    switch_change(val) {
      lightApi.switch_change(val, this.device);
    },
    //亮度调节
    slider_change(val) {
      lightApi.slider_change(val, this.device);
    }
  },
  created(){
   
  },
  mounted() {
    console.log("light vue");
    lightApi.readStatus(this.device);
  },
  destroyed() {
    lightApi.closeSocket();
  },
  components: {},
  computed: {
    device() {
      var device = this.$store.state.device;
      
      return device;
    }
    // watch: {
    //   device: {
    //     handler: function(val, oldVal) {
    //         lightApi.readStatus(this.device, this.deviceProperty);
    //     },
    //     deep: true
    //   }
    // }
  }
};
</script>