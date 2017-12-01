<template>
    <div  v-loading="moodLoading">
    <el-card v-for="mood in moodList" class="box-card m-b-15" :span="24">
            <div v-show="!mood.add" style="line-height:36px" >
              <span>{{mood.mood}}</span>
              <a  class="fa fa-close fr m-l-10 m-t-5" style="font-size:20px;color:#ff4949;" @click="deleteMood(mood)"></a>
              <el-button type="primary" @click="switch_change(true,mood.deviceList)" class="fr">Run</el-button>
                  <!-- <el-switch class="fr" v-model="mood.on_off" @change="switch_change(mood.on_off,mood.deviceList)">
                  </el-switch> -->
            </div>
           <div v-show="mood.add" >
            <el-input  v-model="mood.mood" style="width:100px;" placeholder="Mood name"></el-input>
            <el-select  v-model="devicetypes" style="width:200px;" multiple placeholder="Please choose">
            <el-option
              v-for="item in deviceTypeOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
            </el-option>
          </el-select>
          <el-select  v-if="devicetypes.indexOf('curtain') != -1" style="width:200px;" v-model="curtains" multiple placeholder="Please choose">
            <el-option
              v-for="curtain in allCurtains"
              :key="curtain.id"
              :label="curtain.device"
              :value="curtain.id">
              <span class="fl">{{ curtain.device }}</span>
               <el-switch  class="fr mood-curtain-switch" v-model="curtain.on_off">
                  </el-switch>
            </el-option>
          </el-select>
            <el-button class="fr" type="primary" round  @click="saveMood(mood)">save</el-button>
           </div>
           
            
        </el-card>
        <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="addMood()" >Add</el-button>
            <el-button @click="closeDialog">Cancel</el-button>
        </div>
    </div>
</template>
<style>
.mood-curtain-switch {
  margin-right: 20px;
  line-height: 1 !important;
}
</style>

<script>
import http from "../../../assets/js/http";
import ac from "../devices/ac/ac";
import light from "../devices/light/light";
import led from "../devices/led/led";
import curtain from "../devices/curtain/curtain";
import music from "../devices/music/music";
export default {
  data() {
    return {
      moodList: {},
      oldMoodList: {},
      moodLoading: true,
      mood_name: "",
      devicetypes: [],
      curtains: [],
      allCurtains: [],
      deviceTypeOptions: [
        { label: "AC", value: "ac" },
        { label: "Light", value: "light" },
        { label: "LED", value: "led" },
        { label: "Curtain", value: "curtain" }
        // { label: "Music", value: "music" }
      ]
    };
  },
  // props: ['device'],
  methods: {
    switch_change(val, deviceList) {
      var udpArr = [];
      if (val) {
        for (var device of deviceList) {
          switch (device.devicetype) {
            case "ac":
              if (device.status_1 == "on") {
                udpArr.push(ac.get_switch_change(true, device));
                switch (device.status_2) {
                  case "cool":
                    udpArr.push(ac.get_coolbtn(device));
                    udpArr.push(ac.get_cooltmp_change(device.status_4, device));
                    break;
                  case "fan":
                    udpArr.push(ac.get_fanbtn(device));
                    udpArr.push(ac.get_cooltmp_change(device.status_4, device));
                    break;
                  case "heat":
                    udpArr.push(ac.get_heatbtn(device));
                    udpArr.push(ac.get_heattmp_change(device.status_5, device));
                    break;
                  case "auto":
                    udpArr.push(ac.get_autobtn(device));
                    udpArr.push(ac.get_autotmp_change(device.status_6, device));
                    break;
                }
                switch (device.status_3) {
                  case "wind_auto":
                    udpArr.push(ac.get_wind_change("0", device));
                    break;
                  case "high":
                    udpArr.push(ac.get_wind_change("1", device));
                    break;
                  case "middle":
                    udpArr.push(ac.get_wind_change("2", device));
                    break;
                  case "low":
                    udpArr.push(ac.get_wind_change("3", device));
                    break;
                }
                // udpArr.push(ac.get_cooltmp_change(device.status_4, device));
                // udpArr.push(ac.get_heattmp_change(device.status_5, device));
                // udpArr.push(ac.get_autotmp_change(device.status_6, device));
              } else {
                udpArr.push(ac.get_switch_change(false, device));
              }
              break;
            case "light":
              if (device.status_1 == "on") {
                var deviceProperty = {
                  brightness: 100
                };
                udpArr.push(
                  light.get_switch_change(true, device, deviceProperty)
                );
                // udpArr.push(light.get_slider_change(true, device));
              } else {
                var deviceProperty = {
                  brightness: 0
                };
                udpArr.push(
                  light.get_switch_change(false, device, deviceProperty)
                );
              }
              break;
            case "led":
              if (device.status_1 == "on") {
                var deviceProperty = {
                  color: device.status_2
                };
                udpArr.push(
                  led.get_switch_change(true, device, deviceProperty)
                );
                // udpArr.push(light.get_slider_change(true, device));
              } else {
                var deviceProperty = {
                  color: "#000000"
                };
                udpArr.push(
                  led.get_switch_change(false, device, deviceProperty)
                );
              }
              break;
            case "curtain":
              if (device.status_1 == "on") {
                udpArr.push(curtain.get_switch_change(true, device));
                // udpArr.push(light.get_slider_change(true, device));
              } else {
                udpArr.push(curtain.get_switch_change(false, device));
              }
              break;
            case "music":
              break;
          }
        }
      } else {
        this.$emit("off", true);
      }
      var len = udpArr.length;
      // console.log(udpArr)
      var i = 0;
      var vm = this;
      var forudpArr = setInterval(function() {
        vm.apiGet("udp/sendUdp.php", udpArr[i]).then(res => {
          // console.log("res = ", _g.j2s(res));
          // _g.closeGlobalLoading()
        });
        i++;
        if (i >= len) {
          clearInterval(forudpArr);
        }
      }, 100);
    },
    closeDialog() {
      this.$emit("close", false);
    },
    getMood() {
      this.moodLoading = true;
      var data = {
        params: {
          address: this.room.address,
          floor: this.room.floor,
          room: this.room.room
        }
      };
      this.apiGet("device/mood.php?action=search", data).then(res => {
        if (res) {
          var moodList = {};

          for (var mood of res) {
            if (!moodList[mood.mood]) {
              var moodObj = {
                mood: mood.mood,
                on_off: false,
                add: false,
                deviceList: []
              };
              moodList[mood.mood] = moodObj;
            }
            moodList[mood.mood].deviceList.push(mood);
          }
          this.oldMoodList = moodList;
          this.moodList = moodList;
          this.moodLoading = false;
          // console.log(this.moodList);
        }
      });
    },
    saveMood(mood) {
      // for(var oldMood in this.oldMoodList){
      //   // var mood = this.oldMoodList[oldMood];
      //   if(mood.mood == this.oldMoodList[oldMood].mood){
      //     _g.toastMsg("error", "Repetition of mood names");
      //     return
      //   }
      // }
      if (mood.mood == "" || !mood.mood) {
        _g.toastMsg("error", "Mood name cannot be empty");
        return;
      }
      if (!this.devicetypes.length > 0) {
        _g.toastMsg("error", "Select at least one device type");
        return;
      }
      var curtains = [];
      for (var curtain_key of this.curtains) {
        for (var curtain of this.allCurtains) {
          if (curtain.id == curtain_key) {
            curtains.push(curtain);
          }
        }
      }
      var data = {
        params: {
          mood: mood.mood,
          address: this.room.address,
          floor: this.room.floor,
          room: this.room.room,
          devicetypes: this.devicetypes,
          curtains: curtains
        }
      };
      // console.log(data);
      this.apiGet("device/mood.php?action=insert", data).then(res => {
        if (res[0]) {
          _g.toastMsg("success", res[1]);
        } else {
          _g.toastMsg("error", res[1]);
        }
        this.getMood();
      });
    },
    addMood() {
      var newMood = {
        mood: "",
        on_off: false,
        add: true,
        deviceList: []
      };
      this.$set(this.moodList, "new_mood", newMood);
    },
    deleteMood(mood) {
      this.$confirm("Are you sure to delete this mood?", "Tips", {
        confirmButtonText: "Yse",
        cancelButtonText: "No",
        type: "warning"
      }).then(() => {
        var data = {
          params: {
            mood: mood.mood,
            address: this.room.address,
            floor: this.room.floor,
            room: this.room.room
          }
        };
        this.apiGet("device/mood.php?action=delete", data).then(res => {
          if (res[0]) {
            _g.toastMsg("success", res[1]);
          } else {
            _g.toastMsg("error", res[1]);
          }
          this.getMood();
        });
      });
    }
  },
  created() {
    this.getMood();
    console.log('mood')
  },
  mounted() {
    var allCurtains = [];
    for (var type of this.room.typeList) {
      if (type.name == "curtain") {
        allCurtains = type.deviceList;
      }
    }
    this.allCurtains = allCurtains;
  },
  components: {},
  computed: {},
  props: ["room"],
  mixins: [http]
};
</script>