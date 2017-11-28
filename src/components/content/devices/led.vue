<template>
    <el-col :span="10" :offset="6">
        <el-row style="text-align:center;margin-top:50px;">
            <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            <colorPicker v-model="deviceProperty.color" v-on:accept="headleChangeColor"></colorPicker>
        </el-row>
        <el-row>
            <div style="text-align:center">
                <i class="fa fa-lightbulb-o big-font-icon led-light" style="margin:0;"></i>
            </div>
        </el-row>
    </el-col>
</template>

<script>
import ledApi from "./led/led.js";
import http from "../../../assets/js/http"
import colorPicker from "vue-color-picker";
import $ from "jquery";
// import colorPicker from '../../Common/colorPicker'
export default {
  data() {
    return {
      deviceProperty: {
        on_off: false,
        brightness: 0,
        color: '#c0ccda',
        // red: "c0",
        // green: "cc",
        // blue: "da"
      }
    };
  },
  methods: {
    setColor() {
      var vm = this
    const data = {
      params: {
        id: this.device.id,
        color: this.deviceProperty.color
      }
    };
    this.apiGet("device/index.php?action=setColor", data).then(res => {
      if (res[0]) {
        var devices = this.$store.state.devices;
        for (var i = 0; i < devices.length; i++) {
          if (devices[i].id == this.device.id) {
            devices[i].mode = this.deviceProperty.color;
          }
        }
        vm.$store.dispatch("setDevices", devices);
        // _g.toastMsg("success", res[1]);
      } else {
        // _g.toastMsg("error", res[1]);
      }
    });
  },
    //开关滑块
    //驱动颜色变化的数据为 255色的16位进制
    //UDP发送的颜色数据为  100色的16位进制
    switch_change(val) {
      ledApi.switch_change(val, this.device, this.deviceProperty);
    },
    //当颜色值发生改变时
    headleChangeColor(val) {
      this.deviceProperty.color = val.hex
      // this.setColor()
      ledApi.headleChangeColor(val, this.device, this.deviceProperty);
    }
  },
  mounted() {
    ledApi.readStatus(this.device, this.deviceProperty);
    $(".vc-target").hide();
    $(".led-light").click(function() {
      $(".vc-target").trigger("click");
    });
    $(".vc-container").css("z-index", 999);
     this.deviceProperty.color = this.device.mode?this.device.mode:this.deviceProperty.color
    $(".led-light").css("color", this.deviceProperty.color);
  },
  computed: {
    device() {
      var device = this.$store.state.device;
      return device;
    }
  },
  components: {
    colorPicker
  },
  mixins:[http]
};
</script>