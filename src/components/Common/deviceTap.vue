<template>
    <div class="device-tap" @dblclick="deviceDbclick()" ref="device" :style="{left:device.x_axis + 'px', top:device.y_axis + 'px'}">
        <el-tooltip placement="bottom" transition="" style="width:42px;height:42px;">
            <div style="width:150px;word-break:break-word;" slot="content">
              <p>Name : {{device.device}}</p>
              <p v-if="device.comment" >Comment : {{device.comment}}</p>
            </div>
            <div>
                <div :class="{'device-tap-border': setting }" class="icon">
                    <i class="fa" :class="iconstyle(device.devicetype)"></i>
                </div>
                <el-switch class="device-switch" v-model="device.deviceProperty.on_off" @change="switch_change">
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

.device-tap .icon {
  position: absolute;
  top: 0;
  left: 0;
  padding: 6px;
  width: 24px;
  height: 24px;
  line-height: 24px;
  font-size: 24px;
  color: #fff;
  background-color: #20a0ff;
  border: 3px solid #20a0ff;
  border-radius: 24px;
}

.device-tap .icon i {
  margin-left: 5px;
}
.device-tap .icon .fa-music {
  margin-left: -2px;
}
.device-tap .icon .fa-life-ring {
  font-size: 22px;
  margin-left: -2px;
}
.device-tap .icon .fa-columns {
  font-size: 22px;
  margin-left: -2px;
}
.device-tap .device-switch {
  position: absolute;
  top: 9px;
  left: 41px;
  border: 2px solid #20a0ff;
  border-radius: 15px;
  margin-left: -13px;
}

.device-tap .device-tap-border {
  border: 2px solid #fff;
}
</style>

<script>
import lightApi from "../Content/devices/light/api";
import acApi from "../Content/devices/ac/api";
import ledApi from "../Content/devices/led/api";
import musicApi from "../Content/devices/music/api";
import "../../assets/js/jquery-1.9.1.min.js";
import "../../assets/js/drag.js";
import http from "../../assets/js/http";
export default {
  data() {
    return {
      jqDevice: {}
    };
  },
  props: ["device"],
  methods: {
    //点击图标开启设备操作页
    iconClick() {
      this.$store.dispatch("showContral", true);
      let url = "/home/contral/" + this.device.devicetype;
      this.$store.dispatch("setDevice", this.device);
      router.push(url);
    },
    //点击图标开启设备操作页
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
    //开关设备
    switch_change(val) {
      switch (this.device.devicetype) {
        case "light":
          lightApi.switch_change(val, this.device);
          break;
        case "ac":
          acApi.switch_change(val, this.device);
          break;
        case "led":
          ledApi.switch_change(val, this.device);
          break;
        case "music":
          musicApi.switch_change(val,this.device)
          break;
      }
    },
    //读取设备开启状态
    readOpen() {
      switch (this.device.devicetype) {
        case "light":
          lightApi.readOpen(this.device);
          break;
        case "ac":
          acApi.readOpen(this.device);
          break;
        case "led":
          ledApi.readOpen(this.device);
          break;
        case "music":
          // this.device.on_off = false
          musicApi.readStatus(this.device);
          break;
      }
      // console.log("OK");
    },
    //根据设备类型选择图标
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
        case "floorheat":
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
        case "security":
          return "fa-lock";
          break;
      }
    }
  },
  created() {
    // console.log('deviceTap')
    // this.deviceProperty = this.device.deviceProperty;
    // console.log('device list device')
    // this.readOpen()
  },
  mounted() {
    var self = this;
    this.$nextTick(function() {
      var device = this.$refs.device;
      this.jqDevice = $(device);
      this.jqDevice.myDrag({
        parent: "parent", //定义拖动不能超出的外框,拖动范围
        randomPosition: false, //初始化随机位置
        direction: "all", //方向
        handler: false, //把手
        lock: self.lock,
        dragStart: function(x, y) {
          // if (self.lock) {
          //   self.$message({
          //     showClose: true,
          //     message:
          //       "Mobile function has been banned, please click the right unlock button and move again",
          //     type: "warning",
          //     duration:3000,
          //   });
          // }
        }, //拖动开始 x,y为当前坐标
        dragEnd: function(x, y) {
          if (self.device.x_axis != x || self.device.y_axis != y) {
            self.device.x_axis = x;
            self.device.y_axis = y;
            if (self.device.id && self.device.id != "") {
              const data = {
                id: self.device.id,
                x_axis: x,
                y_axis: y
              };
              self.apiPost("admin/device/updateLocation", data).then(res => {
                self.handelResponse(res, data => {
                  var devices = self.$store.state.devices;
                  for (var i = 0; i < devices.length; i++) {
                    if (devices[i].id == self.device.id) {
                      devices[i] = self.device;
                    }
                  }
                  self.$store.dispatch("setDevices", devices);
                });
              });
            }
          }
        }, //拖动停止 x,y为当前坐标
        dragMove: function(x, y) {} //拖动进行中 x,y为当前坐标
      });
    });
  },
  props: ["device", "setting", "lock"],
  components: {},
  computed: {},
  mixins: [http]
};
</script>