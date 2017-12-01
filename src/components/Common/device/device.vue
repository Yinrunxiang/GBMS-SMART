<template>
    <el-card class="box-card" style="width:150px;padding:0;margin-right:30px;margin-bottom:16px;text-align: center;">
        <i v-if="device.warn" class="el-icon-warning device-card-warn"></i>
        <i class="el-icon-close device-close" @click="deviceDelete"></i>
        <div class="device-box">
            
            <div @click="deviceContral(device)">
                <i class="fa" :class="iconstyle(device.devicetype)" style="font-size:80px;color:#ccc"></i>
            </div>
            <div style="margin-top:5px">
                <el-switch v-model="device.on_off" @change="switch_change">
                </el-switch>
            </div>

        </div>
        <div style="padding:0;margin:0;height:30px;line-height:30px;color:#aaa;border-top:1px solid #515151">
            <p style="margin:0">{{device.device}}</p>
        </div>
    </el-card>
</template>

<style>
.box-card {
  position: relative;
}
.device-box {
  padding: 0;
  margin-bottom: 10px;
}
.device-card-warn {
  position: absolute;
  top: 5px;
  left: 5px;
  font-size: 16px;
  color: #ff4949;
}
.device-close {
  position: absolute;
  top: 3px;
  right: 3px;
  padding: 3px;
  border-radius: 3px;
  font-size: 6px;
  color: rgb(204, 204, 204);
}
.device-close:hover {
  background-color: #ff4949;
  color: #fff;
}
</style>

<script>
import http from "../../../assets/js/http";
import lightApi from "../../content/devices/light/light.js";
import acApi from "../../content/devices/ac/ac.js";
import ledApi from "../../content/devices/led/led.js";
import musicApi from "../../content/devices/music/music.js";
import curtainApi from "../../content/devices/curtain/curtain.js";
export default {
  data() {
    return {};
  },
  props: ["device"],
  methods: {
    deviceDelete() {
      this.$confirm("Are you sure to delete the selected data?", "Tips", {
        confirmButtonText: "Yse",
        cancelButtonText: "No",
        type: "warning"
      })
        .then(() => {
          const data = {
            params: {
              selections: [this.device]
            }
          };
          this.apiGet("device/index.php?action=delete", data).then(res => {
            if (res[0]) {
              var devices = this.$store.state.devices;
              for (var i = 0; i < devices.length; i++) {
                if (devices[i].id == this.device.id) {
                  devices.splice(i, 1);
                }
              }
              this.$store.dispatch("setDevices", devices);
              _g.toastMsg("success", res[1]);
            } else {
              _g.toastMsg("error", res[1]);
            }
          });
        })
        .catch(() => {
          // catch error
        });
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
          // this.device.on_off = false;
          var deviceProperty = {
            on_off: false,
            brightness: 0,
            color: this.device.mode ? this.device.mode : "#ffffff"
          };
          ledApi.switch_change(val, this.device, deviceProperty);
          break;
        case "music":
          // musicApi.switch_change(this.device)
          break;
        case "curtain":
          curtainApi.switch_change(val, this.device);
          break;
        case "ir":
          var deviceProperty = {
            on_off: false,
            brightness: 0
          };
          curtainApi.switch_change(val, this.device, deviceProperty);
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
          this.device.color = "#c0ccda";
          this.device.red = "c0";
          this.device.green = "cc";
          this.device.blue = "da";
          ledApi.readOpen(this.device);
          break;
        case "music":
          // this.device.on_off = false
          // musicApi.readOpen(this.device)
          break;
        case "curtain":
          this.device.on_ff = false;
          curtainApi.readOpen(this.device);
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
    // console.log("device list device");
    // this.readOpen()
  },
  props: ["device"],
  components: {},
  computed: {},
  mixins: [http]
};
</script>