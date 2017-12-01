<template>
    <div class="device-tap" @dblclick="deviceDbclick()" ref="device" :style="{left:device.x_axis + 'px', top:device.y_axis + 'px'}">
        <el-tooltip placement="bottom" transition="" style="width:42px;height:42px;">
            <div  slot="content">{{device.device}}</div>
            <div>
                <div :class="{'device-tap-border': setting }" class="icon">
                    <i class="fa" :class="iconstyle(device.devicetype)"></i>
                </div>
                <el-switch class="device-switch" v-model="device.on_off" @change="switch_change">
                </el-switch>
            </div>
        </el-tooltip>
    </div>
</template>

<style>
.device-tap {
  position: relative;
  width: 100px;
  height: 50px;
}

.icon {
  position: absolute;
  top: 0;
  left: 0;
  padding: 6px;
  width: 24px;
  height: 24px;
  font-size: 24px;
  color: #fff;
  background-color: #20a0ff;
  border: 3px solid #20a0ff;
  border-radius: 24px;
}

.icon i {
  margin-left: 5px;
}

.device-switch {
  position: absolute;
  top: 9px;
  left: 41px;
  border: 2px solid #20a0ff;
  border-radius: 15px;
  margin-left: -13px;
}

.device-tap-border {
  border: 2px solid #fff;
}
</style>

<script>
import lightApi from "../../content/devices/light/light.js";
import acApi from "../../content/devices/ac/ac.js";
import ledApi from "../../content/devices/led/led.js";
import musicApi from "../../content/devices/music/music.js";
import "../../../assets/js/jquery-1.9.1.min.js";
import "../../../assets/js/drag.js";
import http from "../../../assets/js/http";
export default {
  data() {
    return {};
  },
  props: ["device"],
  methods: {
    iconClick() {
      this.$store.dispatch("showContral", true);
      let url = "/home/contral/" + this.device.devicetype;
      this.$store.dispatch("setDevice", this.device);
      router.push(url);
    },
    deviceContral(device) {
      this.$store.dispatch("showContral", true);
      let url = "/home/contral/" + device.devicetype;
      // console.log('typelist:' + url + '/n');
      this.$store.dispatch("setDevice", device);
      if (url != this.$route.path) {
        router.push(url);
      } else {
        _g.shallowRefresh(this.$route.name);
      }
    },
    deviceDbclick() {
      if (this.setting) {
        this.$emit("deviceDbclick", false, true, this.device);
      } else {
        this.$emit("deviceDbclick", false, false, this.device);
      }
      this.$store.dispatch("setDevice", this.device);
      // else {
      //     this.$store.dispatch('showContral', true)
      //     let url = '/home/contral/' + this.device.devicetype
      //     this.$store.dispatch('setDevice', this.device)
      //     router.push(url)
      // }
    },
    switch_change(val) {
      switch (this.device.devicetype) {
        case "light":
          var deviceProperty = {
            on_off: false,
            brightness: 0
          };
          lightApi.switch_change(val, this.device, deviceProperty);
          break;
        case "ac":
          this.device.on_ff = false;
          acApi.switch_change(val, this.device);
          break;
        case "led":
          this.device.on_off = false;
          var deviceProperty = {
            on_off: false,
            brightness: 0,
            color: "#c0ccda",
            red: "c0",
            green: "cc",
            blue: "da"
          };
          ledApi.switch_change(val, this.device, deviceProperty);
          break;
        case "music":
          // musicApi.switch_change(this.device)
          break;
      }
    },
    readOpen() {
      switch (this.device.devicetype) {
        case "light":
          this.device.on_ff = false;
          lightApi.readOpen(this.device);
          break;
        case "ac":
          this.device.on_ff = false;
          acApi.readOpen(this.device);
          break;
        case "led":
          this.device.on_off = false;
          this.device.brightness = 0;
          color: this.device.mode ? this.device.mode : "#ffffff",
            ledApi.readOpen(this.device);
          break;
        case "music":
          // this.device.on_off = false
          // musicApi.readOpen(this.device)
          break;
      }
      // console.log("OK");
    },
    iconstyle(type) {
      switch (type) {
        case "light":
          return "fa-lightbulb-o";
          break;
        case "led":
          return "fa-lightbulb-o";
          break;
        case "ac":
          return "fa-thermometer";
          break;
        case "music":
          return "fa-music";
          break;
        case "curtain":
          return "fa-columns";
          break;
        case "ir":
          return "fa-life-ring";
          break;
      }
    }
  },
  created() {
    // console.log('device list device')
    // this.readOpen()
  },
  mounted() {
    var self = this;
    this.$nextTick(function() {
      var device = this.$refs.device;
      $(device).myDrag({
        parent: "parent", //定义拖动不能超出的外框,拖动范围
        randomPosition: false, //初始化随机位置
        direction: "all", //方向
        handler: false, //把手
        dragStart: function(x, y) {}, //拖动开始 x,y为当前坐标
        dragEnd: function(x, y) {
          if (self.device.x_axis != x || self.device.y_axis != y) {
            self.device.x_axis = x;
            self.device.y_axis = y;
            if (self.device.id && self.device.id != "") {
              const data = {
                params: self.device
              };
              self
                .apiGet("device/index.php?action=updateLocation", data)
                .then(res => {
                  // _g.clearVuex('setRules')
                  if (res[0]) {
                    var devices = self.$store.state.devices;
                    for (var i = 0; i < devices.length; i++) {
                      if (devices[i].id == self.device.id) {
                        devices[i] = self.device;
                      }
                    }
                    self.$store.dispatch("setDevices", devices);
                    // _g.toastMsg('success', res[1])
                  } else {
                    // _g.toastMsg('error', res[1])
                  }
                });
            }
          }
        }, //拖动停止 x,y为当前坐标
        dragMove: function(x, y) {} //拖动进行中 x,y为当前坐标
      });
    });
  },
  props: ["device", "setting"],
  components: {},
  computed: {},
  mixins: [http]
};
</script>