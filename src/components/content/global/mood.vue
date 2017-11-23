<template>
    <div>
    <el-card v-for="mood in moodList" class="box-card" :span="24">
            <span>{{mood.mood}}</span>
            <el-switch v-model="mood.on_off">
            </el-switch>
            <a class="fa fa-close"></a>
        </el-card>
        <div slot="footer" class="dialog-footer">
            <el-button type="primary" @click="addMood()" >Add</el-button>
            <el-button @click="showDialog = false">Cancel</el-button>
        </div>
    </div>
</template>

<script>
import http from "../../../assets/js/http";
export default {
  data() {
    return {
      moodList:[],
      mood_name:"",
    };
  },
  // props: ['device'],
  methods: {
    switch_change(val) {
      lightApi.switch_change(val, this.device, this.deviceProperty);
    },
    getMood() {
      var data = {
        params: {
          room: this.room.id
        }
      };
      this.apiGet("device/mood.php?action=search", data).then(res => {
        if (res) {
          var moodList = []
          for(mood of res){
            if(moodList.indexOf(mood.mood) == -1){
              
            }
          }
        }
      });
    },
    addMood() {
      var data = {
        params: {
          mood: this.mood_name,
          room: this.room.id
        }
      };
      this.apiGet("device/mood.php?action=add", data).then(res => {
        if (res) {
          this.moodList = res
        }
      });
    },
    deleteMood() {
      var data = {
        params: {
          mood: this.mood.mood,
        }
      };
      this.apiGet("device/mood.php?action=delete", data).then(res => {
        if (res) {
          this.moodList = res
        }
      });
    }
  },
  mounted() {
    console.log("light vue");
    lightApi.readStatus(this.device, this.deviceProperty);
  },
  components: {},
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