<template>
    <div   v-loading="moodLoading">
      <div class="mood-container">
    <el-card v-for="(mood,key) in moodList" :key = "key" class="box-card m-r-5 m-b-15" :span="24">
            <div v-show="!mood.add" style="line-height:40px" >
              <span>{{mood.mood}}</span>
              <a  class="fa fa-close fr m-l-10 m-t-10" style="font-size:20px;color:#ff4949;" @click="deleteMood(mood)"></a>
              <el-button type="primary" @click="run(mood.deviceList)" class="fr">Run</el-button>
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
      </div>
      <div slot="footer" class="dialog-footer">
          <el-button type="primary" @click="addMood()" >Add</el-button>
          <el-button @click="closeDialog">Cancel</el-button>
      </div>
    </div>
</template>
<style>
.mood-container {
  overflow: scroll;
  max-height: 350px;
}
.mood-curtain-switch {
  margin-right: 20px;
  line-height: 1 !important;
}
</style>

<script>
import http from "../../assets/js/http";
import udpArr from "../Content/devices/udpArr";
import ac from "../Content/devices/ac/api";
import light from "../Content/devices/light/api";
import led from "../Content/devices/led/api";
import curtain from "../Content/devices/curtain/api";
import music from "../Content/devices/music/api";
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
    // run(mood) {
    //   var data = {
    //     params: {
    //       mood: mood,
    //       address: this.room.address,
    //       floor: this.room.floor,
    //       room: this.room.room,
    //     }
    //   };
    //   this.apiGet("device/mood.php?action=run", data).then(res => {
    //   });
    // },
    run(deviceList) {
      udpArr.sendUdpArr(deviceList);
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
      this.apiGet("admin/mood", data).then(res => {
        this.handelResponse(res, data => {
          var moodList = {};

          for (var mood of data) {
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
        });
        this.moodLoading = false;
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
        mood: mood.mood,
        address: this.room.address,
        floor: this.room.floor,
        room: this.room.room,
        devicetypes: this.devicetypes,
        curtains: curtains
      };
      // console.log(data);
      this.apiPost("admin/mood", data).then(res => {
        this.handelResponse(res, data => {
          _g.toastMsg("success", data);
          this.getMood();
        });
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
          mood: mood.mood,
          address: this.room.address,
          floor: this.room.floor,
          room: this.room.room
        };
        this.apiPost("admin/mood/delete", data).then(res => {
          this.handelResponse(res, data => {
            _g.toastMsg("success", data);
            this.getMood();
          });
        });
      });
    }
  },
  created() {
    this.getMood();
    console.log("mood");
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