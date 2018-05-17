<template>
    <div class="right-page-content light">
      <div>{{device.device}}</div>
        <div>
            <i class="fa fa-lightbulb-o icon"></i>
        </div>
        <el-switch v-model="deviceProperty.on_off" @change="switch_change">
        </el-switch>
        <template>
            <div class="block">
                <el-slider v-model="deviceProperty.brightness" :min='0' :max='100' :step="1" @change="slider_change">
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
      deviceProperty: {
        brightness: 0,
        on_off: false
      }
    };
  },
  // props: ['device'],
  methods: {
    switch_change(val) {
      lightApi.switch_change(val, this.device, this.deviceProperty);
    },
    slider_change(val) {
      lightApi.slider_change(val, this.device, this.deviceProperty);
    }
  },
  mounted() {
    console.log("light vue");
    lightApi.readStatus(this.device, this.deviceProperty);
  },
  destroyed(){
    lightApi.closeSocket();
  },
  components: {},
  computed: {
    device() {
        
      var device = this.$store.state.device;
      this.deviceProperty.brightness = 0
      this.deviceProperty.on_off = false
      // lightApi.readStatus(device, this.deviceProperty);
      
      return device;
    },
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