<template>
    <div  v-loading="moodLoading">
    <el-card v-for="mood in moodList" class="box-card m-b-15" :span="24">
            <div v-show="!mood.add">
              <span>{{mood.mood}}</span>
              <a  class="fa fa-close fr m-l-10" style="font-size:20px;color:#ff4949;" @click="deleteMood(mood)"></a>
                  <el-switch class="fr" v-model="mood.on_off" @change="switch_change(mood.deviceList)">
                  </el-switch>
            </div>
           <div v-show="mood.add" >
            <el-input  v-model="mood.mood" style="width:100px; "></el-input>
            <el-select v-model="devicetypes" multiple placeholder="Please choose">
            <el-option
              v-for="item in deviceTypeOptions"
              :key="item.value"
              :label="item.label"
              :value="item.value">
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

<script>
import http from "../../../assets/js/http";
import ac from "../devices/ac/ac";
import ight from "../devices/ac/ac";
import led from "../devices/ac/ac";
import curtain from "../devices/ac/ac";
import music from "../devices/ac/ac";
export default {
  data() {
    return {
      moodList: {},
      oldMoodList: {},
      moodLoading: true,
      mood_name: "",
      devicetypes: [],
      deviceTypeOptions: [
        { label: "AC", value: "ac" },
        { label: "Light", value: "light" },
        { label: "LED", value: "led" },
        { label: "Curtain", value: "curtain" },
        { label: "Music", value: "music" }
      ]
    };
  },
  // props: ['device'],
  methods: {
    switch_change(deviceList) {
      for(var device of deviceList){
        switch(device.devicetype){
          case "ac":
          break
          case "light":
          break
          case "led":
          break
          case "curtain":
          break
          case "music":
          break
        }
      }
    },
    closeDialog(){
      this.$emit("close",false)
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
          console.log(this.moodList);
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
      var data = {
        params: {
          mood: mood.mood,
          address: this.room.address,
          floor: this.room.floor,
          room: this.room.room,
          devicetypes: this.devicetypes
        }
      };
      console.log(this.devicetypes);
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
        mood: "mood",
        on_off: false,
        add: true,
        deviceList: []
      };
      this.$set(this.moodList, "mood", newMood);
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
  },
  mounted() {},
  components: {
  },
  computed: {
    device() {
      var device = this.$store.state.device;
      return device;
    }
  },
  props: ["room"],
  mixins: [http]
};
</script>