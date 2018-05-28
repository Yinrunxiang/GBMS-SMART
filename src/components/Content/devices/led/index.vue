<template>
<div class="right-page-content led">
        <div>{{device.device}}</div>
        <div>
            <i class="fa fa-lightbulb-o icon led-light"></i>
        </div>
        <el-row>
            <el-switch v-model="deviceProperty.on_off" @change="switch_change">
            </el-switch>
            
        </el-row>
        <div style="visible: hidden;">
          <colorPicker v-model="deviceProperty.color" v-on:accept="headleChangeColor"></colorPicker>
        </div>
</div>
</template>
<style>
.right-page-content {
  margin: 0 10px;
  text-align: center;
  width: 300px;
  height: 100%;
}
.led .icon {
  display: inline-block;
  width: 300px;
  height: 300px;
  cursor: pointer;
  font-size: 300px;
  color: #c0ccda;
  text-align: center;
}
</style>
<script>
import ledApi from "./api";
import http from "../../../../assets/js/http";
import colorPicker from "vue-color-picker";
import $ from "jquery";
// import colorPicker from '../../Common/colorPicker'
export default {
  data() {
    return {
      deviceProperty: {
        on_off: false,
        brightness: 0,
        color: "#c0ccda"
        // red: "c0",
        // green: "cc",
        // blue: "da"
      }
    };
  },
  methods: {
    setColor() {
      var vm = this;
      const data = {
        id: this.device.id,
        color: this.deviceProperty.color
      };
      this.apiPost("admin/device/setColor", data).then(res => {
        this.handelResponse(res, data => {
          var devices = this.$store.state.devices;
          for (var i = 0; i < devices.length; i++) {
            if (devices[i].id == this.device.id) {
              devices[i].mode = this.deviceProperty.color;
            }
          }
          vm.$store.dispatch("setDevices", devices);
        });
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
      this.deviceProperty.color = val.hex;
      this.setColor();
      ledApi.headleChangeColor(val, this.device, this.deviceProperty);
    }
  },
  mounted() {
    console.log("led");
    ledApi.readStatus(this.device, this.deviceProperty);
    $(".vc-target").hide();
    $(".led-light").click(function() {
      $(".vc-target").trigger("click");
    });
    $(".vc-container").css("z-index", 999);
    this.deviceProperty.color = this.device.mode
      ? this.device.mode
      : this.deviceProperty.color;
    $(".led-light").css("color", this.deviceProperty.color);
  },
  destroyed(){
    ledApi.closeSocket();
  },
  computed: {
    device() {
      var device = this.$store.state.device;
        device.deviceProperty = this.deviceProperty
      return device;
    }
  },
  components: {
    colorPicker
  },
  mixins: [http]
};
</script>